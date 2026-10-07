import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../providers/auth_provider.dart';
import '../../providers/teacher_provider.dart';
import 'teacher_notification_screen.dart';
import 'teacher_rollcall_screen.dart';
import 'teacher_barcode_scanner_screen.dart';
import 'teacher_settings_screen.dart';
import 'teacher_sync_screen.dart';

class TeacherHomeScreen extends StatefulWidget {
  const TeacherHomeScreen({super.key});

  @override
  State<TeacherHomeScreen> createState() => _TeacherHomeScreenState();
}

class _TeacherHomeScreenState extends State<TeacherHomeScreen> {
  bool _hasUnreadNotifications = true;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<TeacherProvider>(context, listen: false).loadDashboard();
      _checkUnreadNotifications();
    });
  }

  Future<void> _checkUnreadNotifications() async {
    final prefs = await SharedPreferences.getInstance();
    final readIds = prefs.getStringList('teacher_read_notifications') ?? [];
    if (mounted) {
      setState(() {
        _hasUnreadNotifications = readIds.length < 4;
      });
    }
  }

  void _navigateToRollcall(String sessionType) {
    final teacherProvider = Provider.of<TeacherProvider>(context, listen: false);
    teacherProvider.setSelectedSession(sessionType);
    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => TeacherRollcallScreen(initialSession: sessionType),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<AuthProvider>(context);
    final teacher = Provider.of<TeacherProvider>(context);
    final user = auth.user ?? {};

    final teacherName = user['name'] ?? 'Bapak/Ibu Guru';
    final homeroomClass = user['homeroom_class'] ?? user['classroom']?['name'] ?? 'Guru Mata Pelajaran';

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        backgroundColor: const Color(0xFF0F172A),
        elevation: 0,
        titleSpacing: 16,
        title: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(7),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFF3B82F6), Color(0xFF1D4ED8)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(10),
                boxShadow: [
                  BoxShadow(
                    color: const Color(0xFF2563EB).withOpacity(0.35),
                    blurRadius: 6,
                    offset: const Offset(0, 2),
                  ),
                ],
              ),
              child: const Icon(Icons.school, color: Colors.white, size: 20),
            ),
            const SizedBox(width: 10),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'SIMS Terpadu',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                Text(
                  'Portal Presensi Guru',
                  style: TextStyle(
                    color: Colors.blue.shade200,
                    fontSize: 11,
                  ),
                ),
              ],
            ),
          ],
        ),
        actions: [
          // Notification Icon with Functional Unread Badge
          IconButton(
            tooltip: 'Notifikasi & Pengumuman',
            icon: Stack(
              clipBehavior: Clip.none,
              children: [
                const Icon(Icons.notifications_none_rounded, color: Colors.white, size: 24),
                if (_hasUnreadNotifications)
                  Positioned(
                    top: 1,
                    right: 1,
                    child: Container(
                      width: 8,
                      height: 8,
                      decoration: BoxDecoration(
                        color: const Color(0xFFEF4444),
                        shape: BoxShape.circle,
                        border: Border.all(color: const Color(0xFF0F172A), width: 1.5),
                      ),
                    ),
                  ),
              ],
            ),
            onPressed: () async {
              await Navigator.of(context).push(
                MaterialPageRoute(builder: (_) => const TeacherNotificationScreen()),
              );
              _checkUnreadNotifications();
            },
          ),
          // Settings Icon
          IconButton(
            tooltip: 'Pengaturan',
            icon: const Icon(Icons.settings_outlined, color: Colors.white, size: 24),
            onPressed: () async {
              final tp = Provider.of<TeacherProvider>(context, listen: false);
              await Navigator.of(context).push(
                MaterialPageRoute(builder: (_) => const TeacherSettingsScreen()),
              );
              if (mounted) {
                tp.loadDashboard();
              }
            },
          ),
          const SizedBox(width: 4),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          await teacher.loadDashboard();
          await _checkUnreadNotifications();
        },
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 18),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Teacher Profile Banner Card
              _buildTeacherHeaderCard(context, teacherName, homeroomClass),

              const SizedBox(height: 22),

              // Section: Menu Presensi Siswa
              Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(4),
                    decoration: BoxDecoration(
                      color: const Color(0xFF1E3A8A).withOpacity(0.08),
                      borderRadius: BorderRadius.circular(6),
                    ),
                    child: const Icon(
                      Icons.grid_view_rounded,
                      size: 20,
                      color: Color(0xFF1E3A8A),
                    ),
                  ),
                  const SizedBox(width: 8),
                  const Text(
                    'Menu Presensi Siswa',
                    style: TextStyle(
                      fontSize: 16.5,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF0F265C),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 2),
              Padding(
                padding: const EdgeInsets.only(left: 32),
                child: Text(
                  'Pilih menu yang ingin Anda akses',
                  style: TextStyle(
                    fontSize: 12,
                    color: Colors.grey.shade600,
                  ),
                ),
              ),
              const SizedBox(height: 14),

              // 2x2 Attendance Grid
              Row(
                children: [
                  Expanded(
                    child: _buildGridAttendanceCard(
                      title: 'Apel Pagi',
                      subtitle: 'Absensi kehadiran saat upacara / baris pagi',
                      icon: Icons.wb_sunny_rounded,
                      accentColor: const Color(0xFFF59E0B),
                      iconBgColor: const Color(0xFFFEF3C7),
                      onTap: () => _navigateToRollcall('apel'),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _buildGridAttendanceCard(
                      title: 'Masuk Kelas\n(Mata Pelajaran)',
                      subtitle: 'Absensi siswa per jam pelajaran di ruang kelas',
                      icon: Icons.menu_book_rounded,
                      accentColor: const Color(0xFF2563EB),
                      iconBgColor: const Color(0xFFEFF6FF),
                      onTap: () => _navigateToRollcall('kelas'),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              Row(
                children: [
                  Expanded(
                    child: _buildGridAttendanceCard(
                      title: 'Pulang Sekolah',
                      subtitle: 'Pengecekan kehadiran siswa saat jam kepulangan',
                      icon: Icons.home_rounded,
                      accentColor: const Color(0xFF10B981),
                      iconBgColor: const Color(0xFFECFDF5),
                      onTap: () => _navigateToRollcall('pulang'),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _buildMotivationCard(),
                  ),
                ],
              ),

              const SizedBox(height: 20),

              // Offline Sync Status Card
              _buildSyncStatusCard(context, teacher),
              const SizedBox(height: 40),
            ],
          ),
        ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () {
          final tp = Provider.of<TeacherProvider>(context, listen: false);
          if (tp.selectedClass == null && tp.classes.isNotEmpty) {
            tp.setSelectedClass(tp.classes.first);
          }
          if (tp.selectedClass == null) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Memuat data kelas, mohon tunggu...')),
            );
            return;
          }
          Navigator.of(context).push(
            MaterialPageRoute(
              builder: (_) => TeacherBarcodeScannerScreen(
                sessionType: tp.selectedSession,
                className: tp.selectedClass['name'] ?? 'Kelas',
                classId: tp.selectedClass['id'],
                date: tp.selectedDate,
                subjectId: tp.selectedSubject?['id'],
              ),
            ),
          );
        },
        backgroundColor: const Color(0xFF1E40AF),
        foregroundColor: Colors.white,
        icon: const Icon(Icons.qr_code_scanner, size: 22),
        label: const Text(
          'Scan Barcode',
          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
        ),
      ),
    );
  }

  Widget _buildTeacherHeaderCard(BuildContext context, String name, String homeroom) {
    return InkWell(
      onTap: () {
        Navigator.of(context).push(
          MaterialPageRoute(builder: (_) => const TeacherSettingsScreen()),
        );
      },
      borderRadius: BorderRadius.circular(20),
      child: Container(
        width: double.infinity,
        height: 120,
        decoration: BoxDecoration(
          gradient: const LinearGradient(
            colors: [Color(0xFF2563EB), Color(0xFF1D4ED8), Color(0xFF1E40AF)],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
          borderRadius: BorderRadius.circular(20),
          boxShadow: [
            BoxShadow(
              color: const Color(0xFF1E40AF).withOpacity(0.25),
              blurRadius: 14,
              offset: const Offset(0, 5),
            ),
          ],
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(20),
          child: Stack(
            children: [
              // School Building Illustration on Right
              Positioned(
                right: 6,
                bottom: 0,
                top: 10,
                child: SizedBox(
                  width: 135,
                  height: 110,
                  child: CustomPaint(
                    painter: _SchoolBuildingPainter(),
                  ),
                ),
              ),
              // User Details (Left)
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 14, 120, 14),
                child: Row(
                  children: [
                    Container(
                      width: 54,
                      height: 54,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        color: Colors.white.withOpacity(0.22),
                        border: Border.all(
                          color: Colors.white.withOpacity(0.4),
                          width: 1.5,
                        ),
                      ),
                      child: const Icon(Icons.person, color: Colors.white, size: 34),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Text(
                            name,
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 16.5,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 2),
                          Text(
                            homeroom,
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: TextStyle(
                              color: Colors.white.withOpacity(0.88),
                              fontSize: 12.5,
                            ),
                          ),
                          const SizedBox(height: 6),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 3),
                            decoration: BoxDecoration(
                              color: Colors.white.withOpacity(0.2),
                              borderRadius: BorderRadius.circular(16),
                            ),
                            child: const Text(
                              'SMP Negeri 2 Tanggetada',
                              style: TextStyle(
                                color: Colors.white,
                                fontSize: 10.5,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildGridAttendanceCard({
    required String title,
    required String subtitle,
    required IconData icon,
    required Color accentColor,
    required Color iconBgColor,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(16),
      child: Container(
        height: 116,
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: const Color(0xFFE2E8F0)),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 6,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(16),
          child: Stack(
            children: [
              // Left vertical colored accent bar
              Positioned(
                left: 0,
                top: 0,
                bottom: 0,
                child: Container(
                  width: 4.5,
                  color: accentColor,
                ),
              ),
              // Card Details
              Padding(
                padding: const EdgeInsets.fromLTRB(10, 12, 10, 10),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      width: 44,
                      height: 44,
                      decoration: BoxDecoration(
                        color: iconBgColor,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Icon(icon, color: accentColor, size: 24),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Expanded(
                                child: Text(
                                  title,
                                  style: const TextStyle(
                                    fontSize: 13,
                                    fontWeight: FontWeight.bold,
                                    color: Color(0xFF0F172A),
                                    height: 1.18,
                                  ),
                                ),
                              ),
                              const Icon(
                                Icons.chevron_right_rounded,
                                size: 18,
                                color: Color(0xFF2563EB),
                              ),
                            ],
                          ),
                          const SizedBox(height: 4),
                          Text(
                            subtitle,
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                            style: const TextStyle(
                              fontSize: 10,
                              color: Color(0xFF64748B),
                              height: 1.25,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildMotivationCard() {
    return Container(
      height: 116,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 6,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(16),
        child: Stack(
          children: [
            Positioned(
              right: 0,
              bottom: 0,
              child: Container(
                width: 80,
                height: 80,
                decoration: BoxDecoration(
                  gradient: RadialGradient(
                    colors: [
                      const Color(0xFFEFF6FF).withOpacity(0.8),
                      Colors.white.withOpacity(0.0),
                    ],
                  ),
                ),
              ),
            ),
            Padding(
              padding: const EdgeInsets.fromLTRB(10, 10, 8, 8),
              child: Row(
                children: [
                  Expanded(
                    flex: 3,
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          '~ ',
                          style: TextStyle(
                            color: Color(0xFF1E3A8A),
                            fontWeight: FontWeight.bold,
                            fontSize: 13,
                          ),
                        ),
                        Expanded(
                          child: Text(
                            'Bersama\nMewujudkan\nPendidikan yang\nLebih Baik',
                            style: GoogleFonts.caveat(
                              textStyle: const TextStyle(
                                fontSize: 13.5,
                                fontWeight: FontWeight.bold,
                                fontStyle: FontStyle.italic,
                                color: Color(0xFF1E3A8A),
                                height: 1.15,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  Expanded(
                    flex: 2,
                    child: SizedBox(
                      height: 90,
                      child: CustomPaint(
                        painter: _BooksAndPlantPainter(),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSyncStatusCard(BuildContext context, TeacherProvider teacher) {
    final isSynced = teacher.queueCount == 0;

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 6,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(16),
        child: Stack(
          children: [
            Positioned(
              right: 8,
              bottom: 4,
              child: Icon(
                Icons.cloud_upload_rounded,
                size: 88,
                color: const Color(0xFFBFDBFE).withOpacity(0.35),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        width: 36,
                        height: 36,
                        decoration: const BoxDecoration(
                          color: Color(0xFFEFF6FF),
                          shape: BoxShape.circle,
                        ),
                        child: const Icon(
                          Icons.sync_rounded,
                          color: Color(0xFF2563EB),
                          size: 22,
                        ),
                      ),
                      const SizedBox(width: 8),
                      const Expanded(
                        child: Text(
                          'Penyimpanan Offline & Sinkronisasi',
                          style: TextStyle(
                            fontSize: 13.5,
                            fontWeight: FontWeight.bold,
                            color: Color(0xFF0F172A),
                          ),
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(
                          color: isSynced ? const Color(0xFFDCFCE7) : const Color(0xFFFEF3C7),
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Container(
                              width: 6,
                              height: 6,
                              decoration: BoxDecoration(
                                color: isSynced ? const Color(0xFF16A34A) : const Color(0xFFD97706),
                                shape: BoxShape.circle,
                              ),
                            ),
                            const SizedBox(width: 6),
                            Text(
                              isSynced ? 'Tersinkron' : '${teacher.queueCount} Pending',
                              style: TextStyle(
                                fontSize: 11,
                                fontWeight: FontWeight.bold,
                                color: isSynced ? const Color(0xFF15803D) : const Color(0xFFB45309),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  const Text(
                    'Aplikasi ini bekerja penuh saat internet mati. Anda bisa mengabsen kelas tanpa kuota, dan data akan otomatis dikirim saat sinyal kembali.',
                    style: TextStyle(
                      fontSize: 11.5,
                      color: Color(0xFF64748B),
                      height: 1.45,
                    ),
                  ),
                  const SizedBox(height: 14),
                  InkWell(
                    onTap: () {
                      Navigator.of(context).push(
                        MaterialPageRoute(builder: (_) => const TeacherSyncScreen()),
                      );
                    },
                    borderRadius: BorderRadius.circular(10),
                    child: Container(
                      width: double.infinity,
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 11),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(color: const Color(0xFF93C5FD), width: 1.2),
                      ),
                      child: const Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.manage_search_rounded, size: 20, color: Color(0xFF2563EB)),
                          SizedBox(width: 8),
                          Text(
                            'Buka Pengelola Sinkronisasi',
                            style: TextStyle(
                              fontSize: 13,
                              fontWeight: FontWeight.w600,
                              color: Color(0xFF2563EB),
                            ),
                          ),
                          Spacer(),
                          Icon(Icons.chevron_right_rounded, size: 20, color: Color(0xFF2563EB)),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// Custom Painter: Stylized School Building Illustration
class _SchoolBuildingPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final w = size.width;
    final h = size.height;

    final basePaint = Paint()
      ..color = const Color(0xFFBFDBFE).withOpacity(0.85)
      ..style = PaintingStyle.fill;

    final darkPaint = Paint()
      ..color = const Color(0xFF93C5FD).withOpacity(0.9)
      ..style = PaintingStyle.fill;

    final windowPaint = Paint()
      ..color = Colors.white.withOpacity(0.9)
      ..style = PaintingStyle.fill;

    final bushPaint = Paint()
      ..color = const Color(0xFF6EE7B7).withOpacity(0.5)
      ..style = PaintingStyle.fill;

    // Bushes
    canvas.drawCircle(Offset(w * 0.08, h * 0.92), h * 0.14, bushPaint);
    canvas.drawCircle(Offset(w * 0.92, h * 0.92), h * 0.14, bushPaint);

    // Left Wing
    final leftWingRect = Rect.fromLTWH(w * 0.12, h * 0.46, w * 0.28, h * 0.54);
    canvas.drawRRect(RRect.fromRectAndRadius(leftWingRect, const Radius.circular(2)), basePaint);

    // Right Wing
    final rightWingRect = Rect.fromLTWH(w * 0.60, h * 0.46, w * 0.28, h * 0.54);
    canvas.drawRRect(RRect.fromRectAndRadius(rightWingRect, const Radius.circular(2)), basePaint);

    // Center Body
    final centerRect = Rect.fromLTWH(w * 0.36, h * 0.38, w * 0.28, h * 0.62);
    canvas.drawRRect(RRect.fromRectAndRadius(centerRect, const Radius.circular(2)), darkPaint);

    // Center Gable Roof (Triangle)
    final roofPath = Path()
      ..moveTo(w * 0.33, h * 0.38)
      ..lineTo(w * 0.50, h * 0.18)
      ..lineTo(w * 0.67, h * 0.38)
      ..close();
    canvas.drawPath(roofPath, darkPaint);

    // Spire / Tower on roof
    final towerRect = Rect.fromLTWH(w * 0.46, h * 0.08, w * 0.08, h * 0.12);
    canvas.drawRect(towerRect, basePaint);

    // Flagpole & Flag
    final polePaint = Paint()
      ..color = Colors.white.withOpacity(0.85)
      ..strokeWidth = 1.5
      ..style = PaintingStyle.stroke;
    canvas.drawLine(Offset(w * 0.50, h * 0.08), Offset(w * 0.50, h * 0.01), polePaint);

    final flagPath = Path()
      ..moveTo(w * 0.50, h * 0.01)
      ..lineTo(w * 0.58, h * 0.04)
      ..lineTo(w * 0.50, h * 0.07)
      ..close();
    canvas.drawPath(flagPath, Paint()..color = Colors.white.withOpacity(0.9)..style = PaintingStyle.fill);

    // Clock in gable
    canvas.drawCircle(Offset(w * 0.50, h * 0.29), w * 0.045, windowPaint);

    // Windows in Left Wing
    _drawWindowGrid(canvas, w * 0.16, h * 0.52, w * 0.08, h * 0.12, 2, 2, w * 0.04, h * 0.08, windowPaint);

    // Windows in Right Wing
    _drawWindowGrid(canvas, w * 0.64, h * 0.52, w * 0.08, h * 0.12, 2, 2, w * 0.04, h * 0.08, windowPaint);

    // Center Entrance Arch
    final doorRect = Rect.fromLTWH(w * 0.45, h * 0.68, w * 0.10, h * 0.32);
    canvas.drawRRect(
      RRect.fromRectAndCorners(
        doorRect,
        topLeft: const Radius.circular(6),
        topRight: const Radius.circular(6),
      ),
      Paint()..color = const Color(0xFF1E3A8A).withOpacity(0.7)..style = PaintingStyle.fill,
    );
  }

  void _drawWindowGrid(Canvas canvas, double x, double y, double width, double height, int rows, int cols, double gapX, double gapY, Paint paint) {
    for (int r = 0; r < rows; r++) {
      for (int c = 0; c < cols; c++) {
        final winRect = Rect.fromLTWH(x + c * (width + gapX), y + r * (height + gapY), width, height);
        canvas.drawRRect(RRect.fromRectAndRadius(winRect, const Radius.circular(2)), paint);
      }
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}

// Custom Painter: Stacked Books & Potted Plant Illustration
class _BooksAndPlantPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final w = size.width;
    final h = size.height;

    // Bottom book
    final bottomBookRect = RRect.fromRectAndRadius(
      Rect.fromLTWH(w * 0.05, h * 0.68, w * 0.90, h * 0.18),
      const Radius.circular(4),
    );
    canvas.drawRRect(bottomBookRect, Paint()..color = const Color(0xFF60A5FA)..style = PaintingStyle.fill);

    final bottomPages = RRect.fromRectAndRadius(
      Rect.fromLTWH(w * 0.10, h * 0.72, w * 0.80, h * 0.10),
      const Radius.circular(2),
    );
    canvas.drawRRect(bottomPages, Paint()..color = Colors.white..style = PaintingStyle.fill);

    // Top book
    final topBookRect = RRect.fromRectAndRadius(
      Rect.fromLTWH(w * 0.15, h * 0.48, w * 0.75, h * 0.17),
      const Radius.circular(4),
    );
    canvas.drawRRect(topBookRect, Paint()..color = const Color(0xFF93C5FD)..style = PaintingStyle.fill);

    final topPages = RRect.fromRectAndRadius(
      Rect.fromLTWH(w * 0.20, h * 0.52, w * 0.65, h * 0.09),
      const Radius.circular(2),
    );
    canvas.drawRRect(topPages, Paint()..color = Colors.white..style = PaintingStyle.fill);

    // Pot
    final potPath = Path()
      ..moveTo(w * 0.42, h * 0.48)
      ..lineTo(w * 0.62, h * 0.48)
      ..lineTo(w * 0.59, h * 0.32)
      ..lineTo(w * 0.45, h * 0.32)
      ..close();
    canvas.drawPath(potPath, Paint()..color = const Color(0xFF94A3B8)..style = PaintingStyle.fill);

    // Leaves & Stem
    final leafPaint = Paint()..color = const Color(0xFF34D399)..style = PaintingStyle.fill;
    final darkLeafPaint = Paint()..color = const Color(0xFF10B981)..style = PaintingStyle.fill;

    final stemPaint = Paint()
      ..color = const Color(0xFF059669)
      ..strokeWidth = 2
      ..style = PaintingStyle.stroke;
    canvas.drawLine(Offset(w * 0.52, h * 0.32), Offset(w * 0.52, h * 0.08), stemPaint);

    // Left leaf
    canvas.save();
    canvas.translate(w * 0.44, h * 0.20);
    canvas.rotate(-0.4);
    canvas.drawOval(Rect.fromCenter(center: Offset.zero, width: w * 0.22, height: h * 0.12), leafPaint);
    canvas.restore();

    // Right leaf
    canvas.save();
    canvas.translate(w * 0.60, h * 0.16);
    canvas.rotate(0.35);
    canvas.drawOval(Rect.fromCenter(center: Offset.zero, width: w * 0.20, height: h * 0.11), darkLeafPaint);
    canvas.restore();

    // Top leaf
    canvas.save();
    canvas.translate(w * 0.52, h * 0.08);
    canvas.drawOval(Rect.fromCenter(center: Offset.zero, width: w * 0.14, height: h * 0.14), leafPaint);
    canvas.restore();
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
