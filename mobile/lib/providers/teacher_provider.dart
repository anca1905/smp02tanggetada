import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import '../services/api_service.dart';
import '../services/offline_sync_service.dart';
import '../models/offline_attendance_batch.dart';

class TeacherProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  final OfflineSyncService _syncService = OfflineSyncService();

  bool _isLoading = false;
  bool _isSyncing = false;
  String? _errorMessage;

  // Master Data
  List<dynamic> _classes = [];
  List<dynamic> _subjects = [];
  List<dynamic> _allStudents = [];
  Map<String, dynamic>? _dashboardData;

  // Selected state
  dynamic _selectedClass;
  String _selectedSession = 'apel'; // 'apel', 'kelas', 'pulang'
  dynamic _selectedSubject;
  DateTime _selectedDate = DateTime.now();

  // Current roll-call students & attendance status map: { nis: 'present' }
  List<dynamic> _currentStudents = [];
  Map<String, String> _attendanceMap = {};

  // Offline Sync State
  int _queueCount = 0;
  List<OfflineAttendanceBatch> _pendingQueue = [];
  DateTime? _lastSyncTime;
  bool _isOfflineMode = false;

  // Getters
  bool get isLoading => _isLoading;
  bool get isSyncing => _isSyncing;
  String? get errorMessage => _errorMessage;

  List<dynamic> get classes => _classes;
  List<dynamic> get subjects => _subjects;
  List<dynamic> get allStudents => _allStudents;
  Map<String, dynamic>? get dashboardData => _dashboardData;

  dynamic get selectedClass => _selectedClass;
  String get selectedSession => _selectedSession;
  dynamic get selectedSubject => _selectedSubject;
  DateTime get selectedDate => _selectedDate;

  List<dynamic> get currentStudents => _currentStudents;
  Map<String, String> get attendanceMap => _attendanceMap;

  int get queueCount => _queueCount;
  List<OfflineAttendanceBatch> get pendingQueue => _pendingQueue;
  DateTime? get lastSyncTime => _lastSyncTime;
  bool get isOfflineMode => _isOfflineMode;

  // Attendance summary count getters
  int get countPresent => _attendanceMap.values.where((v) => v == 'present').length;
  int get countLate => _attendanceMap.values.where((v) => v == 'late').length;
  int get countSick => _attendanceMap.values.where((v) => v == 'sick').length;
  int get countPermission => _attendanceMap.values.where((v) => v == 'permission').length;
  int get countAbsent => _attendanceMap.values.where((v) => v == 'absent').length;

  TeacherProvider() {
    refreshQueueState();
  }

  Future<void> refreshQueueState() async {
    _pendingQueue = await _syncService.getQueue();
    _queueCount = _pendingQueue.length;
    _lastSyncTime = await _syncService.getLastSyncTime();
    notifyListeners();
  }

  // ==========================================
  // INITIALIZE / LOAD MASTER DATA
  // ==========================================

  Future<void> loadDashboard() async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final response = await _apiService.client.get('/teacher/dashboard');
      if (response.statusCode == 200 && response.data['success'] == true) {
        _dashboardData = response.data['data'];
        _isOfflineMode = false;
      }
    } catch (_) {
      // Offline fallback: non-blocking
      _isOfflineMode = true;
    }

    await loadClassesAndSubjects();
    await refreshQueueState();

    _isLoading = false;
    notifyListeners();
  }

  Future<void> loadClassesAndSubjects() async {
    // 1. Try to load from API
    try {
      final classRes = await _apiService.client.get('/teacher/classes');
      if (classRes.statusCode == 200 && classRes.data['success'] == true) {
        _classes = classRes.data['data'] ?? [];
        await _syncService.saveClasses(_classes);
        _isOfflineMode = false;
      }
    } catch (_) {
      // 2. Load from local cache if API fails
      _classes = await _syncService.getCachedClasses();
      _isOfflineMode = true;
    }

    // Set default selected class if not set
    if (_classes.isNotEmpty && _selectedClass == null) {
      _selectedClass = _classes.first;
    }

    // Load subjects
    try {
      final subjRes = await _apiService.client.get('/teacher/subjects');
      if (subjRes.statusCode == 200 && subjRes.data['success'] == true) {
        _subjects = subjRes.data['data'] ?? [];
        await _syncService.saveSubjects(_subjects);
      }
    } catch (_) {
      _subjects = await _syncService.getCachedSubjects();
    }

    if (_subjects.isNotEmpty && _selectedSubject == null) {
      _selectedSubject = _subjects.first;
    }

    // Load all students across school for school-wide barcode attendance (Apel Pagi & Pulang)
    try {
      final allRes = await _apiService.client.get('/teacher/students?class_id=all');
      if (allRes.statusCode == 200 && allRes.data['success'] == true) {
        _allStudents = allRes.data['data'] ?? [];
        await _syncService.saveAllStudents(_allStudents);
      }
    } catch (_) {
      _allStudents = await _syncService.getCachedAllStudents();
    }

    // If a class is selected, load its students
    if (_selectedClass != null) {
      await loadStudentsForClass(_selectedClass['id']);
    }

    notifyListeners();
  }

  // ==========================================
  // LOAD STUDENTS FOR SELECTED CLASS
  // ==========================================

  Future<void> loadStudentsForClass(int classId) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.client.get('/teacher/students?class_id=$classId');
      if (response.statusCode == 200 && response.data['success'] == true) {
        _currentStudents = response.data['data'] ?? [];
        await _syncService.saveStudents(classId, _currentStudents);
        _isOfflineMode = false;
      }
    } catch (_) {
      // Fallback: Read from local cache
      _currentStudents = await _syncService.getCachedStudents(classId);
      _isOfflineMode = true;
    }

    // Initialize attendance map with 'present' default or from history
    _initAttendanceMap();

    // Check if there's already attendance saved on server for this date & session
    await _checkOnlineHistory();

    _isLoading = false;
    notifyListeners();
  }

  void _initAttendanceMap() {
    _attendanceMap.clear();
    for (var s in _currentStudents) {
      final nis = s['nis']?.toString() ?? '';
      if (nis.isNotEmpty) {
        _attendanceMap[nis] = 'present'; // Default: Hadir
      }
    }
  }

  Future<void> _checkOnlineHistory() async {
    if (_selectedClass == null) return;
    try {
      final dateStr = _selectedDate.toIso8601String().substring(0, 10);
      var url = '/teacher/attendance/history?class_id=${_selectedClass['id']}&date=$dateStr&session_type=$_selectedSession';
      if (_selectedSession == 'kelas' && _selectedSubject != null) {
        url += '&subject_id=${_selectedSubject['id']}';
      }

      final res = await _apiService.client.get(url);
      if (res.statusCode == 200 && res.data['success'] == true && res.data['has_data'] == true) {
        final Map<String, dynamic> history = res.data['data'] ?? {};
        history.forEach((nis, status) {
          if (_attendanceMap.containsKey(nis)) {
            _attendanceMap[nis] = status.toString();
          }
        });
      }
    } catch (_) {
      // Offline: do nothing
    }
  }

  // ==========================================
  // SETTERS & ROLL CALL ACTIONS
  // ==========================================

  void setSelectedClass(dynamic cls) {
    _selectedClass = cls;
    notifyListeners();
    if (cls != null && cls['id'] != null) {
      loadStudentsForClass(cls['id']);
    }
  }

  void setSelectedSession(String session) {
    _selectedSession = session;
    notifyListeners();
    _checkOnlineHistory().then((_) => notifyListeners());
  }

  void setSelectedSubject(dynamic subject) {
    _selectedSubject = subject;
    notifyListeners();
    if (_selectedSession == 'kelas') {
      _checkOnlineHistory().then((_) => notifyListeners());
    }
  }

  void setSelectedDate(DateTime date) {
    _selectedDate = date;
    notifyListeners();
    _checkOnlineHistory().then((_) => notifyListeners());
  }

  void setStudentStatus(String nis, String status) {
    _attendanceMap[nis] = status;
    notifyListeners();
  }

  void markAllPresent() {
    for (var s in _currentStudents) {
      final nis = s['nis']?.toString() ?? '';
      if (nis.isNotEmpty) {
        _attendanceMap[nis] = 'present';
      }
    }
    notifyListeners();
  }

  /// Search student across current class or the entire school student directory
  dynamic findStudent(String query) {
    final q = query.trim();
    if (q.isEmpty) return null;

    // 1. Search in current active classroom list
    for (final s in _currentStudents) {
      if (s['nis']?.toString() == q || s['nisn']?.toString() == q) {
        return s;
      }
    }

    // 2. Search in all students directory across the school
    for (final s in _allStudents) {
      if (s['nis']?.toString() == q || s['nisn']?.toString() == q) {
        return s;
      }
    }

    return null;
  }

  /// Record scanned student, mark status in attendanceMap if in current class,
  /// and persist in offline sync queue.
  Future<void> recordStudentScanned({
    required String nis,
    dynamic classId,
    String? className,
    required String sessionType,
    required DateTime date,
  }) async {
    if (_attendanceMap.containsKey(nis)) {
      _attendanceMap[nis] = 'present';
    }

    final dateStr = date.toIso8601String().substring(0, 10);
    await _syncService.queueSingleScan(
      nis: nis,
      classId: classId,
      className: className,
      sessionType: sessionType,
      date: dateStr,
    );
    await refreshQueueState();
    notifyListeners();
  }

  // ==========================================
  // SAVE ATTENDANCE (OFFLINE-FIRST)
  // ==========================================

  Future<SaveAttendanceResult> saveAttendance() async {
    if (_selectedClass == null || _currentStudents.isEmpty) {
      return SaveAttendanceResult(
        success: false,
        isSynced: false,
        message: 'Tidak ada siswa yang dipilih.',
      );
    }

    _isLoading = true;
    notifyListeners();

    final dateStr = _selectedDate.toIso8601String().substring(0, 10);
    final batch = OfflineAttendanceBatch(
      id: '${_selectedClass['id']}_${_selectedSession}_$dateStr',
      classId: _selectedClass['id'],
      className: _selectedClass['name'] ?? 'Kelas',
      sessionType: _selectedSession,
      subjectId: (_selectedSession == 'kelas' && _selectedSubject != null) ? _selectedSubject['id'] : null,
      subjectName: (_selectedSession == 'kelas' && _selectedSubject != null) ? _selectedSubject['name'] : null,
      date: dateStr,
      attendance: Map<String, String>.from(_attendanceMap),
      studentCount: _currentStudents.length,
      createdAt: DateTime.now(),
    );

    // 1. Selalu amankan ke antrean lokal terlebih dahulu (Zero Data Loss)
    await _syncService.queueAttendance(batch);
    await refreshQueueState();

    // 2. Coba kirim langsung ke server jika ada koneksi
    bool syncedOnline = false;
    String feedbackMessage = '';

    try {
      final res = await _apiService.client.post(
        '/teacher/attendance/sync',
        data: batch.toApiPayload(),
      );

      if (res.statusCode == 200 && res.data['success'] == true) {
        // Hapus dari antrean lokal karena sudah sukses masuk server
        await _syncService.removeBatch(batch.id);
        await refreshQueueState();
        syncedOnline = true;
        _isOfflineMode = false;
        feedbackMessage = 'Presensi berhasil disimpan dan tersinkronkan ke server!';
      } else {
        feedbackMessage = 'Tersimpan di perangkat lokal. Akan disinkronkan saat ada internet.';
      }
    } catch (e) {
      // Jaringan tidak ada / error koneksi
      _isOfflineMode = true;
      feedbackMessage = 'Koneksi internet tidak ada. Data presensi aman tersimpan di HP.';
    }

    _isLoading = false;
    notifyListeners();

    return SaveAttendanceResult(
      success: true,
      isSynced: syncedOnline,
      message: feedbackMessage,
    );
  }

  // ==========================================
  // MANUAL FULL SYNC
  // ==========================================

  Future<SyncResult> syncAllPending() async {
    _isSyncing = true;
    notifyListeners();

    final result = await _syncService.syncAll();
    await refreshQueueState();

    if (result.success) {
      _isOfflineMode = false;
    }

    _isSyncing = false;
    notifyListeners();
    return result;
  }
}

class SaveAttendanceResult {
  final bool success;
  final bool isSynced;
  final String message;

  SaveAttendanceResult({
    required this.success,
    required this.isSynced,
    required this.message,
  });
}
