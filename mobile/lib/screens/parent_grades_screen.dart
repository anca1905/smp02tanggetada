import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import '../widgets/donut_chart.dart';
import 'package:dio/dio.dart';
import 'dart:math';

class ParentGradesScreen extends StatefulWidget {
  const ParentGradesScreen({Key? key}) : super(key: key);

  @override
  State<ParentGradesScreen> createState() => _ParentGradesScreenState();
}

class _ParentGradesScreenState extends State<ParentGradesScreen> {
  int _activeTabIndex = 0;
  final List<String> _tabs = ['Ringkasan', 'Semester', 'Tahun', 'Rapor'];

  bool _isLoading = true;
  String _error = '';
  Map<String, dynamic> _gradesData = {};

  @override
  void initState() {
    super.initState();
    _fetchGrades();
  }

  Future<void> _fetchGrades() async {
    setState(() {
      _isLoading = true;
      _error = '';
    });
    
    try {
      final response = await ApiService().client.get('/student/grades');
      if (response.data['success']) {
        setState(() {
          _gradesData = response.data['data'];
          _isLoading = false;
        });
      } else {
        setState(() {
          _error = 'Gagal memuat data nilai.';
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
    final studentName = user?['student_name'] ?? 'Siswa';
    final classroom = user?['classroom']?['name'] ?? '-';

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      body: SafeArea(
        child: Column(
          children: [
            _buildHeader(studentName, classroom),
            _buildTabs(),
            Expanded(
              child: RefreshIndicator(
                onRefresh: _fetchGrades,
                child: _isLoading
                    ? const Center(child: CircularProgressIndicator())
                    : _error.isNotEmpty
                        ? ListView(
                            children: [
                              SizedBox(height: MediaQuery.of(context).size.height * 0.3),
                              Center(child: Text(_error, style: const TextStyle(color: Colors.red))),
                              Center(child: TextButton(onPressed: _fetchGrades, child: const Text('Coba Lagi')))
                            ],
                          )
                        : SingleChildScrollView(
                            physics: const AlwaysScrollableScrollPhysics(),
                            child: Column(
                              children: [
                                const SizedBox(height: 16),
                                _buildSummaryCards(),
                                const SizedBox(height: 16),
                                _buildGradesTable(),
                                const SizedBox(height: 16),
                                _buildChartsSection(),
                                const SizedBox(height: 16),
                                _buildLegendSection(),
                                const SizedBox(height: 16),
                                _buildPromoBanner(),
                                const SizedBox(height: 24),
                              ],
                            ),
                          ),
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(String studentName, String classroom) {
    return Container(
      padding: const EdgeInsets.only(left: 16, right: 16, top: 16, bottom: 20),
      decoration: const BoxDecoration(color: Color(0xFF3B82F6)),
      child: Row(
        children: [
          const Icon(Icons.arrow_back, color: Colors.white),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Nilai', style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                const SizedBox(height: 4),
                Row(
                  children: [
                    Expanded(child: Text('$studentName ($classroom)', style: const TextStyle(color: Colors.white70, fontSize: 12), maxLines: 1, overflow: TextOverflow.ellipsis)),
                    const Icon(Icons.keyboard_arrow_down, color: Colors.white70, size: 16),
                  ],
                )
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), borderRadius: BorderRadius.circular(12)),
            child: const Icon(Icons.calendar_today, color: Colors.white, size: 20),
          )
        ],
      ),
    );
  }

  Widget _buildTabs() {
    return Container(
      color: Colors.white,
      padding: const EdgeInsets.only(top: 8),
      child: Row(
        children: List.generate(_tabs.length, (index) {
          final isActive = _activeTabIndex == index;
          return Expanded(
            child: GestureDetector(
              onTap: () => setState(() => _activeTabIndex = index),
              child: Column(
                children: [
                  Text(
                    _tabs[index],
                    style: TextStyle(
                      color: isActive ? Colors.blue.shade700 : Colors.grey.shade500,
                      fontWeight: isActive ? FontWeight.bold : FontWeight.normal,
                      fontSize: 13,
                    ),
                  ),
                  const SizedBox(height: 12),
                  Container(
                    height: 3,
                    decoration: BoxDecoration(
                      color: isActive ? Colors.blue.shade700 : Colors.transparent,
                      borderRadius: const BorderRadius.only(topLeft: Radius.circular(3), topRight: Radius.circular(3)),
                    ),
                  )
                ],
              ),
            ),
          );
        }),
      ),
    );
  }

  Widget _buildSummaryCards() {
    final summary = _gradesData['summary'] ?? {};
    final avg = (summary['average'] ?? 0).toString();
    final highest = (summary['highest'] ?? 0).toString();
    final lowest = (summary['lowest'] ?? 0).toString();
    final total = (summary['total_subjects'] ?? 0).toString();

    double avgDouble = double.tryParse(avg) ?? 0.0;
    String predicate = avgDouble >= 86 ? 'Sangat Baik' : (avgDouble >= 75 ? 'Baik' : 'Cukup');

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: const EdgeInsets.symmetric(horizontal: 16),
      physics: const BouncingScrollPhysics(),
      child: Row(
        children: [
          // Rata-rata Nilai Card
          Container(
            width: 220,
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
            ),
            child: Row(
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Rata-rata Nilai', style: TextStyle(fontSize: 10, color: Colors.grey)),
                      const SizedBox(height: 4),
                      Text(avg, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.blue)),
                      const SizedBox(height: 4),
                      Text(predicate, style: const TextStyle(fontSize: 10, color: Colors.grey)),
                      const SizedBox(height: 4),
                      RichText(
                        text: const TextSpan(
                          text: 'Ranking Kelas: ',
                          style: TextStyle(fontSize: 10, color: Colors.grey),
                          children: [
                            TextSpan(text: 'TBD', style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
                          ],
                        ),
                      )
                    ],
                  ),
                ),
                Stack(
                  alignment: Alignment.center,
                  children: [
                    SizedBox(
                      width: 60,
                      height: 60,
                      child: CircularProgressIndicator(
                        value: avgDouble / 100,
                        backgroundColor: Colors.blue.withOpacity(0.1),
                        color: Colors.blue,
                        strokeWidth: 6,
                      ),
                    ),
                    Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Text(avg, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                        const Text('Rata-rata', style: TextStyle(fontSize: 8, color: Colors.grey)),
                      ],
                    )
                  ],
                )
              ],
            ),
          ),
          const SizedBox(width: 12),
          // Tertinggi
          _buildMiniStatCard('Tertinggi', highest, 'Semester Ini', Colors.green, Icons.trending_up),
          const SizedBox(width: 12),
          // Terendah
          _buildMiniStatCard('Terendah', lowest, 'Semester Ini', Colors.red, Icons.trending_down),
          const SizedBox(width: 12),
          // Total Mapel
          _buildMiniStatCard('Total Data', total, 'Mata Pelajaran', Colors.blue, null),
          const SizedBox(width: 12),
          // KKM
          _buildMiniStatCard('KKM Standar', '75', '', Colors.orange, Icons.star_border),
        ],
      ),
    );
  }

  Widget _buildMiniStatCard(String title, String value, String subtitle, Color color, IconData? icon) {
    return Container(
      width: 110,
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: TextStyle(fontSize: 10, color: color, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          Text(value, style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: color)),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(child: Text(subtitle, style: const TextStyle(fontSize: 9, color: Colors.grey), maxLines: 1, overflow: TextOverflow.ellipsis)),
              if (icon != null) Icon(icon, size: 14, color: color),
            ],
          )
        ],
      ),
    );
  }

  Widget _buildGradesTable() {
    final List grades = _gradesData['grades'] ?? [];

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
        ),
        child: Column(
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade300), borderRadius: BorderRadius.circular(20)),
                  child: const Row(
                    children: [
                      Text('Semester Ini', style: TextStyle(fontSize: 12)),
                      SizedBox(width: 4),
                      Icon(Icons.keyboard_arrow_down, size: 16, color: Colors.grey),
                    ],
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade300), borderRadius: BorderRadius.circular(8)),
                  child: const Row(
                    children: [
                      Icon(Icons.filter_list, size: 14, color: Colors.grey),
                      SizedBox(width: 4),
                      Text('Filter', style: TextStyle(fontSize: 12, color: Colors.grey)),
                    ],
                  ),
                )
              ],
            ),
            const SizedBox(height: 16),
            const Row(
              children: [
                Expanded(flex: 3, child: Text('Mata Pelajaran', style: TextStyle(fontSize: 10, color: Colors.grey))),
                Expanded(flex: 1, child: Center(child: Text('Nilai', style: TextStyle(fontSize: 10, color: Colors.grey)))),
                Expanded(flex: 1, child: Center(child: Text('Predikat', style: TextStyle(fontSize: 10, color: Colors.grey)))),
                Expanded(flex: 1, child: Center(child: Text('KKM', style: TextStyle(fontSize: 10, color: Colors.grey)))),
                Expanded(flex: 1, child: Align(alignment: Alignment.centerRight, child: Text('Status', style: TextStyle(fontSize: 10, color: Colors.grey)))),
              ],
            ),
            const Divider(height: 24),
            if (grades.isEmpty)
              const Padding(padding: EdgeInsets.all(16), child: Text('Belum ada data nilai', style: TextStyle(color: Colors.grey, fontSize: 12))),
            for (var g in grades)
              _buildSubjectRow(
                g['subject']['name'],
                g['type'], // Used as description instead of teacher name for now
                g['score'].toString(),
                _getGradeLetter(g['score']),
                '75',
                (g['score'] as num) >= 75,
                Icons.menu_book,
                Colors.blue,
              ),
            const SizedBox(height: 16),
          ],
        ),
      ),
    );
  }

  String _getGradeLetter(dynamic s) {
    num score = s as num;
    if (score >= 90) return 'A';
    if (score >= 80) return 'B';
    if (score >= 75) return 'C';
    return 'D';
  }

  Widget _buildSubjectRow(String subject, String teacher, String score, String grade, String kkm, bool isTuntas, IconData icon, Color iconColor) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Row(
        children: [
          Expanded(
            flex: 3,
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(6),
                  decoration: BoxDecoration(color: iconColor.withOpacity(0.1), shape: BoxShape.circle),
                  child: Icon(icon, color: iconColor, size: 16),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(subject, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12), maxLines: 1, overflow: TextOverflow.ellipsis),
                      Text(teacher, style: const TextStyle(fontSize: 10, color: Colors.grey), maxLines: 1, overflow: TextOverflow.ellipsis),
                    ],
                  ),
                )
              ],
            ),
          ),
          Expanded(flex: 1, child: Center(child: Text(score, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: isTuntas ? Colors.green : Colors.red)))),
          Expanded(flex: 1, child: Center(child: Text(grade, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: isTuntas ? Colors.green : Colors.blue)))),
          Expanded(flex: 1, child: Center(child: Text(kkm, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)))),
          Expanded(
            flex: 1,
            child: Align(
              alignment: Alignment.centerRight,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                decoration: BoxDecoration(
                  color: isTuntas ? Colors.green.withOpacity(0.1) : Colors.red.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(isTuntas ? 'Tuntas' : 'Gagal', style: TextStyle(fontSize: 8, color: isTuntas ? Colors.green : Colors.red, fontWeight: FontWeight.bold), textAlign: TextAlign.center),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildChartsSection() {
    final dist = _gradesData['distribution'] ?? {};
    final total = _gradesData['summary']?['total_subjects'] ?? 1; // avoid / 0
    final aCount = dist['A'] ?? 0;
    final bCount = dist['B'] ?? 0;
    final cCount = dist['C'] ?? 0;
    final dCount = dist['D'] ?? 0;

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Line Chart Card
          Expanded(
            flex: 3,
            child: Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16), boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))]),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Trend Nilai', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                    ],
                  ),
                  const SizedBox(height: 16),
                  const SizedBox(
                    height: 120,
                    width: double.infinity,
                    child: _SimpleLineChartMock(),
                  ),
                  const SizedBox(height: 12),
                  const Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.horizontal_rule, color: Colors.blue, size: 12),
                      SizedBox(width: 4),
                      Text('Nilai Anak', style: TextStyle(fontSize: 8, color: Colors.grey)),
                      SizedBox(width: 12),
                      Icon(Icons.horizontal_rule, color: Colors.green, size: 12),
                      SizedBox(width: 4),
                      Text('KKM (75)', style: TextStyle(fontSize: 8, color: Colors.grey)),
                    ],
                  )
                ],
              ),
            ),
          ),
          const SizedBox(width: 12),
          // Donut Chart Card
          Expanded(
            flex: 2,
            child: Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16), boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))]),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Distribusi', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                  const SizedBox(height: 16),
                  Center(
                    child: DonutChart(
                      radius: 40,
                      strokeWidth: 15,
                      data: [
                        if (aCount > 0) DonutChartData('A', (aCount as int).toDouble(), Colors.green),
                        if (bCount > 0) DonutChartData('B', (bCount as int).toDouble(), Colors.orange),
                        if (cCount > 0) DonutChartData('C', (cCount as int).toDouble(), Colors.blue),
                        if (dCount > 0) DonutChartData('D', (dCount as int).toDouble(), Colors.red),
                        if (total == 0) DonutChartData('Empty', 1, Colors.grey.shade300),
                      ],
                      centerContent: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text(total.toString(), style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                          const Text('Total Data', style: TextStyle(fontSize: 6, color: Colors.grey)),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  if (total > 0) ...[
                    _buildLegendRow(Colors.green, 'A', aCount.toString(), '${((aCount / total) * 100).toStringAsFixed(1)}%'),
                    _buildLegendRow(Colors.orange, 'B', bCount.toString(), '${((bCount / total) * 100).toStringAsFixed(1)}%'),
                    _buildLegendRow(Colors.blue, 'C', cCount.toString(), '${((cCount / total) * 100).toStringAsFixed(1)}%'),
                    _buildLegendRow(Colors.red, 'D', dCount.toString(), '${((dCount / total) * 100).toStringAsFixed(1)}%'),
                  ]
                ],
              ),
            ),
          )
        ],
      ),
    );
  }

  Widget _buildLegendRow(Color color, String grade, String count, String pct) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            children: [
              Container(width: 6, height: 6, decoration: BoxDecoration(shape: BoxShape.circle, color: color)),
              const SizedBox(width: 4),
              Text('$grade ($count)', style: const TextStyle(fontSize: 8, fontWeight: FontWeight.bold)),
            ],
          ),
          Text(pct, style: const TextStyle(fontSize: 8, color: Colors.grey)),
        ],
      ),
    );
  }

  Widget _buildLegendSection() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16), boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))]),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Keterangan Predikat', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(child: _buildLegendBox('A (Sangat Baik)', '>= 90', Colors.green)),
                const SizedBox(width: 8),
                Expanded(child: _buildLegendBox('B (Baik)', '80 - 89', Colors.orange)),
                const SizedBox(width: 8),
                Expanded(child: _buildLegendBox('C (Cukup)', '75 - 79', Colors.blue)),
                const SizedBox(width: 8),
                Expanded(child: _buildLegendBox('D (Kurang)', '< 75', Colors.red)),
              ],
            )
          ],
        ),
      ),
    );
  }

  Widget _buildLegendBox(String title, String range, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 4),
      decoration: BoxDecoration(color: color.withOpacity(0.05), borderRadius: BorderRadius.circular(8)),
      child: Column(
        children: [
          Text(title, style: TextStyle(color: color, fontWeight: FontWeight.bold, fontSize: 7), textAlign: TextAlign.center, maxLines: 2, overflow: TextOverflow.ellipsis),
          const SizedBox(height: 4),
          Text(range, style: const TextStyle(color: Colors.black87, fontSize: 8)),
        ],
      ),
    );
  }

  Widget _buildPromoBanner() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(color: Colors.blue.shade50, borderRadius: BorderRadius.circular(16)),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(color: Colors.blue.shade400, borderRadius: BorderRadius.circular(8)),
              child: const Icon(Icons.menu_book, color: Colors.white, size: 24),
            ),
            const SizedBox(width: 12),
            const Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Pantau Perkembangan Nilai', style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold, fontSize: 13)),
                  SizedBox(height: 4),
                  Text('Terus dukung dan motivasi anak Anda untuk meningkatkan prestasi belajar.', style: TextStyle(color: Colors.blueGrey, fontSize: 10)),
                ],
              ),
            ),
            const Icon(Icons.school, color: Colors.blue, size: 40) // Mocking illustration
          ],
        ),
      ),
    );
  }
}

class _SimpleLineChartMock extends StatelessWidget {
  const _SimpleLineChartMock();

  @override
  Widget build(BuildContext context) {
    return CustomPaint(
      painter: _MockChartPainter(),
    );
  }
}

class _MockChartPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final paintLine = Paint()
      ..color = Colors.blue
      ..strokeWidth = 2
      ..style = PaintingStyle.stroke;

    final paintDot = Paint()
      ..color = Colors.blue
      ..style = PaintingStyle.fill;

    final paintKkm = Paint()
      ..color = Colors.green.withOpacity(0.5)
      ..strokeWidth = 1
      ..style = PaintingStyle.stroke;

    // Draw horizontal dashed line for KKM (75%)
    double kkmY = size.height * 0.25; // 75 is roughly top 25% of 0-100 chart
    double dashWidth = 5, dashSpace = 5, startX = 0;
    while (startX < size.width) {
      canvas.drawLine(Offset(startX, kkmY), Offset(startX + dashWidth, kkmY), paintKkm);
      startX += dashWidth + dashSpace;
    }

    // Since we don't have historical data dynamically yet, we just render a simple mock path for aesthetics
    List<double> values = [0.8, 0.85, 0.7, 0.9, 0.75, 0.8]; // Normalized 0-1 (higher is lower Y)
    List<Offset> points = [];
    double stepX = size.width / 5;

    for (int i = 0; i < values.length; i++) {
      points.add(Offset(i * stepX, size.height * (1 - values[i])));
    }

    final path = Path();
    path.moveTo(points[0].dx, points[0].dy);
    for (int i = 1; i < points.length; i++) {
      path.lineTo(points[i].dx, points[i].dy);
    }
    canvas.drawPath(path, paintLine);

    final textPainter = TextPainter(textDirection: TextDirection.ltr);
    List<String> labels = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6'];

    for (int i = 0; i < points.length; i++) {
      canvas.drawCircle(points[i], 3, paintDot);
      
      textPainter.text = TextSpan(text: labels[i], style: const TextStyle(color: Colors.grey, fontSize: 8));
      textPainter.layout();
      textPainter.paint(canvas, Offset(points[i].dx - textPainter.width / 2, size.height - 10));
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
