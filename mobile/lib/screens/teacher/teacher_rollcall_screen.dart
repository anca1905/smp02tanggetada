import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/teacher_provider.dart';

class TeacherRollcallScreen extends StatefulWidget {
  final String initialSession;

  const TeacherRollcallScreen({
    Key? key,
    this.initialSession = 'apel',
  }) : super(key: key);

  @override
  State<TeacherRollcallScreen> createState() => _TeacherRollcallScreenState();
}

class _TeacherRollcallScreenState extends State<TeacherRollcallScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final p = Provider.of<TeacherProvider>(context, listen: false);
      if (p.classes.isEmpty) {
        p.loadClassesAndSubjects();
      }
    });
  }

  Future<void> _selectDate(BuildContext context, TeacherProvider teacher) async {
    final picked = await showDatePicker(
      context: context,
      initialDate: teacher.selectedDate,
      firstDate: DateTime(2024),
      lastDate: DateTime(2030),
      builder: (context, child) {
        return Theme(
          data: Theme.of(context).copyWith(
            colorScheme: const ColorScheme.light(
              primary: Color(0xFF1E40AF),
              onPrimary: Colors.white,
              onSurface: Color(0xFF0F172A),
            ),
          ),
          child: child!,
        );
      },
    );
    if (picked != null && picked != teacher.selectedDate) {
      teacher.setSelectedDate(picked);
    }
  }

  Future<void> _handleSave(TeacherProvider teacher) async {
    final result = await teacher.saveAttendance();

    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Row(
          children: [
            Icon(
              result.isSynced ? Icons.cloud_done : Icons.cloud_off,
              color: Colors.white,
              size: 20,
            ),
            const SizedBox(width: 10),
            Expanded(
              child: Text(
                result.message,
                style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w500),
              ),
            ),
          ],
        ),
        backgroundColor: result.isSynced ? const Color(0xFF059669) : const Color(0xFFD97706),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        duration: const Duration(seconds: 4),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final teacher = Provider.of<TeacherProvider>(context);
    final students = teacher.currentStudents;
    final attMap = teacher.attendanceMap;

    return Scaffold(
      backgroundColor: const Color(0xFFF1F5F9), // Slate 100
      appBar: AppBar(
        backgroundColor: const Color(0xFF0F172A), // Slate 900
        title: Text(
          'Presensi: ${teacher.selectedSession == 'apel' ? 'Apel Pagi' : teacher.selectedSession == 'pulang' ? 'Pulang' : 'Di Kelas'}',
          style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
        ),
        actions: [
          IconButton(
            tooltip: 'Pilih Tanggal',
            icon: const Icon(Icons.calendar_today, size: 20, color: Colors.white),
            onPressed: () => _selectDate(context, teacher),
          ),
        ],
      ),
      body: Column(
        children: [
          // Filter & Configuration Card
          _buildFilterHeader(context, teacher),

          // Attendance Summary Counter Bar
          _buildCounterBar(teacher),

          // Students List
          Expanded(
            child: teacher.isLoading
                ? const Center(child: CircularProgressIndicator())
                : students.isEmpty
                    ? _buildEmptyState(teacher)
                    : ListView.builder(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                        itemCount: students.length,
                        itemBuilder: (context, index) {
                          final student = students[index];
                          final nis = student['nis']?.toString() ?? '';
                          final currentStatus = attMap[nis] ?? 'present';
                          return _buildStudentItem(index + 1, student, currentStatus, teacher);
                        },
                      ),
          ),

          // Sticky Bottom Save Bar
          if (students.isNotEmpty) _buildBottomActionBar(teacher),
        ],
      ),
    );
  }

  Widget _buildFilterHeader(BuildContext context, TeacherProvider teacher) {
    return Container(
      color: Colors.white,
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      child: Column(
        children: [
          Row(
            children: [
              // Class Selector
              Expanded(
                flex: 3,
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10),
                  decoration: BoxDecoration(
                    color: const Color(0xFFF8FAFC),
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(color: const Color(0xFFCBD5E1)),
                  ),
                  child: DropdownButtonHideUnderline(
                    child: DropdownButton<dynamic>(
                      value: teacher.selectedClass,
                      hint: const Text('Pilih Kelas', style: TextStyle(fontSize: 13)),
                      isExpanded: true,
                      items: teacher.classes.map<DropdownMenuItem<dynamic>>((c) {
                        return DropdownMenuItem<dynamic>(
                          value: c,
                          child: Text(
                            c['name'] ?? 'Kelas',
                            style: const TextStyle(fontSize: 13, fontWeight: FontWeight.bold),
                          ),
                        );
                      }).toList(),
                      onChanged: (val) {
                        if (val != null) teacher.setSelectedClass(val);
                      },
                    ),
                  ),
                ),
              ),

              const SizedBox(width: 8),

              // Date Chip
              InkWell(
                onTap: () => _selectDate(context, teacher),
                borderRadius: BorderRadius.circular(8),
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 11),
                  decoration: BoxDecoration(
                    color: const Color(0xFFF8FAFC),
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(color: const Color(0xFFCBD5E1)),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.event, size: 16, color: Color(0xFF2563EB)),
                      const SizedBox(width: 6),
                      Text(
                        teacher.selectedDate.toIso8601String().substring(0, 10),
                        style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Color(0xFF1E293B)),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),

          const SizedBox(height: 10),

          // Session Chips
          Row(
            children: [
              _buildSessionChip('apel', 'Apel Pagi', teacher),
              const SizedBox(width: 6),
              _buildSessionChip('kelas', 'Di Kelas', teacher),
              const SizedBox(width: 6),
              _buildSessionChip('pulang', 'Pulang', teacher),
            ],
          ),

          // Subject picker if session is 'kelas'
          if (teacher.selectedSession == 'kelas' && teacher.subjects.isNotEmpty) ...[
            const SizedBox(height: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 10),
              decoration: BoxDecoration(
                color: const Color(0xFFF8FAFC),
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: const Color(0xFFCBD5E1)),
              ),
              child: DropdownButtonHideUnderline(
                child: DropdownButton<dynamic>(
                  value: teacher.selectedSubject,
                  hint: const Text('Pilih Mata Pelajaran', style: TextStyle(fontSize: 12)),
                  isExpanded: true,
                  items: teacher.subjects.map<DropdownMenuItem<dynamic>>((s) {
                    return DropdownMenuItem<dynamic>(
                      value: s,
                      child: Text(
                        'Mapel: ${s['name']}',
                        style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w500),
                      ),
                    );
                  }).toList(),
                  onChanged: (val) {
                    if (val != null) teacher.setSelectedSubject(val);
                  },
                ),
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildSessionChip(String sessionKey, String label, TeacherProvider teacher) {
    final isSelected = teacher.selectedSession == sessionKey;
    return Expanded(
      child: GestureDetector(
        onTap: () => teacher.setSelectedSession(sessionKey),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 8),
          decoration: BoxDecoration(
            color: isSelected ? const Color(0xFF1E40AF) : const Color(0xFFF1F5F9),
            borderRadius: BorderRadius.circular(6),
            border: Border.all(
              color: isSelected ? const Color(0xFF1E40AF) : const Color(0xFFE2E8F0),
            ),
          ),
          child: Center(
            child: Text(
              label,
              style: TextStyle(
                fontSize: 11,
                fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                color: isSelected ? Colors.white : const Color(0xFF475569),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildCounterBar(TeacherProvider teacher) {
    return Container(
      color: const Color(0xFFF8FAFC),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
      child: Row(
        children: [
          // Quick "Tandai Semua Hadir" button
          ElevatedButton.icon(
            onPressed: () => teacher.markAllPresent(),
            icon: const Icon(Icons.done_all, size: 14),
            label: const Text('Semua Hadir', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold)),
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF10B981), // Emerald
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 6),
              minimumSize: Size.zero,
              elevation: 0,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6)),
            ),
          ),
          const Spacer(),
          // Stats Badges
          _buildCountBadge('H', teacher.countPresent, const Color(0xFF10B981)),
          const SizedBox(width: 4),
          _buildCountBadge('T', teacher.countLate, const Color(0xFFF59E0B)),
          const SizedBox(width: 4),
          _buildCountBadge('S', teacher.countSick, const Color(0xFFEAB308)),
          const SizedBox(width: 4),
          _buildCountBadge('I', teacher.countPermission, const Color(0xFF3B82F6)),
          const SizedBox(width: 4),
          _buildCountBadge('A', teacher.countAbsent, const Color(0xFFEF4444)),
        ],
      ),
    );
  }

  Widget _buildCountBadge(String label, int count, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 3),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(4),
      ),
      child: Row(
        children: [
          Text(label, style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: color)),
          const SizedBox(width: 3),
          Text('$count', style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: color)),
        ],
      ),
    );
  }

  Widget _buildStudentItem(int no, dynamic student, String currentStatus, TeacherProvider teacher) {
    final nis = student['nis']?.toString() ?? '';
    final name = student['student_name'] ?? '-';
    final gender = student['gender'] == 'M' ? 'L' : student['gender'] == 'F' ? 'P' : '-';

    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 24,
                height: 24,
                decoration: BoxDecoration(
                  color: const Color(0xFFF1F5F9),
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Center(
                  child: Text('$no', style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFF475569))),
                ),
              ),
              const SizedBox(width: 8),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      name,
                      style: const TextStyle(fontSize: 13, fontWeight: FontWeight.bold, color: Color(0xFF0F172A)),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    Text(
                      'NIS: $nis  •  $gender',
                      style: const TextStyle(fontSize: 11, color: Color(0xFF64748B)),
                    ),
                  ],
                ),
              ),
            ],
          ),

          const SizedBox(height: 8),

          // 5 Status Toggle Buttons: [H] [T] [S] [I] [A]
          Row(
            children: [
              _buildStatusButton('present', 'Hadir', 'H', const Color(0xFF10B981), currentStatus, () {
                teacher.setStudentStatus(nis, 'present');
              }),
              const SizedBox(width: 6),
              _buildStatusButton('late', 'Terlambat', 'T', const Color(0xFFF59E0B), currentStatus, () {
                teacher.setStudentStatus(nis, 'late');
              }),
              const SizedBox(width: 6),
              _buildStatusButton('sick', 'Sakit', 'S', const Color(0xFFEAB308), currentStatus, () {
                teacher.setStudentStatus(nis, 'sick');
              }),
              const SizedBox(width: 6),
              _buildStatusButton('permission', 'Izin', 'I', const Color(0xFF3B82F6), currentStatus, () {
                teacher.setStudentStatus(nis, 'permission');
              }),
              const SizedBox(width: 6),
              _buildStatusButton('absent', 'Alpa', 'A', const Color(0xFFEF4444), currentStatus, () {
                teacher.setStudentStatus(nis, 'absent');
              }),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildStatusButton(
    String statusKey,
    String label,
    String code,
    Color color,
    String currentStatus,
    VoidCallback onTap,
  ) {
    final isSelected = currentStatus == statusKey;
    return Expanded(
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(6),
        child: AnimatedContainer(
          duration: const Duration(milliseconds: 150),
          padding: const EdgeInsets.symmetric(vertical: 6),
          decoration: BoxDecoration(
            color: isSelected ? color : Colors.white,
            borderRadius: BorderRadius.circular(6),
            border: Border.all(
              color: isSelected ? color : const Color(0xFFCBD5E1),
              width: isSelected ? 1.5 : 1,
            ),
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(
                code,
                style: TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.bold,
                  color: isSelected ? Colors.white : color,
                ),
              ),
              Text(
                label,
                style: TextStyle(
                  fontSize: 8,
                  fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                  color: isSelected ? Colors.white : const Color(0xFF64748B),
                ),
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildBottomActionBar(TeacherProvider teacher) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 10,
            offset: const Offset(0, -3),
          ),
        ],
      ),
      child: SafeArea(
        top: false,
        child: Row(
          children: [
            Expanded(
              child: ElevatedButton.icon(
                onPressed: teacher.isLoading ? null : () => _handleSave(teacher),
                icon: teacher.isLoading
                    ? const SizedBox(
                        width: 18,
                        height: 18,
                        child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                      )
                    : const Icon(Icons.cloud_upload_outlined, size: 20),
                label: Text(
                  teacher.isLoading ? 'Menyimpan...' : 'Simpan Presensi',
                  style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold),
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF1E40AF),
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 14),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildEmptyState(TeacherProvider teacher) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.people_outline, size: 60, color: Color(0xFF94A3B8)),
            const SizedBox(height: 12),
            const Text(
              'Tidak Ada Siswa',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Color(0xFF334155)),
            ),
            const SizedBox(height: 6),
            Text(
              teacher.selectedClass != null
                  ? 'Belum ada siswa terdaftar pada ${teacher.selectedClass['name']}.'
                  : 'Silakan pilih kelas terlebih dahulu.',
              textAlign: TextAlign.center,
              style: const TextStyle(fontSize: 12, color: Color(0xFF64748B)),
            ),
          ],
        ),
      ),
    );
  }
}
