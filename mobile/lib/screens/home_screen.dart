import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../providers/data_provider.dart';
import '../theme/app_theme.dart';
import 'login_screen.dart';
import 'schedule_screen.dart';
import 'assignment_screen.dart';
import 'attendance_screen.dart';
import 'grade_screen.dart';
import 'profile_screen.dart';
import 'announcement_screen.dart';
import 'material_screen.dart';
import 'qr_scan_screen.dart';
import 'package:url_launcher/url_launcher.dart';
import 'leave_request_screen.dart';
class HomeScreen extends StatefulWidget {
  const HomeScreen({Key? key}) : super(key: key);

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;
  bool _isInit = true;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_isInit) {
      final dp = Provider.of<DataProvider>(context, listen: false);
      dp.fetchDashboard();
      dp.fetchAnnouncements();
      _isInit = false;
    }
  }

  void _handleLogout() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    await authProvider.logout();
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => const LoginScreen()),
    );
  }

  Widget _buildHomeTab(BuildContext context) {
    final user = Provider.of<AuthProvider>(context).user;
    final userName = user?['student_name'] ?? 'Siswa';
    final nis = user?['nis'] ?? '-';
    final classroom = user?['classroom']?['name'] ?? '';
    final dataProvider = Provider.of<DataProvider>(context);

    return RefreshIndicator(
      onRefresh: () async {
        await dataProvider.fetchDashboard();
        await dataProvider.fetchAnnouncements();
      },
      child: SingleChildScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header Section with Blue Background
            Stack(
              clipBehavior: Clip.none,
              children: [
                Container(
                  height: 200,
                  padding: const EdgeInsets.fromLTRB(20, 20, 20, 0),
                  decoration: BoxDecoration(
                    color: AppTheme.primaryColor,
                    borderRadius: const BorderRadius.only(
                      bottomLeft: Radius.circular(30),
                      bottomRight: Radius.circular(30),
                    ),
                  ),
                  child: Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      CircleAvatar(
                        radius: 26,
                        backgroundColor: Colors.white.withOpacity(0.25),
                        child: Text(
                          userName.isNotEmpty ? userName[0].toUpperCase() : 'S',
                          style: const TextStyle(
                              fontSize: 22,
                              fontWeight: FontWeight.bold,
                              color: Colors.white),
                        ),
                      ),
                      const SizedBox(width: 14),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text(
                              'Halo,',
                              style: TextStyle(color: Colors.white70, fontSize: 13),
                            ),
                            Text(
                              userName,
                              style: const TextStyle(
                                color: Colors.white,
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                              ),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                            if (classroom.isNotEmpty)
                              Text(
                                '$classroom • NIS $nis',
                                style: const TextStyle(
                                    color: Colors.white70, fontSize: 12),
                              ),
                          ],
                        ),
                      ),
                      Stack(
                        children: [
                          IconButton(
                            icon: const Icon(Icons.notifications_none, color: Colors.white),
                            onPressed: () {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                    builder: (_) => const AnnouncementScreen()),
                              );
                            },
                          ),
                          Positioned(
                            right: 12,
                            top: 12,
                            child: dataProvider.allAnnouncements.isEmpty 
                              ? const SizedBox() 
                              : Container(
                                  padding: const EdgeInsets.all(4),
                                  decoration: const BoxDecoration(
                                    color: Colors.red,
                                    shape: BoxShape.circle,
                                  ),
                                  child: Text('${dataProvider.allAnnouncements.length}', style: const TextStyle(color: Colors.white, fontSize: 8, fontWeight: FontWeight.bold)),
                                ),
                          )
                        ],
                      ),
                      IconButton(
                        icon: const Icon(Icons.logout, color: Colors.white),
                        onPressed: _handleLogout,
                      ),
                    ],
                  ),
                ),
                // Overlapping Card (Jadwal Hari Ini & Ringkasan)
                Positioned(
                  top: 100,
                  left: 16,
                  right: 16,
                  child: Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.05),
                          blurRadius: 10,
                          offset: const Offset(0, 5),
                        )
                      ],
                    ),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Left Side (Jadwal)
                        Expanded(
                          flex: 5,
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                children: [
                                  Column(
                                    children: [
                                      const Text('Kamis', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                                      const Text('09', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 24)),
                                      const Text('Juli 2026', style: TextStyle(fontSize: 10, color: Colors.grey)),
                                    ],
                                  ),
                                  const SizedBox(width: 12),
                                  Container(width: 1, height: 50, color: Colors.grey.withOpacity(0.2)),
                                  const SizedBox(width: 12),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        const Text('Jadwal Hari Ini', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                                        const SizedBox(height: 8),
                                        _buildMiniSchedule('08.00', 'Matematika'),
                                        _buildMiniSchedule('09.40', 'Bahasa Indonesia'),
                                        const SizedBox(height: 8),
                                        GestureDetector(
                                          onTap: () => setState(() => _currentIndex = 1),
                                          child: const Text('Lihat Semua >', style: TextStyle(color: AppTheme.primaryColor, fontSize: 12, fontWeight: FontWeight.bold)),
                                        )
                                      ],
                                    ),
                                  )
                                ],
                              ),
                            ],
                          ),
                        ),
                        Container(width: 1, height: 100, color: Colors.grey.withOpacity(0.2)),
                        const SizedBox(width: 12),
                        // Right Side (Ringkasan)
                        Expanded(
                          flex: 4,
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Ringkasan', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                              const SizedBox(height: 8),
                              Row(
                                children: [
                                  Expanded(child: _buildSummaryBox('Kehadiran', '95%', Colors.green)),
                                  const SizedBox(width: 8),
                                  Expanded(child: _buildSummaryBox('Nilai Rata-rata', '89', Colors.blue)),
                                ],
                              ),
                              const SizedBox(height: 8),
                              Row(
                                children: [
                                  Expanded(child: _buildSummaryBox('Tugas Belum', '2', Colors.orange)),
                                  const SizedBox(width: 8),
                                  Expanded(child: _buildSummaryBox('Pengumuman', '4', Colors.red)),
                                ],
                              )
                            ],
                          ),
                        )
                      ],
                    ),
                  ),
                ),
              ],
            ),
            
            const SizedBox(height: 90), // Space for overlapping card

            // Menu Utama Grid
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'Menu Utama',
                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: Color(0xFF1A1A2E)),
                  ),
                  TextButton.icon(
                    onPressed: () {
                      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Fitur Edit Menu akan segera hadir')));
                    },
                    icon: const Icon(Icons.edit, size: 16, color: AppTheme.primaryColor),
                    label: const Text('Edit', style: TextStyle(color: AppTheme.primaryColor)),
                  )
                ],
              ),
            ),
            
            Container(
              color: Colors.white,
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
              child: Column(
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      _buildMenuIcon(Icons.calendar_month_outlined, 'Jadwal', const Color(0xFF3B82F6), () => setState(() => _currentIndex = 1)),
                      _buildMenuIcon(Icons.assignment_outlined, 'Tugas', const Color(0xFFF59E0B), () => Navigator.push(context, MaterialPageRoute(builder: (_) => const AssignmentScreen()))),
                      _buildMenuIcon(Icons.how_to_reg_outlined, 'Presensi', const Color(0xFF10B981), () => Navigator.push(context, MaterialPageRoute(builder: (_) => const AttendanceScreen()))),
                      _buildMenuIcon(Icons.bar_chart, 'Nilai', const Color(0xFF8B5CF6), () => Navigator.push(context, MaterialPageRoute(builder: (_) => const GradeScreen()))),
                      _buildMenuIcon(Icons.campaign_outlined, 'Pengumuman', const Color(0xFFEF4444), () => Navigator.push(context, MaterialPageRoute(builder: (_) => const AnnouncementScreen()))),
                    ],
                  ),
                  const SizedBox(height: 20),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      _buildMenuIcon(Icons.folder_copy_outlined, 'Materi', const Color(0xFF06B6D4), () => Navigator.push(context, MaterialPageRoute(builder: (_) => const MaterialScreen()))),
                      _buildMenuIcon(Icons.person_outline, 'Profil', const Color(0xFF64748B), () => setState(() => _currentIndex = 2)),
                      _buildMenuIcon(Icons.qr_code_scanner, 'Scan QR', const Color(0xFF10B981), () => Navigator.push(context, MaterialPageRoute(builder: (_) => const QrScanScreen()))),
                      _buildMenuIcon(Icons.download_outlined, 'Raport', const Color(0xFF3B82F6), () => _showDownloadRaportDialog(context)),
                      _buildMenuIcon(Icons.chat_bubble_outline, 'Hubungi Guru', const Color(0xFFF59E0B), () => _showTeachersBottomSheet(context)),
                    ],
                  ),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // Horizontal Scroll Section (Tugas, Kalender, Pengumuman)
            SizedBox(
              height: 180,
              child: ListView(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 16),
                children: [
                  _buildScrollableCard('Tugas', 'Lihat Semua', () => Navigator.push(context, MaterialPageRoute(builder: (_) => const AssignmentScreen())), [
                    _buildProgressRow('Fisika', 0.6, Colors.blue),
                    _buildProgressRow('Kimia', 0.2, Colors.red),
                    _buildProgressRow('Bahasa Inggris', 1.0, Colors.green),
                  ]),
                  const SizedBox(width: 16),
                  _buildScrollableCard('Kalender Akademik', 'Lihat Semua', () {
                    ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Kalender Akademik lengkap segera hadir')));
                  }, [
                    _buildCalendarRow('10 Jul', 'Ulangan Harian', 'Matematika'),
                    _buildCalendarRow('15 Jul', 'Pembagian Raport', 'Semester Ganjil'),
                  ]),
                  const SizedBox(width: 16),
                  _buildScrollableCard('Pengumuman Terbaru', 'Lihat Semua', () => Navigator.push(context, MaterialPageRoute(builder: (_) => const AnnouncementScreen())), [
                    _buildAnnouncementRow('Nilai Matematika', '2 jam yang lalu'),
                    _buildAnnouncementRow('Kegiatan Class Meeting', '5 jam yang lalu'),
                  ]),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // Widgets Section (Presensi, Motivasi, Cuaca)
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Row(
                children: [
                  Expanded(
                    child: Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: Colors.blue.shade50,
                        borderRadius: BorderRadius.circular(16),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              const Text('Presensi Hari Ini', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: Colors.blue)),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                decoration: BoxDecoration(color: Colors.green, borderRadius: BorderRadius.circular(8)),
                                child: const Text('Hadir', style: TextStyle(color: Colors.white, fontSize: 10)),
                              )
                            ],
                          ),
                          const SizedBox(height: 8),
                          const Text('Terima kasih, kehadiran Anda telah tercatat.', style: TextStyle(fontSize: 10, color: Colors.black54)),
                          const SizedBox(height: 12),
                          const Text('Jam Absen\n07.32 WIB', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                          const SizedBox(height: 8),
                          GestureDetector(
                            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const AttendanceScreen())),
                            child: Container(
                              width: double.infinity,
                              padding: const EdgeInsets.symmetric(vertical: 4),
                              decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(8)),
                              child: const Center(child: Text('Lihat Riwayat >', style: TextStyle(color: Colors.blue, fontSize: 10, fontWeight: FontWeight.bold))),
                            ),
                          )
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 4, offset: const Offset(0, 2))]
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Motivasi Hari Ini', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                          const SizedBox(height: 8),
                          const Text('“', style: TextStyle(fontSize: 24, color: Colors.blue, fontWeight: FontWeight.bold)),
                          const Text('Belajar sedikit setiap hari lebih baik daripada banyak tapi jarang.', style: TextStyle(fontSize: 11, fontStyle: FontStyle.italic)),
                          const SizedBox(height: 8),
                          const Text('- Konsisten adalah kunci sukses.', style: TextStyle(fontSize: 9, color: Colors.grey)),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
            
            const SizedBox(height: 16),

            // Akses Cepat
            Container(
              padding: const EdgeInsets.all(16),
              color: Colors.white,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Akses Cepat', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                  const SizedBox(height: 12),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceAround,
                    children: [
                      _buildFastAccessIcon(Icons.qr_code, 'Scan QR', Colors.green, () => Navigator.push(context, MaterialPageRoute(builder: (_) => const QrScanScreen()))),
                      _buildFastAccessIcon(Icons.assignment_late, 'Izin', Colors.orange, () => Navigator.push(context, MaterialPageRoute(builder: (_) => const LeaveRequestScreen()))),
                      _buildFastAccessIcon(Icons.download, 'Download Raport', Colors.blue, () => _showDownloadRaportDialog(context)),
                      _buildFastAccessIcon(Icons.chat, 'Chat Admin', Colors.purple, () async {
                        final Uri url = Uri.parse('https://wa.me/1234567890');
                        if (await canLaunchUrl(url)) {
                          await launchUrl(url);
                        } else {
                          ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Tidak dapat membuka WhatsApp')));
                        }
                      }),
                      _buildFastAccessIcon(Icons.help_outline, 'Panduan', Colors.blueGrey, () => ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Membuka Panduan...')))),
                    ],
                  )
                ],
              ),
            ),

            const SizedBox(height: 24),
          ],
        ),
      ),
    );
  }

  Widget _buildMiniSchedule(String time, String subject) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(time, style: const TextStyle(fontSize: 10, color: Colors.grey)),
          const SizedBox(width: 8),
          Expanded(child: Text(subject, style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold), maxLines: 1, overflow: TextOverflow.ellipsis)),
        ],
      ),
    );
  }

  Widget _buildSummaryBox(String title, String value, Color color) {
    return Container(
      padding: const EdgeInsets.all(6),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: TextStyle(fontSize: 9, color: color)),
          Text(value, style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: color)),
        ],
      ),
    );
  }

  Widget _buildScrollableCard(String title, String actionText, VoidCallback onActionTap, List<Widget> children) {
    return Container(
      width: 250,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 4, offset: const Offset(0, 2))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              GestureDetector(
                onTap: onActionTap,
                child: Text(actionText, style: const TextStyle(color: AppTheme.primaryColor, fontSize: 10)),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Expanded(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: children,
            ),
          )
        ],
      ),
    );
  }

  Widget _buildProgressRow(String title, double progress, Color color) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(title, style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w500)),
            Text('${(progress * 100).toInt()}%', style: const TextStyle(fontSize: 10, color: Colors.grey)),
          ],
        ),
        const SizedBox(height: 4),
        LinearProgressIndicator(
          value: progress,
          backgroundColor: Colors.grey.shade200,
          color: color,
          minHeight: 4,
          borderRadius: BorderRadius.circular(4),
        )
      ],
    );
  }

  Widget _buildCalendarRow(String date, String title, String subtitle) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
          decoration: BoxDecoration(color: Colors.blue.shade50, borderRadius: BorderRadius.circular(8)),
          child: Text(date, style: const TextStyle(color: Colors.blue, fontWeight: FontWeight.bold, fontSize: 11), textAlign: TextAlign.center),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 11)),
              Text(subtitle, style: const TextStyle(fontSize: 10, color: Colors.grey)),
            ],
          ),
        )
      ],
    );
  }

  Widget _buildAnnouncementRow(String title, String time) {
    return Row(
      children: [
        Container(width: 6, height: 6, decoration: const BoxDecoration(color: Colors.blue, shape: BoxShape.circle)),
        const SizedBox(width: 8),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 11)),
              Text(time, style: const TextStyle(fontSize: 10, color: Colors.grey)),
            ],
          ),
        )
      ],
    );
  }

  Widget _buildMenuIcon(IconData icon, String label, Color color, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: SizedBox(
        width: 60,
        child: Column(
          children: [
            Container(
              width: 50,
              height: 50,
              decoration: BoxDecoration(
                color: color.withOpacity(0.1),
                borderRadius: BorderRadius.circular(14),
              ),
              child: Icon(icon, color: color, size: 24),
            ),
            const SizedBox(height: 6),
            Text(
              label,
              style: const TextStyle(fontWeight: FontWeight.w500, fontSize: 10, color: Color(0xFF334155)),
              textAlign: TextAlign.center,
              maxLines: 2,
            ),
          ],
        ),
      ),
    );
  }
  
  Widget _buildFastAccessIcon(IconData icon, String label, Color color, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Row(
        children: [
          Icon(icon, color: color, size: 18),
          const SizedBox(width: 4),
          Text(label, style: const TextStyle(fontSize: 10, color: Colors.black87)),
        ],
      ),
    );
  }

  void _showTeachersBottomSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (context) => Container(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Hubungi Guru', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            ListTile(
              leading: const CircleAvatar(child: Text('A')),
              title: const Text('Bu Anita Sari'),
              subtitle: const Text('Wali Kelas'),
              trailing: const Icon(Icons.chat, color: Colors.green),
              onTap: () async {
                final Uri url = Uri.parse('https://wa.me/1234567890');
                if (await canLaunchUrl(url)) await launchUrl(url);
              },
            ),
            ListTile(
              leading: const CircleAvatar(child: Text('B')),
              title: const Text('Pak Budi'),
              subtitle: const Text('Guru Matematika'),
              trailing: const Icon(Icons.chat, color: Colors.green),
              onTap: () async {
                final Uri url = Uri.parse('https://wa.me/1234567890');
                if (await canLaunchUrl(url)) await launchUrl(url);
              },
            )
          ],
        ),
      ),
    );
  }

  void _showDownloadRaportDialog(BuildContext context) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) {
        Future.delayed(const Duration(seconds: 2), () {
          if (mounted) {
            Navigator.pop(context);
            ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Raport berhasil diunduh ke folder Download')));
          }
        });
        return const AlertDialog(
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              CircularProgressIndicator(),
              SizedBox(height: 16),
              Text('Mengunduh Raport...'),
            ],
          ),
        );
      }
    );
  }

  @override
  Widget build(BuildContext context) {
    final List<Widget> tabs = [
      _buildHomeTab(context),
      const ScheduleScreen(),
      const ProfileScreen(),
    ];

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      body: SafeArea(child: tabs[_currentIndex]),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        onTap: (index) => setState(() => _currentIndex = index),
        selectedItemColor: AppTheme.primaryColor,
        unselectedItemColor: Colors.grey,
        backgroundColor: Colors.white,
        type: BottomNavigationBarType.fixed,
        elevation: 8,
        items: const [
          BottomNavigationBarItem(
              icon: Icon(Icons.home_outlined),
              activeIcon: Icon(Icons.home),
              label: 'Beranda'),
          BottomNavigationBarItem(
              icon: Icon(Icons.calendar_today_outlined),
              activeIcon: Icon(Icons.calendar_today),
              label: 'Jadwal'),
          BottomNavigationBarItem(
              icon: Icon(Icons.person_outline),
              activeIcon: Icon(Icons.person),
              label: 'Profil'),
        ],
      ),
    );
  }
}
