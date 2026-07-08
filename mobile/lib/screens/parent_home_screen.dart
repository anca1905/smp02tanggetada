import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../theme/app_theme.dart';
import '../widgets/sparkline_chart.dart';
import '../widgets/activity_timeline.dart';

class ParentHomeScreen extends StatelessWidget {
  const ParentHomeScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final user = Provider.of<AuthProvider>(context).user;
    final parentName = user?['parent_name'] ?? 'Ibu Siti Aminah';
    final studentName = user?['student_name'] ?? 'Muhammad Arsyad';
    final nis = user?['nis'] ?? '202501001';
    final classroom = user?['classroom']?['name'] ?? 'XI IPA 1';

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header & Child Selector
              _buildHeader(context, parentName, studentName, nis, classroom),
              
              const SizedBox(height: 16),
              
              // Stats Row
              SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 16),
                physics: const BouncingScrollPhysics(),
                child: Row(
                  children: [
                    _buildStatCard('95%', 'Kehadiran\nBulan Ini', Colors.green, [4, 5, 4, 6, 8, 7, 9], Icons.check_circle),
                    _buildStatCard('89', 'Rata-rata Nilai\nSemester Ini', Colors.blue, [7, 7.5, 8, 8.5, 8.2, 8.9, 8.9], Icons.menu_book),
                    _buildStatCard('2', 'Tugas Belum\nSelesai', Colors.orange, [5, 4, 3, 4, 2, 3, 2], Icons.assignment),
                    _buildStatCard('Rp350.000', 'Tagihan\nJuli 2026', Colors.purple, [2, 3, 2, 4, 5, 4, 6], Icons.account_balance_wallet),
                  ],
                ),
              ),

              const SizedBox(height: 24),

              // Main Menu Grid
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: GridView.count(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  crossAxisCount: 4,
                  mainAxisSpacing: 20,
                  crossAxisSpacing: 10,
                  children: [
                    _buildMenuIcon(Icons.calendar_today, 'Jadwal', Colors.blue),
                    _buildMenuIcon(Icons.bar_chart, 'Nilai', Colors.green),
                    _buildMenuIcon(Icons.person_pin, 'Presensi', Colors.teal),
                    _buildMenuIcon(Icons.assignment, 'Tugas', Colors.orange),
                    _buildMenuIcon(Icons.description, 'Raport', Colors.purple),
                    _buildMenuIcon(Icons.account_balance_wallet, 'Pembayaran', Colors.redAccent),
                    _buildMenuIcon(Icons.campaign, 'Pengumuman', Colors.red),
                    _buildMenuIcon(Icons.chat_bubble, 'Chat Guru', Colors.blueAccent),
                  ],
                ),
              ),

              const SizedBox(height: 24),

              // Jadwal & Presensi Section
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(child: _buildJadwalHariIni()),
                    const SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        children: [
                          _buildPresensiHariIni(),
                          const SizedBox(height: 16),
                          _buildNilaiTerbaru(),
                        ],
                      ),
                    )
                  ],
                ),
              ),

              const SizedBox(height: 16),

              // Pengumuman & Tagihan Section
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(child: _buildPengumumanTerbaru()),
                    const SizedBox(width: 16),
                    Expanded(child: _buildTagihanAktif(context)),
                  ],
                ),
              ),

              const SizedBox(height: 16),

              // Timeline Aktivitas
              Padding(
                padding: const EdgeInsets.all(16),
                child: Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text('Timeline Aktivitas Hari Ini', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                          Text('Lihat Semua', style: TextStyle(color: Colors.blue, fontSize: 12, fontWeight: FontWeight.bold)),
                        ],
                      ),
                      const SizedBox(height: 20),
                      ActivityTimeline(
                        items: [
                          ActivityTimelineItem(time: '07.05', title: 'Presensi\nMasuk', subtitle: '', icon: Icons.check_circle, color: Colors.green),
                          ActivityTimelineItem(time: '08.00', title: 'Mengikuti\nMatematika', subtitle: '', icon: Icons.menu_book, color: Colors.blue),
                          ActivityTimelineItem(time: '10.15', title: 'Tugas Kimia\ndiberikan', subtitle: '', icon: Icons.assignment, color: Colors.orange),
                          ActivityTimelineItem(time: '11.30', title: 'Nilai Fisika\ndiperbarui', subtitle: '', icon: Icons.star, color: Colors.purple),
                          ActivityTimelineItem(time: '13.00', title: 'Pengumuman\nbaru', subtitle: '', icon: Icons.campaign, color: Colors.red),
                          ActivityTimelineItem(time: '15.08', title: 'Presensi\nPulang', subtitle: '', icon: Icons.check_circle, color: Colors.green),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 24),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context, String parentName, String studentName, String nis, String classroom) {
    return Stack(
      clipBehavior: Clip.none,
      children: [
        Container(
          padding: const EdgeInsets.only(left: 20, right: 20, top: 20, bottom: 60),
          decoration: const BoxDecoration(
            color: Color(0xFF3B82F6),
            borderRadius: BorderRadius.only(bottomLeft: Radius.circular(24), bottomRight: Radius.circular(24)),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  const CircleAvatar(
                    radius: 24,
                    backgroundImage: NetworkImage('https://i.pravatar.cc/150?img=5'), // Dummy avatar
                  ),
                  const SizedBox(width: 12),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Halo,', style: TextStyle(color: Colors.white70, fontSize: 12)),
                      Text(parentName, style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                      const Text('Orang Tua / Wali', style: TextStyle(color: Colors.white70, fontSize: 12)),
                    ],
                  )
                ],
              ),
              Row(
                children: [
                  _buildIconBadge(Icons.notifications_none, '5'),
                  const SizedBox(width: 12),
                  _buildIconBadge(Icons.chat_bubble_outline, '3'),
                ],
              )
            ],
          ),
        ),
        // Child Selector Card (Overlap)
        Positioned(
          bottom: -30,
          left: 20,
          right: 20,
          child: Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, 5))],
            ),
            child: Row(
              children: [
                const CircleAvatar(
                  radius: 24,
                  backgroundImage: NetworkImage('https://i.pravatar.cc/150?img=11'), // Dummy student avatar
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(studentName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                      Text('$classroom • NIS $nis', style: const TextStyle(color: Colors.grey, fontSize: 12)),
                      const SizedBox(height: 4),
                      Row(
                        children: [
                          const Icon(Icons.check_circle, color: Colors.green, size: 12),
                          const SizedBox(width: 4),
                          const Text('Hadir Hari Ini', style: TextStyle(color: Colors.green, fontSize: 10, fontWeight: FontWeight.bold)),
                        ],
                      )
                    ],
                  ),
                ),
                const Icon(Icons.keyboard_arrow_down, color: Colors.grey),
              ],
            ),
          ),
        )
      ],
    );
  }

  Widget _buildIconBadge(IconData icon, String count) {
    return Stack(
      clipBehavior: Clip.none,
      children: [
        Icon(icon, color: Colors.white, size: 28),
        Positioned(
          right: -4,
          top: -4,
          child: Container(
            padding: const EdgeInsets.all(4),
            decoration: const BoxDecoration(color: Colors.red, shape: BoxShape.circle),
            child: Text(count, style: const TextStyle(color: Colors.white, fontSize: 8, fontWeight: FontWeight.bold)),
          ),
        )
      ],
    );
  }

  Widget _buildStatCard(String mainValue, String label, Color color, List<double> chartData, IconData icon) {
    return Container(
      width: 130,
      margin: const EdgeInsets.only(right: 12, top: 40), // Top margin to account for overlap
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(6),
            decoration: BoxDecoration(color: color.withOpacity(0.1), shape: BoxShape.circle),
            child: Icon(icon, color: color, size: 16),
          ),
          const SizedBox(height: 12),
          Text(mainValue, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 22)),
          const SizedBox(height: 4),
          Text(label, style: const TextStyle(fontSize: 10, color: Colors.grey)),
          const SizedBox(height: 12),
          SparklineChart(data: chartData, color: color),
        ],
      ),
    );
  }

  Widget _buildMenuIcon(IconData icon, String label, Color color) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: color.withOpacity(0.1),
            borderRadius: BorderRadius.circular(16),
          ),
          child: Icon(icon, color: color, size: 24),
        ),
        const SizedBox(height: 8),
        Text(label, style: const TextStyle(fontSize: 10, color: Colors.black87), textAlign: TextAlign.center),
      ],
    );
  }

  Widget _buildJadwalHariIni() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Jadwal Hari Ini', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              Text('Lihat Semua', style: TextStyle(color: Colors.blue, fontSize: 10, fontWeight: FontWeight.bold)),
            ],
          ),
          const SizedBox(height: 16),
          _buildJadwalItem('08.00 - 09.30', 'Matematika', 'Bu Anita S.', Icons.calculate, Colors.blue),
          _buildJadwalItem('09.40 - 11.10', 'Bahasa Indonesia', 'Bu Siti A.', Icons.menu_book, Colors.green),
          _buildJadwalItem('11.20 - 12.50', 'Kimia', 'Bu Lestari', Icons.science, Colors.orange),
          _buildJadwalItem('13.00 - 14.30', 'Informatika', 'Pak Dwi P.', Icons.computer, Colors.indigo, isLast: true),
          const SizedBox(height: 12),
          Center(
            child: Text('Lihat Semua Jadwal >', style: TextStyle(color: Colors.blue.shade700, fontSize: 12, fontWeight: FontWeight.bold)),
          )
        ],
      ),
    );
  }

  Widget _buildJadwalItem(String time, String subject, String teacher, IconData icon, Color color, {bool isLast = false}) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Column(
          children: [
            Container(
              width: 10,
              height: 10,
              decoration: BoxDecoration(shape: BoxShape.circle, border: Border.all(color: color, width: 2), color: Colors.white),
            ),
            if (!isLast)
              Container(width: 2, height: 45, color: Colors.grey.shade200),
          ],
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Padding(
            padding: const EdgeInsets.only(bottom: 16),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(color: color.withOpacity(0.1), borderRadius: BorderRadius.circular(8)),
                  child: Icon(icon, color: color, size: 16),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(time, style: const TextStyle(fontSize: 10, color: Colors.grey)),
                      Text(subject, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                      Text(teacher, style: const TextStyle(fontSize: 10, color: Colors.grey)),
                    ],
                  ),
                )
              ],
            ),
          ),
        )
      ],
    );
  }

  Widget _buildPresensiHariIni() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Presensi Hari Ini', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              Text('Lihat Detail', style: TextStyle(color: Colors.blue, fontSize: 10, fontWeight: FontWeight.bold)),
            ],
          ),
          const SizedBox(height: 12),
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade200), borderRadius: BorderRadius.circular(12)),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(6),
                  decoration: BoxDecoration(color: Colors.green.withOpacity(0.1), shape: BoxShape.circle),
                  child: const Icon(Icons.login, color: Colors.green, size: 16),
                ),
                const SizedBox(width: 12),
                const Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('Masuk', style: TextStyle(fontSize: 10, color: Colors.grey)),
                      Text('07.10 WIB', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.green)),
                    ],
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                  decoration: BoxDecoration(color: Colors.green.withOpacity(0.1), borderRadius: BorderRadius.circular(12)),
                  child: const Text('Tepat Waktu', style: TextStyle(color: Colors.green, fontSize: 8)),
                )
              ],
            ),
          ),
          const SizedBox(height: 8),
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade200), borderRadius: BorderRadius.circular(12)),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(6),
                  decoration: BoxDecoration(color: Colors.blue.withOpacity(0.1), shape: BoxShape.circle),
                  child: const Icon(Icons.logout, color: Colors.blue, size: 16),
                ),
                const SizedBox(width: 12),
                const Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('Pulang', style: TextStyle(fontSize: 10, color: Colors.grey)),
                      Text('15.15 WIB', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.blue)),
                    ],
                  ),
                ),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _buildNilaiTerbaru() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Nilai Terbaru', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              Text('Lihat Semua', style: TextStyle(color: Colors.blue, fontSize: 10, fontWeight: FontWeight.bold)),
            ],
          ),
          const SizedBox(height: 12),
          _buildNilaiRow('Matematika', '92', 'A'),
          _buildNilaiRow('Bahasa Inggris', '88', 'A'),
          _buildNilaiRow('Kimia', '91', 'A'),
          _buildNilaiRow('Fisika', '85', 'B+'),
        ],
      ),
    );
  }

  Widget _buildNilaiRow(String subject, String score, String grade) {
    Color gradeColor = grade.startsWith('A') ? Colors.green : Colors.blue;
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(subject, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w500)),
          Row(
            children: [
              Text(score, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
              const SizedBox(width: 12),
              Text(grade, style: TextStyle(color: gradeColor, fontWeight: FontWeight.bold, fontSize: 12)),
            ],
          )
        ],
      ),
    );
  }

  Widget _buildPengumumanTerbaru() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Pengumuman Terbaru', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              Text('Lihat Semua', style: TextStyle(color: Colors.blue, fontSize: 10, fontWeight: FontWeight.bold)),
            ],
          ),
          const SizedBox(height: 16),
          _buildPengumumanItem('Libur Semester Ganjil', 'Libur semester akan dimulai pada tanggal 20 Juli - 2 Agustus 2026.', '2 jam yang lalu', Colors.green),
          const Divider(),
          _buildPengumumanItem('Pembagian Raport', 'Pembagian raport semester ganjil akan dilaksanakan pada 15 Juli 2026.', '1 hari yang lalu', Colors.blue),
        ],
      ),
    );
  }

  Widget _buildPengumumanItem(String title, String desc, String time, Color dotColor) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                const SizedBox(height: 4),
                Text(desc, style: const TextStyle(fontSize: 10, color: Colors.grey)),
                const SizedBox(height: 6),
                Text(time, style: const TextStyle(fontSize: 9, color: Colors.grey)),
              ],
            ),
          ),
          const SizedBox(width: 8),
          Container(width: 6, height: 6, decoration: BoxDecoration(color: dotColor, shape: BoxShape.circle)),
        ],
      ),
    );
  }

  Widget _buildTagihanAktif(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Tagihan Aktif', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              Text('Lihat Semua', style: TextStyle(color: Colors.blue, fontSize: 10, fontWeight: FontWeight.bold)),
            ],
          ),
          const SizedBox(height: 16),
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(color: Colors.red.shade50, borderRadius: BorderRadius.circular(12)),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('SPP Juli 2026', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                      decoration: BoxDecoration(color: Colors.red.shade100, borderRadius: BorderRadius.circular(4)),
                      child: const Text('Belum Dibayar', style: TextStyle(color: Colors.red, fontSize: 8, fontWeight: FontWeight.bold)),
                    )
                  ],
                ),
                const SizedBox(height: 8),
                const Text('Rp350.000', style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold, fontSize: 18)),
                const SizedBox(height: 4),
                const Text('Jatuh tempo 10 Juli 2026', style: TextStyle(fontSize: 10, color: Colors.grey)),
                const SizedBox(height: 12),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: () {
                      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Membuka Virtual Account...')));
                    },
                    icon: const Icon(Icons.account_balance_wallet, size: 16),
                    label: const Text('Bayar Sekarang', style: TextStyle(fontSize: 12)),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.blue.shade600,
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                      padding: const EdgeInsets.symmetric(vertical: 10),
                    ),
                  ),
                )
              ],
            ),
          )
        ],
      ),
    );
  }
}
