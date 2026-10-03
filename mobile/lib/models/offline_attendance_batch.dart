class OfflineAttendanceBatch {
  final String id; // Unique local ID timestamp-based
  final int classId;
  final String className;
  final String sessionType; // 'apel', 'kelas', 'pulang'
  final int? subjectId;
  final String? subjectName;
  final String date; // YYYY-MM-DD
  final Map<String, String> attendance; // nis -> status (present, sick, permission, absent, late)
  final int studentCount;
  final DateTime createdAt;
  bool isSynced;

  OfflineAttendanceBatch({
    required this.id,
    required this.classId,
    required this.className,
    required this.sessionType,
    this.subjectId,
    this.subjectName,
    required this.date,
    required this.attendance,
    required this.studentCount,
    required this.createdAt,
    this.isSynced = false,
  });

  factory OfflineAttendanceBatch.fromJson(Map<String, dynamic> json) {
    Map<String, String> attMap = {};
    if (json['attendance'] is Map) {
      json['attendance'].forEach((key, val) {
        if (val is Map && val['status'] != null) {
          attMap[key.toString()] = val['status'].toString();
        } else {
          attMap[key.toString()] = val.toString();
        }
      });
    }

    return OfflineAttendanceBatch(
      id: json['id'] ?? DateTime.now().millisecondsSinceEpoch.toString(),
      classId: json['class'] is int ? json['class'] : int.tryParse(json['class'].toString()) ?? 0,
      className: json['class_name'] ?? 'Kelas',
      sessionType: json['session_type'] ?? 'kelas',
      subjectId: json['subject_id'] != null ? int.tryParse(json['subject_id'].toString()) : null,
      subjectName: json['subject_name'],
      date: json['date'] ?? '',
      attendance: attMap,
      studentCount: json['student_count'] ?? attMap.length,
      createdAt: json['created_at'] != null 
          ? DateTime.tryParse(json['created_at']) ?? DateTime.now() 
          : DateTime.now(),
      isSynced: json['is_synced'] ?? false,
    );
  }

  Map<String, dynamic> toJson() {
    // Format attendance as required by Laravel API:
    // attendance: { "120001": { "status": "present" } }
    Map<String, dynamic> formattedAtt = {};
    attendance.forEach((nis, status) {
      formattedAtt[nis] = {'status': status};
    });

    return {
      'id': id,
      'class': classId,
      'class_name': className,
      'session_type': sessionType,
      'subject_id': subjectId,
      'subject_name': subjectName,
      'date': date,
      'attendance': formattedAtt,
      'student_count': studentCount,
      'created_at': createdAt.toIso8601String(),
      'is_synced': isSynced,
    };
  }

  // Payload specifically for POST /api/teacher/attendance/sync
  Map<String, dynamic> toApiPayload() {
    Map<String, dynamic> formattedAtt = {};
    attendance.forEach((nis, status) {
      formattedAtt[nis] = {'status': status};
    });

    return {
      'class': classId,
      'session_type': sessionType,
      'subject_id': subjectId,
      'date': date,
      'attendance': formattedAtt,
    };
  }

  String get sessionLabel {
    switch (sessionType) {
      case 'apel':
        return 'Apel Pagi';
      case 'pulang':
        return 'Pulang';
      case 'kelas':
      default:
        return subjectName != null && subjectName!.isNotEmpty 
            ? 'Kelas ($subjectName)' 
            : 'Di Kelas';
    }
  }
}
