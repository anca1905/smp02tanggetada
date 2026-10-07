import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/offline_attendance_batch.dart';
import 'api_service.dart';

class OfflineSyncService {
  static const String _keyQueue = 'offline_attendance_queue';
  static const String _keyClasses = 'cached_classes';
  static const String _keySubjects = 'cached_subjects';
  static const String _keyStudentsPrefix = 'cached_students_class_';
  static const String _keyAllStudents = 'cached_all_students';
  static const String _keyLastSync = 'last_sync_timestamp';

  final ApiService _apiService = ApiService();

  // ==========================================
  // CACHE MASTER DATA (Kelas, Siswa, Mapel)
  // ==========================================

  Future<void> saveClasses(List<dynamic> classes) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_keyClasses, jsonEncode(classes));
  }

  Future<List<dynamic>> getCachedClasses() async {
    final prefs = await SharedPreferences.getInstance();
    final data = prefs.getString(_keyClasses);
    if (data == null) return [];
    try {
      return jsonDecode(data) as List<dynamic>;
    } catch (_) {
      return [];
    }
  }

  Future<void> saveAllStudents(List<dynamic> students) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_keyAllStudents, jsonEncode(students));
  }

  Future<List<dynamic>> getCachedAllStudents() async {
    final prefs = await SharedPreferences.getInstance();
    final data = prefs.getString(_keyAllStudents);
    if (data == null) return [];
    try {
      return jsonDecode(data) as List<dynamic>;
    } catch (_) {
      return [];
    }
  }

  Future<void> saveStudents(int classId, List<dynamic> students) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('$_keyStudentsPrefix$classId', jsonEncode(students));
  }

  Future<List<dynamic>> getCachedStudents(int classId) async {
    final prefs = await SharedPreferences.getInstance();
    final data = prefs.getString('$_keyStudentsPrefix$classId');
    if (data == null) return [];
    try {
      return jsonDecode(data) as List<dynamic>;
    } catch (_) {
      return [];
    }
  }

  Future<void> saveSubjects(List<dynamic> subjects) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_keySubjects, jsonEncode(subjects));
  }

  Future<List<dynamic>> getCachedSubjects() async {
    final prefs = await SharedPreferences.getInstance();
    final data = prefs.getString(_keySubjects);
    if (data == null) return [];
    try {
      return jsonDecode(data) as List<dynamic>;
    } catch (_) {
      return [];
    }
  }

  // ==========================================
  // OFFLINE ATTENDANCE QUEUE
  // ==========================================

  Future<void> queueAttendance(OfflineAttendanceBatch batch) async {
    final prefs = await SharedPreferences.getInstance();
    List<OfflineAttendanceBatch> currentQueue = await getQueue();

    // Check if there is an existing unsynced record for the exact same class, date, session, and subject
    // If so, update it instead of creating duplicates
    int existingIndex = currentQueue.indexWhere((item) =>
        item.classId == batch.classId &&
        item.date == batch.date &&
        item.sessionType == batch.sessionType &&
        item.subjectId == batch.subjectId);

    if (existingIndex >= 0) {
      currentQueue[existingIndex] = batch;
    } else {
      currentQueue.insert(0, batch);
    }

    final rawList = currentQueue.map((item) => item.toJson()).toList();
    await prefs.setString(_keyQueue, jsonEncode(rawList));
  }

  Future<void> queueSingleScan({
    required String nis,
    required dynamic classId,
    required String sessionType,
    required String date,
    String? className,
  }) async {
    final effectiveClassId = classId ?? 'all';
    final batchId = '${effectiveClassId}_${sessionType}_$date';
    final prefs = await SharedPreferences.getInstance();
    List<OfflineAttendanceBatch> currentQueue = await getQueue();

    int existingIndex = currentQueue.indexWhere((item) =>
        item.classId.toString() == effectiveClassId.toString() &&
        item.date == date &&
        item.sessionType == sessionType);

    if (existingIndex >= 0) {
      final existing = currentQueue[existingIndex];
      final newAttendance = Map<String, String>.from(existing.attendance);
      newAttendance[nis] = 'present';
      currentQueue[existingIndex] = OfflineAttendanceBatch(
        id: existing.id,
        classId: existing.classId,
        className: existing.className,
        sessionType: existing.sessionType,
        subjectId: existing.subjectId,
        subjectName: existing.subjectName,
        date: existing.date,
        attendance: newAttendance,
        studentCount: newAttendance.length,
        createdAt: DateTime.now(),
      );
    } else {
      currentQueue.insert(
        0,
        OfflineAttendanceBatch(
          id: batchId,
          classId: effectiveClassId,
          className: className ?? (effectiveClassId == 'all' ? 'Seluruh Siswa' : 'Kelas'),
          sessionType: sessionType,
          date: date,
          attendance: {nis: 'present'},
          studentCount: 1,
          createdAt: DateTime.now(),
        ),
      );
    }

    final rawList = currentQueue.map((item) => item.toJson()).toList();
    await prefs.setString(_keyQueue, jsonEncode(rawList));
  }

  Future<List<OfflineAttendanceBatch>> getQueue() async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_keyQueue);
    if (raw == null || raw.isEmpty) return [];

    try {
      final List<dynamic> list = jsonDecode(raw);
      return list.map((item) => OfflineAttendanceBatch.fromJson(item)).toList();
    } catch (e) {
      return [];
    }
  }

  Future<int> getQueueCount() async {
    final queue = await getQueue();
    return queue.length;
  }

  Future<void> removeBatch(String id) async {
    final prefs = await SharedPreferences.getInstance();
    List<OfflineAttendanceBatch> current = await getQueue();
    current.removeWhere((item) => item.id == id);
    final rawList = current.map((item) => item.toJson()).toList();
    await prefs.setString(_keyQueue, jsonEncode(rawList));
  }

  Future<void> clearQueue() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_keyQueue);
  }

  // ==========================================
  // SINKRONISASI KE SERVER (API)
  // ==========================================

  Future<SyncResult> syncAll() async {
    final queue = await getQueue();
    if (queue.isEmpty) {
      return SyncResult(success: true, syncedCount: 0, message: 'Tidak ada data yang perlu disinkronkan.');
    }

    final batchPayloads = queue.map((b) => b.toApiPayload()).toList();

    try {
      final response = await _apiService.client.post(
        '/teacher/attendance/sync',
        data: {'batches': batchPayloads},
      );

      if (response.statusCode == 200 && response.data['success'] == true) {
        final syncedCount = response.data['synced_count'] ?? queue.length;

        // Clear queue upon successful full sync
        await clearQueue();

        // Update last sync time
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString(_keyLastSync, DateTime.now().toIso8601String());

        return SyncResult(
          success: true,
          syncedCount: syncedCount,
          message: 'Berhasil menyinkronkan $syncedCount data presensi ke server.',
        );
      } else {
        return SyncResult(
          success: false,
          syncedCount: 0,
          message: response.data['message'] ?? 'Gagal menyinkronkan data.',
        );
      }
    } catch (e) {
      return SyncResult(
        success: false,
        syncedCount: 0,
        message: 'Koneksi gagal: Periksa kembali internet Anda. Data tetap aman di perangkat.',
      );
    }
  }

  Future<DateTime?> getLastSyncTime() async {
    final prefs = await SharedPreferences.getInstance();
    final str = prefs.getString(_keyLastSync);
    if (str == null) return null;
    return DateTime.tryParse(str);
  }
}

class SyncResult {
  final bool success;
  final int syncedCount;
  final String message;

  SyncResult({
    required this.success,
    required this.syncedCount,
    required this.message,
  });
}
