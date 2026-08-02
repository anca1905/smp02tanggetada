import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import '../widgets/sparkline_chart.dart';
import '../widgets/activity_timeline.dart';
import 'package:dio/dio.dart';
import 'parent_payment_screen.dart';

class ParentHomeScreen extends StatefulWidget {
  const ParentHomeScreen({Key? key}) : super(key: key);

  @override
  State<ParentHomeScreen> createState() => _ParentHomeScreenState();
}

class _ParentHomeScreenState extends State<ParentHomeScreen> {
  bool _isLoading = true;
  String _error = '';
  
  Map<String, dynamic> _dashboardData = {};
  Map<String, dynamic> _billingData = {};
  List<dynamic> _gradesData = [];

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  Future<void> _fetchData() async {
    setState(() {
      _isLoading = true;
      _error = '';
    });
    
    try {
      final api = ApiService().client;
      // Fetch Dashboard
      final resDash = await api.get('/student/dashboard');
      // Fetch Bills for the summary card and active bill section
      final resBills = await api.get('/student/bills');
      // Fetch Grades for the average score card
      final resGrades = await api.get('/student/grades');

      if (resDash.data['success'] && resBills.data['success'] && resGrades.data['success']) {
        setState(() {
          _dashboardData = resDash.data['data'];
          _billingData = resBills.data['data'];
          _gradesData = resGrades.data['data']['grades'];
          _isLoading = false;
        });
      } else {
        setState(() {
          _error = 'Gagal memuat data dari server.';
          _isLoading = false;
        });
      }
    } on DioException catch (e) {
      setState(() {
        _error = 'Koneksi bermasalah: ${e.message}';
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _error = 'Terjadi kesalahan tidak terduga.';
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = Provider.of<AuthProvider>(context).user;
    final parentName = user?['parent_name'] ?? 'Wali Murid';
    final studentName = user?['student_name'] ?? 'Siswa';
    final nis = user?['nis'] ?? '-';
    final classroom = user?['classroom']?['name'] ?? '-';

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      body: SafeArea(
        child: RefreshIndicator(
          onRefresh: _fetchData,
          child: _isLoading 
            ? const Center(child: CircularProgressIndicator())
            : _error.isNotEmpty
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(_error, style: const TextStyle(color: Colors.red)),
                      TextButton(onPressed: _fetchData, child: const Text('Coba Lagi'))
                    ],
                  ),
                )
              : SingleChildScrollView(
                  physics: const AlwaysScrollableScrollPhysics(),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _buildHeader(context, parentName, studentName, nis, classroom),
                      const SizedBox(height: 16),
                      _buildStatsRow(),
                      const SizedBox(height: 24),
                      _buildMainMenuGrid(),
                      const SizedBox(height: 24),
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
                      _buildTimelineActivity(),
                      const SizedBox(height: 24),
                    ],
                  ),
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
              Expanded(
                child: Row(
                  children: [
                    const CircleAvatar(
                      radius: 24,
                      backgroundImage: NetworkImage('https://ui-avatars.com/api/?background=random&color=fff'), 
                    ),
                    const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Halo,', style: TextStyle(color: Colors.white70, fontSize: 12)),
                        Text(parentName, style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold), maxLines: 1, overflow: TextOverflow.ellipsis),
                        const Text('Orang Tua / Wali', style: TextStyle(color: Colors.white70, fontSize: 12)),
                      ],
                    ),
                  )
                ],
              )),
              Row(
                children: [
                  _buildIconBadge(Icons.notifications_none, '0'),
                  const SizedBox(width: 12),
                  _buildIconBadge(Icons.chat_bubble_outline, '0'),
                ],
              )
            ],
          ),
        ),
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
                CircleAvatar(
                  radius: 24,
                  backgroundColor: Colors.blue.shade50,
                  child: const Icon(Icons.person, color: Colors.blue),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(studentName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16), maxLines: 1, overflow: TextOverflow.ellipsis),
                      Text('Kelas $classroom • NIS $nis', style: const TextStyle(color: Colors.grey, fontSize: 12), maxLines: 1, overflow: TextOverflow.ellipsis),
                      const SizedBox(height: 4),
                      Row(
                        children: [
                          Icon(Icons.check_circle, color: _dashboardData['attendance_percentage'] >= 80 ? Colors.green : Colors.orange, size: 12),
                          const SizedBox(width: 4),
                          Text('${_dashboardData['attendance_percentage']}% Kehadiran', style: TextStyle(color: _dashboardData['attendance_percentage'] >= 80 ? Colors.green : Colors.orange, fontSize: 10, fontWeight: FontWeight.bold)),
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
        if (count != '0')
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

  Widget _buildStatsRow() {
    final att = _dashboardData['attendance_percentage'] ?? 0;
    
    // Average score calculation
    double avgScore = 0;
    if (_gradesData.isNotEmpty) {
      double sum = 0;
      for (var g in _gradesData) {
        sum += (g['score'] as num).toDouble();
      }
      avgScore = sum / _gradesData.length;
    }
    
    final sisaTagihan = _billingData['summary']?['sisa_tagihan'] ?? 'Rp0';
    final activeBillsCount = _billingData['summary']?['tagihan_belum_dibayar'] ?? 0;
    
    final activeAssignments = (_dashboardData['upcoming_assignments'] as List?)?.length ?? 0;

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: const EdgeInsets.symmetric(horizontal: 16),
      physics: const BouncingScrollPhysics(),
      child: Row(
        children: [
          _buildStatCard('$att%', 'Kehadiran', Colors.green, [4, 5, 4, 6, 8, 7, 9], Icons.check_circle),
          _buildStatCard(avgScore.toStringAsFixed(1), 'Rata Nilai', Colors.blue, [7, 7.5, 8, 8.5, 8.2, 8.9, 8.9], Icons.menu_book),
          _buildStatCard('$activeAssignments', 'Tugas Aktif', Colors.orange, [5, 4, 3, 4, 2, 3, 2], Icons.assignment),
          if (activeBillsCount > 0)
            _buildStatCard(sisaTagihan, '$activeBillsCount Tagihan', Colors.purple, [2, 3, 2, 4, 5, 4, 6], Icons.account_balance_wallet),
        ],
      ),
    );
  }

  Widget _buildStatCard(String mainValue, String label, Color color, List<double> chartData, IconData icon) {
    return Container(
      width: 130,
      margin: const EdgeInsets.only(right: 12, top: 40),
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
          Text(mainValue, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 20)),
          const SizedBox(height: 4),
          Text(label, style: const TextStyle(fontSize: 10, color: Colors.grey)),
          const SizedBox(height: 12),
          SparklineChart(data: chartData, color: color),
        ],
      ),
    );
  }

  Widget _buildMainMenuGrid() {
    return Padding(
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
    final List schedules = _dashboardData['today_schedules'] ?? [];
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
          if (schedules.isEmpty)
             const Padding(padding: EdgeInsets.all(16), child: Center(child: Text('Tidak ada jadwal hari ini', style: TextStyle(fontSize: 11, color: Colors.grey)))),
          for (int i = 0; i < schedules.length; i++)
            _buildJadwalItem(
              '${schedules[i]['start_time'].substring(0,5)} - ${schedules[i]['end_time'].substring(0,5)}', 
              schedules[i]['subject']['name'], 
              schedules[i]['teacher']['name'], 
              Icons.menu_book, 
              Colors.blue, 
              isLast: i == schedules.length - 1
            ),
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
                      Text(time, style: const TextStyle(fontSize: 10, color: Colors.grey), maxLines: 1, overflow: TextOverflow.ellipsis),
                      Text(subject, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12), maxLines: 1, overflow: TextOverflow.ellipsis),
                      Text(teacher, style: const TextStyle(fontSize: 10, color: Colors.grey), maxLines: 1, overflow: TextOverflow.ellipsis),
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
              Text('Detail', style: TextStyle(color: Colors.blue, fontSize: 10, fontWeight: FontWeight.bold)),
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
                      Text('Status Presensi', style: TextStyle(fontSize: 10, color: Colors.grey)),
                      Text('Terekam', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.green)),
                    ],
                  ),
                ),
              ],
            ),
          ),
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
              Text('Semua', style: TextStyle(color: Colors.blue, fontSize: 10, fontWeight: FontWeight.bold)),
            ],
          ),
          const SizedBox(height: 12),
          if (_gradesData.isEmpty)
            const Center(child: Text('Belum ada nilai', style: TextStyle(fontSize: 11, color: Colors.grey))),
          for (var g in _gradesData.take(4))
             _buildNilaiRow(g['subject']['name'], g['score'].toString(), (g['score'] as num) >= 90 ? 'A' : ((g['score'] as num) >= 80 ? 'B' : 'C')),
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
          Expanded(child: Text(subject, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w500), maxLines: 1, overflow: TextOverflow.ellipsis)),
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
    final List posts = _dashboardData['announcements'] ?? [];
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
              Text('Pengumuman', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              Text('Semua', style: TextStyle(color: Colors.blue, fontSize: 10, fontWeight: FontWeight.bold)),
            ],
          ),
          const SizedBox(height: 16),
          if (posts.isEmpty)
             const Padding(padding: EdgeInsets.all(16), child: Center(child: Text('Tidak ada pengumuman', style: TextStyle(fontSize: 11, color: Colors.grey)))),
          for (var p in posts)
             _buildPengumumanItem(p['title'], p['category'], p['created_at'].toString().substring(0, 10), Colors.blue),
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
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12), maxLines: 2, overflow: TextOverflow.ellipsis),
                const SizedBox(height: 4),
                Text(desc, style: const TextStyle(fontSize: 10, color: Colors.grey), maxLines: 1, overflow: TextOverflow.ellipsis),
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
    final List activeBills = _billingData['active_bills'] ?? [];
    
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
            ],
          ),
          const SizedBox(height: 16),
          if (activeBills.isEmpty)
            const Center(child: Padding(padding: EdgeInsets.all(16), child: Text('Tidak ada tagihan.', style: TextStyle(color: Colors.green, fontSize: 12, fontWeight: FontWeight.bold))))
          else
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(color: Colors.red.shade50, borderRadius: BorderRadius.circular(12)),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Expanded(child: Text(activeBills.first['title'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12), maxLines: 1, overflow: TextOverflow.ellipsis)),
                      const SizedBox(width: 8),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                        decoration: BoxDecoration(color: Colors.red.shade100, borderRadius: BorderRadius.circular(4)),
                        child: Text(activeBills.first['status'], style: const TextStyle(color: Colors.red, fontSize: 8, fontWeight: FontWeight.bold)),
                      )
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(activeBills.first['amount'], style: const TextStyle(color: Colors.red, fontWeight: FontWeight.bold, fontSize: 18)),
                  const SizedBox(height: 4),
                  Text('Jatuh tempo ${activeBills.first['due_date']}', style: const TextStyle(fontSize: 10, color: Colors.grey)),
                  const SizedBox(height: 12),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: () {
                         Navigator.push(context, MaterialPageRoute(builder: (context) => const ParentPaymentScreen()));
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

  Widget _buildTimelineActivity() {
    return Padding(
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
                Text('Aktivitas Sistem Terakhir', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
              ],
            ),
            const SizedBox(height: 20),
            ActivityTimeline(
              items: [
                ActivityTimelineItem(time: 'Baru saja', title: 'Sinkronisasi Data\nberhasil', subtitle: '', icon: Icons.sync, color: Colors.blue),
                ActivityTimelineItem(time: 'Hari Ini', title: 'Aplikasi berjalan\ndengan data dinamis', subtitle: '', icon: Icons.check_circle, color: Colors.green),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
