import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/data_provider.dart';
import '../theme/app_theme.dart';

class GradeScreen extends StatefulWidget {
  const GradeScreen({Key? key}) : super(key: key);

  @override
  _GradeScreenState createState() => _GradeScreenState();
}

class _GradeScreenState extends State<GradeScreen> {
  bool _isInit = true;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_isInit) {
      Provider.of<DataProvider>(context, listen: false).fetchGrades();
      _isInit = false;
    }
  }

  Color _gradeColor(dynamic scoreRaw) {
    final score = double.tryParse(scoreRaw.toString()) ?? 0;
    if (score >= 85) return Colors.green;
    if (score >= 70) return Colors.blue;
    if (score >= 60) return Colors.orange;
    return Colors.red;
  }

  String _gradeLabel(dynamic scoreRaw) {
    final score = double.tryParse(scoreRaw.toString()) ?? 0;
    if (score >= 85) return 'A';
    if (score >= 75) return 'B';
    if (score >= 65) return 'C';
    if (score >= 55) return 'D';
    return 'E';
  }

  String _typeLabel(String? type) {
    switch (type) {
      case 'daily': return 'Nilai Harian';
      case 'midterm': return 'UTS';
      case 'final': return 'UAS';
      case 'assignment': return 'Tugas';
      default: return type ?? '-';
    }
  }

  @override
  Widget build(BuildContext context) {
    final dataProvider = Provider.of<DataProvider>(context);
    final grades = dataProvider.allGrades;

    // Hitung rata-rata
    double avgScore = 0;
    if (grades.isNotEmpty) {
      final total = grades.fold<double>(
          0, (sum, g) => sum + (double.tryParse(g['score'].toString()) ?? 0));
      avgScore = total / grades.length;
    }

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      appBar: AppBar(
        title: const Text('Nilai Saya'),
        elevation: 0,
      ),
      body: dataProvider.isLoadingGrades
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: () => dataProvider.fetchGrades(),
              child: Column(
                children: [
                  // Kartu Rata-rata
                  if (grades.isNotEmpty)
                    Container(
                      margin: const EdgeInsets.all(16),
                      padding: const EdgeInsets.all(20),
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          colors: [_gradeColor(avgScore), _gradeColor(avgScore).withOpacity(0.7)],
                          begin: Alignment.topLeft,
                          end: Alignment.bottomRight,
                        ),
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text(
                                'Rata-rata Nilai',
                                style: TextStyle(color: Colors.white70, fontSize: 14),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                avgScore.toStringAsFixed(1),
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontSize: 44,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ],
                          ),
                          Container(
                            width: 70,
                            height: 70,
                            decoration: BoxDecoration(
                              color: Colors.white.withOpacity(0.2),
                              shape: BoxShape.circle,
                            ),
                            child: Center(
                              child: Text(
                                _gradeLabel(avgScore),
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontSize: 36,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  // Daftar Nilai
                  Expanded(
                    child: grades.isEmpty
                        ? Center(
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Icon(Icons.grade_outlined,
                                    size: 80, color: Colors.grey.shade300),
                                const SizedBox(height: 16),
                                const Text('Belum ada data nilai.',
                                    style: TextStyle(color: Colors.grey, fontSize: 16)),
                              ],
                            ),
                          )
                        : ListView.builder(
                            padding: const EdgeInsets.symmetric(horizontal: 16),
                            itemCount: grades.length,
                            itemBuilder: (context, index) {
                              final grade = grades[index];
                              final color = _gradeColor(grade['score']);
                              final label = _gradeLabel(grade['score']);

                              return Container(
                                margin: const EdgeInsets.only(bottom: 10),
                                decoration: BoxDecoration(
                                  color: Colors.white,
                                  borderRadius: BorderRadius.circular(14),
                                  boxShadow: [
                                    BoxShadow(
                                      color: Colors.black.withOpacity(0.04),
                                      blurRadius: 6,
                                      offset: const Offset(0, 2),
                                    )
                                  ],
                                ),
                                child: ListTile(
                                  contentPadding: const EdgeInsets.symmetric(
                                      horizontal: 16, vertical: 8),
                                  leading: Container(
                                    width: 48,
                                    height: 48,
                                    decoration: BoxDecoration(
                                      color: color.withOpacity(0.12),
                                      borderRadius: BorderRadius.circular(12),
                                    ),
                                    child: Center(
                                      child: Text(
                                        label,
                                        style: TextStyle(
                                          color: color,
                                          fontSize: 22,
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                    ),
                                  ),
                                  title: Text(
                                    grade['subject']?['name'] ?? 'Mapel',
                                    style: const TextStyle(
                                        fontWeight: FontWeight.bold, fontSize: 15),
                                  ),
                                  subtitle: Text(
                                    _typeLabel(grade['type']),
                                    style: TextStyle(
                                        color: Colors.grey.shade500, fontSize: 13),
                                  ),
                                  trailing: Text(
                                    grade['score'].toString(),
                                    style: TextStyle(
                                      fontSize: 28,
                                      fontWeight: FontWeight.bold,
                                      color: color,
                                    ),
                                  ),
                                ),
                              );
                            },
                          ),
                  ),
                ],
              ),
            ),
    );
  }
}
