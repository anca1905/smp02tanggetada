import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  bool _isLoading = false;
  bool _isAuthenticated = false;
  bool _isParent = false;
  Map<String, dynamic>? _user;
  String? _errorMessage;

  bool get isLoading => _isLoading;
  bool get isAuthenticated => _isAuthenticated;
  bool get isParent => _isParent;
  Map<String, dynamic>? get user => _user;
  String? get errorMessage => _errorMessage;

  AuthProvider() {
    _checkAuthStatus();
  }

  Future<void> _checkAuthStatus() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    final isParentPref = prefs.getBool('is_parent') ?? false;
    
    if (token != null) {
      _isAuthenticated = true;
      _isParent = isParentPref;
      notifyListeners();
    }
  }

  Future<bool> login(String nis, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final response = await _apiService.client.post('/student/login', data: {
        'nis': nis,
        'password': password,
      });

      if (response.statusCode == 200 && response.data['success'] == true) {
        final token = response.data['data']['token'];
        final userData = response.data['data']['student'];
        
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', token);
        
        _isAuthenticated = true;
        _user = userData;
        _isLoading = false;
        notifyListeners();
        return true;
      }
    } on DioException catch (e) {
      if (e.response != null && e.response?.data != null) {
        _errorMessage = e.response?.data['message'] ?? 'Login failed';
      } else {
        _errorMessage = 'Connection error. Please check your network.';
      }
    } catch (e) {
      _errorMessage = 'An unexpected error occurred.';
    }

    _isLoading = false;
    notifyListeners();
    return false;
  }

  Future<bool> loginAsParent(String nis, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final response = await _apiService.client.post('/student/parent-login', data: {
        'nis': nis,
        'password': password,
      });

      if (response.statusCode == 200 && response.data['success'] == true) {
        final token = response.data['data']['token'];
        final userData = response.data['data']['student'];
        
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', token);
        await prefs.setBool('is_parent', true);
        
        _isAuthenticated = true;
        _isParent = true;
        _user = {
          'parent_name': userData['parent_name'] ?? 'Orang Tua / Wali',
          'student_name': userData['student_name'] ?? 'Siswa',
          'nis': userData['nis'] ?? '',
          'classroom': {'name': 'Kelas Siswa'}, // Fallback classroom
        };
        _isLoading = false;
        notifyListeners();
        return true;
      }
    } on DioException catch (e) {
      if (e.response != null && e.response?.data != null) {
        _errorMessage = e.response?.data['message'] ?? 'Login failed';
      } else {
        _errorMessage = 'Connection error. Please check your network.';
      }
    } catch (e) {
      _errorMessage = 'An unexpected error occurred.';
    }

    _isLoading = false;
    notifyListeners();
    return false;
  }

  Future<void> logout() async {
    try {
      await _apiService.client.post('/student/logout');
    } catch (e) {
      // Ignore errors on logout
    } finally {
      final prefs = await SharedPreferences.getInstance();
      await prefs.remove('auth_token');
      await prefs.remove('is_parent');
      _isAuthenticated = false;
      _isParent = false;
      _user = null;
      notifyListeners();
    }
  }
}
