import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:image_picker/image_picker.dart';
import '../providers/auth_provider.dart';
import '../theme/app_theme.dart';
import 'login_screen.dart';
import 'grade_screen.dart';
import 'edit_profile_screen.dart';
import 'change_password_screen.dart';
import 'settings_screen.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({Key? key}) : super(key: key);

  @override
  _ProfileScreenState createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  File? _imageFile;

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      setState(() {
        _imageFile = File(pickedFile.path);
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Foto profil berhasil diperbarui')));
      }
    }
  }

  void _handleLogout(BuildContext context) async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    await authProvider.logout();
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => const LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {
    final user = Provider.of<AuthProvider>(context).user;
    final userName = user?['student_name'] ?? 'Siswa';
    final nis = user?['nis'] ?? '-';
    final phone = user?['phone_number'] ?? '-';
    final classroom = user?['classroom']?['name'] ?? '-';

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            children: [
              // Header Profil
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: AppTheme.primaryColor,
                  borderRadius: const BorderRadius.only(
                    bottomLeft: Radius.circular(24),
                    bottomRight: Radius.circular(24),
                  ),
                ),
                child: Column(
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text('Profil Saya', style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                        IconButton(
                          icon: const Icon(Icons.settings, color: Colors.white), 
                          onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const SettingsScreen()))
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    Row(
                      children: [
                        Stack(
                          children: [
                            Container(
                              width: 80,
                              height: 80,
                              decoration: BoxDecoration(
                                color: Colors.white.withOpacity(0.2),
                                shape: BoxShape.circle,
                                border: Border.all(color: Colors.white, width: 2),
                              ),
                              child: _imageFile != null
                                  ? ClipRRect(
                                      borderRadius: BorderRadius.circular(40),
                                      child: Image.file(_imageFile!, width: 80, height: 80, fit: BoxFit.cover),
                                    )
                                  : Center(
                                      child: Text(
                                        userName.isNotEmpty ? userName[0].toUpperCase() : 'S',
                                        style: const TextStyle(fontSize: 32, color: Colors.white, fontWeight: FontWeight.bold),
                                      ),
                                    ),
                            ),
                            Positioned(
                              bottom: 0,
                              right: 0,
                              child: GestureDetector(
                                onTap: _pickImage,
                                child: Container(
                                  padding: const EdgeInsets.all(4),
                                  decoration: const BoxDecoration(color: Colors.blue, shape: BoxShape.circle),
                                  child: const Icon(Icons.camera_alt, color: Colors.white, size: 14),
                                ),
                              ),
                            )
                          ],
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                children: [
                                  Expanded(child: Text(userName, style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold))),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                    decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), borderRadius: BorderRadius.circular(10)),
                                    child: const Text('Siswa', style: TextStyle(color: Colors.white, fontSize: 10)),
                                  )
                                ],
                              ),
                              const SizedBox(height: 4),
                              Text('$classroom • NIS $nis', style: const TextStyle(color: Colors.white70, fontSize: 12)),
                              const SizedBox(height: 4),
                              Row(
                                children: [
                                  const Icon(Icons.school, color: Colors.white70, size: 14),
                                  const SizedBox(width: 4),
                                  const Text('SMA Negeri 1 Kendari', style: TextStyle(color: Colors.white70, fontSize: 12)),
                                ],
                              )
                            ],
                          ),
                        )
                      ],
                    ),
                    const SizedBox(height: 20),
                    // Kehadiran Card Inside Header
                    Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(16),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                children: [
                                  const Text('Kehadiran', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                                  const SizedBox(width: 8),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                    decoration: BoxDecoration(color: Colors.green.withOpacity(0.1), borderRadius: BorderRadius.circular(8)),
                                    child: const Text('Baik', style: TextStyle(color: Colors.green, fontSize: 10)),
                                  )
                                ],
                              ),
                              const SizedBox(height: 8),
                              const Text('95%', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.green)),
                              const Text('Hadir 171 / 180 hari', style: TextStyle(fontSize: 10, color: Colors.grey)),
                            ],
                          ),
                          Icon(Icons.show_chart, color: Colors.green.shade300, size: 40)
                        ],
                      ),
                    )
                  ],
                ),
              ),

              Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    // Informasi Pribadi
                    _buildSectionCard('Informasi Pribadi', 'Edit Profil', () {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => const EditProfileScreen()));
                    }, [
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Expanded(
                            child: Column(
                              children: [
                                _buildInfoRow(Icons.badge_outlined, 'NIS', nis),
                                _buildInfoRow(Icons.calendar_today_outlined, 'Tanggal Lahir', '15 Februari 2008'),
                                _buildInfoRow(Icons.person_outline, 'Jenis Kelamin', 'Laki-laki'),
                                _buildInfoRow(Icons.bloodtype_outlined, 'Golongan Darah', 'O'),
                              ],
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: Column(
                              children: [
                                _buildInfoRow(Icons.home_outlined, 'Alamat', 'Jl. Pendidikan No. 10'),
                                _buildInfoRow(Icons.phone_outlined, 'No. Telepon', phone),
                                _buildInfoRow(Icons.email_outlined, 'Email', 'student@mail.com'),
                                _buildInfoRow(Icons.mosque_outlined, 'Agama', 'Islam'),
                              ],
                            ),
                          )
                        ],
                      ),
                      const SizedBox(height: 16),
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(color: Colors.blue.shade50, borderRadius: BorderRadius.circular(12)),
                        child: Row(
                          children: [
                            const Icon(Icons.verified_user, color: Colors.blue, size: 30),
                            const SizedBox(width: 12),
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                const Text('Status Akun', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                                const Text('Aktif', style: TextStyle(color: Colors.green, fontSize: 12, fontWeight: FontWeight.bold)),
                                const SizedBox(height: 4),
                                const Text('Bergabung Sejak 1 Januari 2025', style: TextStyle(fontSize: 10, color: Colors.grey)),
                              ],
                            )
                          ],
                        ),
                      )
                    ]),
                    
                    const SizedBox(height: 16),

                    // Ringkasan Akademik
                    _buildSectionCard('Ringkasan Akademik', null, null, [
                      Row(
                        children: [
                          Expanded(child: _buildAcademicStatBox('Rata-rata Nilai', '89.25', 'Sangat Baik', Colors.blue, Icons.star_border)),
                          const SizedBox(width: 8),
                          Expanded(child: _buildAcademicStatBox('Peringkat Kelas', '5 / 36', 'Top 14%', Colors.green, Icons.emoji_events_outlined)),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          Expanded(child: _buildAcademicStatBox('Total Tugas', '24', '18 Selesai', Colors.purple, Icons.assignment_outlined)),
                          const SizedBox(width: 8),
                          Expanded(child: _buildAcademicStatBox('Mata Pelajaran', '12', 'Aktif', Colors.orange, Icons.menu_book_outlined)),
                        ],
                      ),
                      const SizedBox(height: 12),
                      GestureDetector(
                        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const GradeScreen())),
                        child: Container(
                          padding: const EdgeInsets.symmetric(vertical: 12),
                          decoration: BoxDecoration(border: Border(top: BorderSide(color: Colors.grey.shade200))),
                          child: const Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Row(
                                children: [
                                  Icon(Icons.bar_chart, color: Colors.blue, size: 16),
                                  SizedBox(width: 8),
                                  Text('Lihat Detail Nilai', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
                                ],
                              ),
                              Icon(Icons.chevron_right, color: Colors.grey, size: 16)
                            ],
                          ),
                        ),
                      )
                    ]),

                    const SizedBox(height: 16),

                    // Informasi Sekolah & Orang Tua
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Expanded(
                          child: _buildSectionCard('Informasi Sekolah', null, null, [
                            _buildInfoRow(Icons.class_outlined, 'Kelas', classroom),
                            _buildInfoRow(Icons.person_outline, 'Wali Kelas', 'Bu Anita Sari'),
                            _buildInfoRow(Icons.calendar_month, 'Tahun Ajaran', '2026/2027'),
                            _buildInfoRow(Icons.science_outlined, 'Jurusan', 'IPA'),
                          ]),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: _buildSectionCard('Orang Tua / Wali', 'Edit', () {
                            Navigator.push(context, MaterialPageRoute(builder: (_) => const EditProfileScreen()));
                          }, [
                            _buildInfoRow(Icons.person, 'Nama Ayah', 'Bapak Ahmad'),
                            _buildInfoRow(Icons.person, 'Nama Ibu', 'Ibu Siti'),
                            _buildInfoRow(Icons.work_outline, 'Pekerjaan', 'Wiraswasta'),
                            _buildInfoRow(Icons.phone, 'No. HP', '0812-xxxx'),
                          ]),
                        )
                      ],
                    ),

                    const SizedBox(height: 16),

                    // Pengaturan & Lainnya
                    _buildSectionCard('Pengaturan & Lainnya', null, null, [
                      _buildSettingsRow(Icons.lock_outline, 'Ubah Password', null, () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ChangePasswordScreen()))),
                      _buildSettingsRow(Icons.notifications_none, 'Notifikasi', null, () => Navigator.push(context, MaterialPageRoute(builder: (_) => const SettingsScreen()))),
                      _buildSettingsRow(Icons.security, 'Keamanan Akun', null, () => Navigator.push(context, MaterialPageRoute(builder: (_) => const SettingsScreen()))),
                      _buildSettingsRow(Icons.language, 'Bahasa', 'Bahasa Indonesia', () => Navigator.push(context, MaterialPageRoute(builder: (_) => const SettingsScreen()))),
                      _buildSettingsRow(Icons.info_outline, 'Tentang Aplikasi', 'Versi 1.0.0', () => Navigator.push(context, MaterialPageRoute(builder: (_) => const SettingsScreen()))),
                    ]),

                    const SizedBox(height: 24),

                    // Tombol Logout
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        onPressed: () => _handleLogout(context),
                        icon: const Icon(Icons.logout),
                        label: const Text('Keluar dari Akun', style: TextStyle(fontSize: 14)),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.red.shade50,
                          foregroundColor: Colors.red,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          elevation: 0,
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        ),
                      ),
                    ),
                    const SizedBox(height: 24),
                  ],
                ),
              )
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildSectionCard(String title, String? actionText, VoidCallback? onActionTap, List<Widget> children) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 4, offset: const Offset(0, 2))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              if (actionText != null)
                GestureDetector(
                  onTap: onActionTap,
                  child: Text(actionText, style: const TextStyle(color: Colors.blue, fontSize: 11, fontWeight: FontWeight.bold)),
                )
            ],
          ),
          const SizedBox(height: 12),
          ...children
        ],
      ),
    );
  }

  Widget _buildInfoRow(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(6),
            decoration: BoxDecoration(color: Colors.blue.withOpacity(0.1), shape: BoxShape.circle),
            child: Icon(icon, size: 14, color: Colors.blue),
          ),
          const SizedBox(width: 8),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label, style: const TextStyle(fontSize: 10, color: Colors.grey)),
                Text(value, style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w500)),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _buildAcademicStatBox(String title, String mainValue, String subValue, Color color, IconData icon) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        border: Border.all(color: Colors.grey.shade200),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: const TextStyle(fontSize: 10, color: Colors.blue, fontWeight: FontWeight.bold)),
          const SizedBox(height: 4),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(mainValue, style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: color)),
                  Text(subValue, style: const TextStyle(fontSize: 9, color: Colors.grey)),
                ],
              ),
              Icon(icon, color: color, size: 20)
            ],
          )
        ],
      ),
    );
  }

  Widget _buildSettingsRow(IconData icon, String label, String? trailingText, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      child: Padding(
        padding: const EdgeInsets.only(bottom: 16, top: 4),
        child: Row(
          children: [
            Icon(icon, size: 18, color: Colors.grey.shade700),
            const SizedBox(width: 12),
            Expanded(child: Text(label, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w500))),
            if (trailingText != null) Text(trailingText, style: const TextStyle(fontSize: 10, color: Colors.grey)),
            const SizedBox(width: 8),
            const Icon(Icons.chevron_right, size: 16, color: Colors.grey)
          ],
        ),
      ),
    );
  }
}
