class TeacherModel {
  final int id;
  final String name;
  final String username;
  final String? employeeId;
  final String? subject;
  final String? homeroomClass;
  final String? photoUrl;
  final Map<String, dynamic>? classroom;

  TeacherModel({
    required this.id,
    required this.name,
    required this.username,
    this.employeeId,
    this.subject,
    this.homeroomClass,
    this.photoUrl,
    this.classroom,
  });

  factory TeacherModel.fromJson(Map<String, dynamic> json) {
    return TeacherModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      username: json['username'] ?? '',
      employeeId: json['employee_id'],
      subject: json['subject'],
      homeroomClass: json['homeroom_class'],
      photoUrl: json['photo_url'],
      classroom: json['classroom'] is Map<String, dynamic> ? json['classroom'] : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'username': username,
      'employee_id': employeeId,
      'subject': subject,
      'homeroom_class': homeroomClass,
      'photo_url': photoUrl,
      'classroom': classroom,
    };
  }
}
