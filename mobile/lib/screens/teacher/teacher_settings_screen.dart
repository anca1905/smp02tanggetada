import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../providers/auth_provider.dart';
import '../../providers/teacher_provider.dart';
import '../change_password_screen.dart';
import '../login_screen.dart';
import 'teacher_sync_screen.dart';

class TeacherSettingsScreen extends StatefulWidget {
  const TeacherSettingsScreen({super.key});

  @override
  State<TeacherSettingsScreen> createState() => _TeacherSettingsScreenState();
}

class _TeacherSettingsScreenState extends State<TeacherSettingsScreen> {
  bool _autoSync = true;
  bool _apelReminder = true;
  bool _soundNotification = true;

  @override
  void initState() {
    super.initState();
    _loadPreferences();
  }

  Future<void> _loadPreferences() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _autoSync = prefs.getBool('teacher_setting_auto_sync') ?? true;
      _apelReminder = prefs.getBool('teacher_setting_apel_reminder') ?? true;
      _soundNotification = prefs.getBool('teacher_setting_sound') ?? true;
    });
  }

  Future<void> _updatePref(String key, bool val, void Function(bool) updater) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool(key, val);
    setState(() {
      updater(val);
    });
  }

  Future<void> _clearCache() async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Bersihkan Cache Lokal?'),
        content: const Text(
          'Tindakan ini akan mengosongkan data cache kelas dan siswa offline. Data antrean yang belum terkirim tetap dipertahankan.',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx, false),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF1E40AF)),
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Bersihkan'),
          ),
        ],
      ),
    );

    if (confirm == true && mounted) {
      final teacher = Provider.of<TeacherProvider>(context, listen: false);
      await teacher.loadClassesAndSubjects();
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Cache offline berhasil diperbarui dari server.'),
            backgroundColor: Color(0xFF10B981),
          ),
        );
      }
    }
  }

  void _showAbout() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(6),
              decoration: BoxDecoration(
                color: const Color(0xFF1E40AF),
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Icon(Icons.school, color: Colors.white, size: 24),
            ),
            const SizedBox(width: 12),
            const Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'SIMS Terpadu',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
                Text(
                  'Portal Presensi Guru',
                  style: TextStyle(fontSize: 12, color: Colors.grey),
                ),
              ],
            ),
          ],
        ),
        content: const Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Versi: 1.0.0 (Build 2026.10)', style: TextStyle(fontWeight: FontWeight.w600)),
            SizedBox(height: 6),
            Text('Instansi: SMP Negeri 2 Tanggetada'),
            SizedBox(height: 12),
            Text(
              'Aplikasi manajemen absensi dan kehadiran siswa terpadu. Dilengkapi teknologi penyimpanan lokal offline dan sinkronisasi otomatis.',
              style: TextStyle(fontSize: 13, color: Color(0xFF475569)),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Tutup'),
          ),
        ],
      ),
    );
  }

  Future<void> _logout() async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Keluar dari Akun?'),
        content: const Text('Anda harus login kembali untuk dapat menginput dan menyinkronkan data presensi.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx, false),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Keluar'),
          ),
        ],
      ),
    );

    if (confirm == true && mounted) {
      final auth = Provider.of<AuthProvider>(context, listen: false);
      await auth.logout();
      if (mounted) {
        Navigator.of(context).pushAndRemoveUntil(
          MaterialPageRoute(builder: (_) => const LoginScreen()),
          (route) => false,
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<AuthProvider>(context);
    final teacher = Provider.of<TeacherProvider>(context);
    final user = auth.user ?? {};

    final teacherName = user['name'] ?? 'Bapak/Ibu Guru';
    final nip = user['employee_id'] ?? '-';
    final homeroomClass = user['homeroom_class'] ?? user['classroom']?['name'] ?? 'Guru Mata Pelajaran';

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        backgroundColor: const Color(0xFF0F172A),
        elevation: 0,
        title: const Text(
          'Pengaturan',
          style: TextStyle(
            color: Colors.white,
            fontSize: 17,
            fontWeight: FontWeight.bold,
          ),
        ),
      ),
      body: ListView(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 20),
        children: [
          // Teacher Info Card
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [Color(0xFF1E3A8A), Color(0xFF2563EB)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.circular(16),
              boxShadow: [
                BoxShadow(
                  color: const Color(0xFF1E3A8A).withOpacity(0.25),
                  blurRadius: 10,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            child: Row(
              children: [
                CircleAvatar(
                  radius: 30,
                  backgroundColor: Colors.white.withOpacity(0.2),
                  child: const Icon(Icons.person, color: Colors.white, size: 36),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        teacherName,
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        homeroomClass,
                        style: TextStyle(
                          color: Colors.white.withOpacity(0.9),
                          fontSize: 13,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        'NIP: $nip',
                        style: TextStyle(
                          color: Colors.blue.shade100,
                          fontSize: 12,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          const SizedBox(height: 24),

          // Section 1: Presensi & Offline Sync
          _buildSectionTitle('Presensi & Sinkronisasi Data'),
          _buildCard(
            children: [
              SwitchListTile(
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 2),
                title: const Text(
                  'Sinkronisasi Otomatis Saat Online',
                  style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: Color(0xFF1E293B)),
                ),
                subtitle: const Text(
                  'Kirim antrean offline secara berkala saat terhubung Wi-Fi / Data',
                  style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                ),
                value: _autoSync,
                activeColor: const Color(0xFF2563EB),
                onChanged: (val) => _updatePref('teacher_setting_auto_sync', val, (v) => _autoSync = v),
              ),
              const Divider(height: 1, indent: 16, endIndent: 16, color: Color(0xFFF1F5F9)),
              ListTile(
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 2),
                leading: Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: const Color(0xFFEFF6FF),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(Icons.cloud_sync, color: Color(0xFF2563EB), size: 20),
                ),
                title: const Text(
                  'Pengelola Sinkronisasi',
                  style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: Color(0xFF1E293B)),
                ),
                subtitle: Text(
                  teacher.queueCount > 0 ? '${teacher.queueCount} sesi antrean pending' : 'Semua data tersinkron',
                  style: TextStyle(
                    fontSize: 12,
                    color: teacher.queueCount > 0 ? Colors.amber.shade700 : const Color(0xFF10B981),
                    fontWeight: FontWeight.w500,
                  ),
                ),
                trailing: const Icon(Icons.chevron_right, color: Color(0xFF94A3B8)),
                onTap: () {
                  Navigator.of(context).push(
                    MaterialPageRoute(builder: (_) => const TeacherSyncScreen()),
                  );
                },
              ),
              const Divider(height: 1, indent: 16, endIndent: 16, color: Color(0xFFF1F5F9)),
              ListTile(
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 2),
                leading: Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: const Color(0xFFFEF2F2),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(Icons.cached, color: Color(0xFFDC2626), size: 20),
                ),
                title: const Text(
                  'Perbarui Cache Kelas & Siswa',
                  style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: Color(0xFF1E293B)),
                ),
                subtitle: const Text(
                  'Muat ulang daftar siswa dan kelas terbaru dari server',
                  style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                ),
                trailing: const Icon(Icons.chevron_right, color: Color(0xFF94A3B8)),
                onTap: _clearCache,
              ),
            ],
          ),

          const SizedBox(height: 20),

          // Section 2: Notifikasi & Pengingat
          _buildSectionTitle('Notifikasi & Pengingat'),
          _buildCard(
            children: [
              SwitchListTile(
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 2),
                title: const Text(
                  'Pengingat Apel Pagi (06:45 WITA)',
                  style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: Color(0xFF1E293B)),
                ),
                subtitle: const Text(
                  'Munculkan pengingat untuk mengisi kehadiran apel pagi',
                  style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                ),
                value: _apelReminder,
                activeColor: const Color(0xFF2563EB),
                onChanged: (val) => _updatePref('teacher_setting_apel_reminder', val, (v) => _apelReminder = v),
              ),
              const Divider(height: 1, indent: 16, endIndent: 16, color: Color(0xFFF1F5F9)),
              SwitchListTile(
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 2),
                title: const Text(
                  'Suara & Getar Notifikasi',
                  style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: Color(0xFF1E293B)),
                ),
                subtitle: const Text(
                  'Bunyikan pemberitahuan saat ada pengumuman baru',
                  style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                ),
                value: _soundNotification,
                activeColor: const Color(0xFF2563EB),
                onChanged: (val) => _updatePref('teacher_setting_sound', val, (v) => _soundNotification = v),
              ),
            ],
          ),

          const SizedBox(height: 20),

          // Section 3: Akun & Keamanan
          _buildSectionTitle('Akun & Keamanan'),
          _buildCard(
            children: [
              ListTile(
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 2),
                leading: Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: const Color(0xFFEFF6FF),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(Icons.lock_outline, color: Color(0xFF2563EB), size: 20),
                ),
                title: const Text(
                  'Ubah Kata Sandi',
                  style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: Color(0xFF1E293B)),
                ),
                subtitle: const Text(
                  'Perbarui kata sandi akun guru Anda',
                  style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                ),
                trailing: const Icon(Icons.chevron_right, color: Color(0xFF94A3B8)),
                onTap: () {
                  Navigator.of(context).push(
                    MaterialPageRoute(builder: (_) => const ChangePasswordScreen()),
                  );
                },
              ),
            ],
          ),

          const SizedBox(height: 20),

          // Section 4: Informasi & Bantuan
          _buildSectionTitle('Informasi'),
          _buildCard(
            children: [
              ListTile(
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 2),
                leading: Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: const Color(0xFFF1F5F9),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(Icons.info_outline, color: Color(0xFF475569), size: 20),
                ),
                title: const Text(
                  'Tentang Aplikasi',
                  style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: Color(0xFF1E293B)),
                ),
                subtitle: const Text(
                  'SIMS Terpadu Guru v1.0.0 - SMPN 2 Tanggetada',
                  style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                ),
                trailing: const Icon(Icons.chevron_right, color: Color(0xFF94A3B8)),
                onTap: _showAbout,
              ),
            ],
          ),

          const SizedBox(height: 28),

          // Logout Button
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: _logout,
              icon: const Icon(Icons.logout, size: 20),
              label: const Text(
                'Keluar dari Akun',
                style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFFDC2626),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                elevation: 0,
              ),
            ),
          ),
          const SizedBox(height: 20),
        ],
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.only(left: 4, bottom: 8),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 13,
          fontWeight: FontWeight.bold,
          color: Color(0xFF64748B),
          letterSpacing: 0.3,
        ),
      ),
    );
  }

  Widget _buildCard({required List<Widget> children}) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFFE2E8F0)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 4,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        children: children,
      ),
    );
  }
}
