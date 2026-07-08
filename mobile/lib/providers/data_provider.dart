import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import '../services/api_service.dart';

class DataProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();

  // Loading state terpisah per fitur agar tidak saling mengganggu
  bool _isLoadingDashboard = false;
  bool _isLoadingSchedules = false;
  bool _isLoadingAssignments = false;
  bool _isLoadingAttendances = false;
  bool _isLoadingGrades = false;
  bool _isLoadingAnnouncements = false;

  String? _errorMessage;

  List<dynamic> _dashboardSchedules = [];
  List<dynamic> _dashboardAssignments = [];
  Map<String, List<dynamic>> _allSchedules = {};
  List<dynamic> _allAssignments = [];
  List<dynamic> _allAttendances = [];
  List<dynamic> _allGrades = [];
  List<dynamic> _allAnnouncements = [];

  // Getters loading per fitur
  bool get isLoadingDashboard => _isLoadingDashboard;
  bool get isLoadingSchedules => _isLoadingSchedules;
  bool get isLoadingAssignments => _isLoadingAssignments;
  bool get isLoadingAttendances => _isLoadingAttendances;
  bool get isLoadingGrades => _isLoadingGrades;
  bool get isLoadingAnnouncements => _isLoadingAnnouncements;

  // Getter isLoading global (untuk backward compat, cek semua state)
  bool get isLoading => _isLoadingDashboard;

  String? get errorMessage => _errorMessage;

  List<dynamic> get dashboardSchedules => _dashboardSchedules;
  List<dynamic> get dashboardAssignments => _dashboardAssignments;
  Map<String, List<dynamic>> get allSchedules => _allSchedules;
  List<dynamic> get allAssignments => _allAssignments;
  List<dynamic> get allAttendances => _allAttendances;
  List<dynamic> get allGrades => _allGrades;
  List<dynamic> get allAnnouncements => _allAnnouncements;

  Future<void> fetchDashboard() async {
    _isLoadingDashboard = true;
    notifyListeners();
    try {
      final response = await _apiService.client.get('/student/dashboard');
      if (response.statusCode == 200 && response.data['success']) {
        _dashboardSchedules = response.data['data']['today_schedules'] ?? [];
        _dashboardAssignments = response.data['data']['upcoming_assignments'] ?? [];
      }
    } on DioException catch (e) {
      _errorMessage = e.message;
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoadingDashboard = false;
    notifyListeners();
  }

  Future<void> fetchSchedules() async {
    _isLoadingSchedules = true;
    notifyListeners();
    try {
      final response = await _apiService.client.get('/student/schedules');
      if (response.statusCode == 200 && response.data['success']) {
        Map<String, dynamic> rawData = response.data['data'];
        _allSchedules = rawData.map(
          (key, value) => MapEntry(key, List<dynamic>.from(value)),
        );
      }
    } on DioException catch (e) {
      _errorMessage = e.message;
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoadingSchedules = false;
    notifyListeners();
  }

  Future<void> fetchAssignments() async {
    _isLoadingAssignments = true;
    notifyListeners();
    try {
      final response = await _apiService.client.get('/student/assignments');
      if (response.statusCode == 200 && response.data['success']) {
        _allAssignments = response.data['data'] ?? [];
      }
    } on DioException catch (e) {
      _errorMessage = e.message;
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoadingAssignments = false;
    notifyListeners();
  }

  Future<void> fetchAttendances() async {
    _isLoadingAttendances = true;
    notifyListeners();
    try {
      final response = await _apiService.client.get('/student/attendances');
      if (response.statusCode == 200 && response.data['success']) {
        _allAttendances = response.data['data'] ?? [];
      }
    } on DioException catch (e) {
      _errorMessage = e.message;
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoadingAttendances = false;
    notifyListeners();
  }

  Future<void> fetchGrades() async {
    _isLoadingGrades = true;
    notifyListeners();
    try {
      final response = await _apiService.client.get('/student/grades');
      if (response.statusCode == 200 && response.data['success']) {
        _allGrades = response.data['data'] ?? [];
      }
    } on DioException catch (e) {
      _errorMessage = e.message;
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoadingGrades = false;
    notifyListeners();
  }

  Future<void> fetchAnnouncements() async {
    _isLoadingAnnouncements = true;
    notifyListeners();
    try {
      final response = await _apiService.client.get('/student/announcements');
      if (response.statusCode == 200 && response.data['success']) {
        _allAnnouncements = response.data['data'] ?? [];
      }
    } on DioException catch (e) {
      _errorMessage = e.message;
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoadingAnnouncements = false;
    notifyListeners();
  }
  bool _isLoadingMaterials = false;
  bool _isSubmittingAssignment = false;
  List<dynamic> _allMaterials = [];

  bool get isLoadingMaterials => _isLoadingMaterials;
  bool get isSubmittingAssignment => _isSubmittingAssignment;
  List<dynamic> get allMaterials => _allMaterials;

  Future<void> fetchMaterials() async {
    _isLoadingMaterials = true;
    notifyListeners();
    try {
      final response = await _apiService.client.get('/student/materials');
      if (response.statusCode == 200 && response.data['success']) {
        _allMaterials = response.data['data'] ?? [];
      }
    } on DioException catch (e) {
      _errorMessage = e.message;
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoadingMaterials = false;
    notifyListeners();
  }

  Future<bool> submitAssignment(int assignmentId, String filePath, String note) async {
    _isSubmittingAssignment = true;
    notifyListeners();
    bool success = false;
    try {
      final formData = FormData.fromMap({
        'file': await MultipartFile.fromFile(filePath),
        'student_note': note,
      });

      final response = await _apiService.client.post(
        '/student/assignments/$assignmentId/submit',
        data: formData,
      );
      
      if (response.statusCode == 200 && response.data['success']) {
        success = true;
        await fetchAssignments(); // Refresh list to get new submission data
      }
    } on DioException catch (e) {
      _errorMessage = e.message;
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isSubmittingAssignment = false;
    notifyListeners();
    return success;
  }
}
