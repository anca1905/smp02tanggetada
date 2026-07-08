import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/data_provider.dart';
import '../theme/app_theme.dart';
import '../providers/auth_provider.dart';

class ScheduleScreen extends StatefulWidget {
  const ScheduleScreen({Key? key}) : super(key: key);

  @override
  _ScheduleScreenState createState() => _ScheduleScreenState();
}

class _ScheduleScreenState extends State<ScheduleScreen> {
  bool _isInit = true;
  String _selectedDay = 'Senin';
  final List<String> _dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_isInit) {
      Provider.of<DataProvider>(context, listen: false).fetchSchedules();
      _isInit = false;
    }
  }

  @override
  Widget build(BuildContext context) {
    final dataProvider = Provider.of<DataProvider>(context);
    final user = Provider.of<AuthProvider>(context).user;
    final classroom = user?['classroom']?['name'] ?? '-';

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      appBar: AppBar(
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Jadwal Pelajaran', style: TextStyle(fontSize: 16)),
            PopupMenuButton<String>(
              initialValue: 'Semester Ganjil 2026/2027',
              child: const Row(
                children: [
                  Text('Semester Ganjil 2026/2027', style: TextStyle(fontSize: 12, fontWeight: FontWeight.normal)),
                  Icon(Icons.keyboard_arrow_down, size: 16),
                ],
              ),
              onSelected: (String result) {
                ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Memilih $result')));
              },
              itemBuilder: (BuildContext context) => <PopupMenuEntry<String>>[
                const PopupMenuItem<String>(
                  value: 'Semester Ganjil 2026/2027',
                  child: Text('Semester Ganjil 2026/2027'),
                ),
                const PopupMenuItem<String>(
                  value: 'Semester Genap 2025/2026',
                  child: Text('Semester Genap 2025/2026'),
                ),
              ],
            )
          ],
        ),
        actions: [
          IconButton(icon: const Icon(Icons.calendar_month), onPressed: () {}),
          IconButton(icon: const Icon(Icons.more_vert), onPressed: () {}),
        ],
        elevation: 0,
      ),
      body: dataProvider.isLoadingSchedules
          ? const Center(child: CircularProgressIndicator())
          : Column(
              children: [
                // Date Selector
                Container(
                  color: Colors.white,
                  child: SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                    child: Row(
                      children: _dayOrder.map((day) {
                        final isSelected = _selectedDay == day;
                        // Mock date
                        String date = '07 Jul';
                        if (day == 'Selasa') date = '08 Jul';
                        if (day == 'Rabu') date = '09 Jul';
                        if (day == 'Kamis') date = '10 Jul';
                        if (day == 'Jumat') date = '11 Jul';
                        if (day == 'Sabtu') date = '12 Jul';
                        
                        return GestureDetector(
                          onTap: () => setState(() => _selectedDay = day),
                          child: Container(
                            margin: const EdgeInsets.only(right: 24),
                            padding: const EdgeInsets.only(bottom: 8),
                            decoration: BoxDecoration(
                              border: Border(bottom: BorderSide(color: isSelected ? Colors.blue : Colors.transparent, width: 3))
                            ),
                            child: Column(
                              children: [
                                Text(day, style: TextStyle(fontWeight: isSelected ? FontWeight.bold : FontWeight.normal, color: isSelected ? Colors.blue : Colors.grey)),
                                const SizedBox(height: 4),
                                Text(date, style: TextStyle(fontSize: 12, color: isSelected ? Colors.blue : Colors.grey)),
                              ],
                            ),
                          ),
                        );
                      }).toList(),
                    ),
                  ),
                ),
                
                Expanded(
                  child: RefreshIndicator(
                    onRefresh: () => dataProvider.fetchSchedules(),
                    child: SingleChildScrollView(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Summary Card
                          Container(
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(16),
                              boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 4, offset: const Offset(0, 2))],
                            ),
                            child: Row(
                              children: [
                                Container(
                                  width: 60,
                                  height: 60,
                                  decoration: BoxDecoration(color: Colors.blue.shade50, borderRadius: BorderRadius.circular(12)),
                                  child: const Icon(Icons.calendar_today, color: Colors.blue, size: 30),
                                ),
                                const SizedBox(width: 16),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Row(
                                        children: [
                                          Text('$_selectedDay, 07 Juli 2026', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                                          const SizedBox(width: 8),
                                          Container(
                                            padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                            decoration: BoxDecoration(color: Colors.blue.shade100, borderRadius: BorderRadius.circular(4)),
                                            child: const Text('Hari A', style: TextStyle(color: Colors.blue, fontSize: 10, fontWeight: FontWeight.bold)),
                                          )
                                        ],
                                      ),
                                      const SizedBox(height: 12),
                                      Row(
                                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                        children: [
                                          _buildMiniSummary(Icons.menu_book, '${dataProvider.allSchedules[_selectedDay]?.length ?? 0}', 'Mapel'),
                                          _buildMiniSummary(Icons.access_time, '08.00 - 15.10', 'Jam Sekolah'),
                                          _buildMiniSummary(Icons.class_, classroom, 'Kelas'),
                                        ],
                                      )
                                    ],
                                  ),
                                )
                              ],
                            ),
                          ),

                          const SizedBox(height: 24),

                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              const Text('Jadwal Hari Ini', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                              GestureDetector(
                                onTap: () {
                                  showModalBottomSheet(
                                    context: context,
                                    builder: (context) => Container(
                                      padding: const EdgeInsets.all(24),
                                      height: 200,
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          const Text('Filter Jadwal', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                                          const SizedBox(height: 16),
                                          const Text('Pilih filter untuk menampilkan jadwal tertentu.'),
                                          const SizedBox(height: 16),
                                          ElevatedButton(
                                            onPressed: () => Navigator.pop(context),
                                            child: const Text('Terapkan'),
                                          )
                                        ],
                                      ),
                                    ),
                                  );
                                },
                                child: Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                                  decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade300), borderRadius: BorderRadius.circular(16)),
                                  child: const Row(
                                    children: [
                                      Icon(Icons.filter_list, size: 14),
                                      SizedBox(width: 4),
                                      Text('Filter', style: TextStyle(fontSize: 12)),
                                    ],
                                  ),
                                ),
                              )
                            ],
                          ),
                          
                          const SizedBox(height: 16),

                          // Timeline Schedule
                          Builder(
                            builder: (context) {
                              final schedules = dataProvider.allSchedules[_selectedDay] ?? [];
                              if (schedules.isEmpty) {
                                return const Padding(
                                  padding: EdgeInsets.symmetric(vertical: 40),
                                  child: Center(child: Text('Tidak ada jadwal untuk hari ini.', style: TextStyle(color: Colors.grey))),
                                );
                              }

                              return ListView.builder(
                                shrinkWrap: true,
                                physics: const NeverScrollableScrollPhysics(),
                                itemCount: schedules.length,
                                itemBuilder: (context, index) {
                                  final schedule = schedules[index];
                                  bool isFirst = index == 0;
                                  bool isLast = index == schedules.length - 1;
                                  
                                  return Row(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      // Time Column
                                      SizedBox(
                                        width: 50,
                                        child: Column(
                                          children: [
                                            Text(schedule['start_time'].toString().substring(0, 5), style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                                            Text(schedule['end_time'].toString().substring(0, 5), style: const TextStyle(color: Colors.grey, fontSize: 10)),
                                          ],
                                        ),
                                      ),
                                      // Timeline Line
                                      Column(
                                        children: [
                                          Container(
                                            width: 24,
                                            height: 24,
                                            decoration: BoxDecoration(color: Colors.blue, shape: BoxShape.circle, border: Border.all(color: Colors.white, width: 2)),
                                            child: Center(child: Text('${index + 1}', style: const TextStyle(color: Colors.white, fontSize: 10))),
                                          ),
                                          if (!isLast)
                                            Container(width: 2, height: 60, color: Colors.blue.shade100), // Adjusted height
                                        ],
                                      ),
                                      const SizedBox(width: 12),
                                      // Content Card
                                      Expanded(
                                        child: Container(
                                          margin: const EdgeInsets.only(bottom: 20),
                                          padding: const EdgeInsets.all(12),
                                          decoration: BoxDecoration(
                                            color: Colors.white,
                                            borderRadius: BorderRadius.circular(12),
                                            border: Border.all(color: Colors.grey.shade200),
                                          ),
                                          child: Row(
                                            crossAxisAlignment: CrossAxisAlignment.start,
                                            children: [
                                              Expanded(
                                                child: Column(
                                                  crossAxisAlignment: CrossAxisAlignment.start,
                                                  children: [
                                                    Row(
                                                      children: [
                                                        Expanded(child: Text(schedule['subject']['name'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14))),
                                                        Container(
                                                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                                          decoration: BoxDecoration(color: Colors.blue.shade50, borderRadius: BorderRadius.circular(4)),
                                                          child: const Text('Wajib', style: TextStyle(color: Colors.blue, fontSize: 10)),
                                                        )
                                                      ],
                                                    ),
                                                    const SizedBox(height: 4),
                                                    const Text('Materi Pembelajaran (Mock)', style: TextStyle(color: Colors.grey, fontSize: 11)),
                                                  ],
                                                ),
                                              ),
                                              const SizedBox(width: 8),
                                              Column(
                                                crossAxisAlignment: CrossAxisAlignment.end,
                                                children: [
                                                  Row(
                                                    children: [
                                                      const CircleAvatar(radius: 8, backgroundColor: Colors.grey, child: Icon(Icons.person, size: 10, color: Colors.white)),
                                                      const SizedBox(width: 4),
                                                      Text(schedule['teacher']['name'].split(' ').first, style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold)),
                                                    ],
                                                  ),
                                                  const SizedBox(height: 4),
                                                  Text('Ruang X', style: TextStyle(fontSize: 10, color: Colors.grey.shade600)),
                                                ],
                                              )
                                            ],
                                          ),
                                        ),
                                      )
                                    ],
                                  );
                                },
                              );
                            }
                          ),
                          
                          // Ringkasan Section
                          const SizedBox(height: 16),
                          const Text('Ringkasan', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                          const SizedBox(height: 12),
                          Row(
                            children: [
                              Expanded(child: _buildInfoCard('Total Hari Sekolah', '6', 'Hari', Icons.calendar_today, Colors.blue)),
                              const SizedBox(width: 12),
                              Expanded(child: _buildInfoCard('Total Mapel', '12', 'Mata Pelajaran', Icons.menu_book, Colors.purple)),
                            ],
                          ),
                          const SizedBox(height: 12),
                          _buildInfoCard('Rata-rata Jam/Hari', '7', 'Jam', Icons.access_time, Colors.orange),

                          const SizedBox(height: 24),
                          
                          // Unduh & Bagikan
                          const Text('Unduh & Bagikan', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                          const SizedBox(height: 12),
                          Container(
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
                            child: Column(
                              children: [
                                _buildActionRow(Icons.picture_as_pdf, 'Unduh PDF', 'Simpan jadwal', Colors.red, () {
                                  _showFakeProgressDialog(context, 'Mengunduh PDF Jadwal...', 'Jadwal berhasil diunduh ke folder Download');
                                }),
                                const Divider(height: 24),
                                _buildActionRow(Icons.share, 'Bagikan Jadwal', 'Kirim ke teman', Colors.blue, () {
                                  _showFakeProgressDialog(context, 'Menyiapkan file untuk dibagikan...', 'Fitur bagikan sedang disempurnakan');
                                }),
                                const Divider(height: 24),
                                _buildActionRow(Icons.event, 'Tambahkan ke Kalender', 'Google Calendar, dll', Colors.green, () {
                                  _showFakeProgressDialog(context, 'Sinkronisasi ke kalender...', 'Jadwal berhasil ditambahkan ke kalender Anda');
                                }),
                              ],
                            ),
                          )
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
    );
  }

  Widget _buildMiniSummary(IconData icon, String value, String label) {
    return Row(
      children: [
        Icon(icon, size: 14, color: Colors.blue),
        const SizedBox(width: 4),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(value, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
            Text(label, style: const TextStyle(fontSize: 9, color: Colors.grey)),
          ],
        )
      ],
    );
  }

  Widget _buildInfoCard(String title, String value, String subtitle, IconData icon, Color color) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.grey.shade200),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(value, style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: color)),
                  Text(subtitle, style: const TextStyle(fontSize: 9, color: Colors.grey)),
                ],
              ),
              Icon(icon, color: color, size: 24)
            ],
          )
        ],
      ),
    );
  }

  Widget _buildActionRow(IconData icon, String title, String subtitle, Color color, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(8),
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 4),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(color: color.withOpacity(0.1), borderRadius: BorderRadius.circular(8)),
              child: Icon(icon, color: color, size: 20),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                  Text(subtitle, style: const TextStyle(fontSize: 10, color: Colors.grey)),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }

  void _showFakeProgressDialog(BuildContext context, String message, String successMessage) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) {
        Future.delayed(const Duration(seconds: 2), () {
          if (mounted) {
            Navigator.pop(context);
            ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(successMessage)));
          }
        });
        return AlertDialog(
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const CircularProgressIndicator(),
              const SizedBox(height: 16),
              Text(message),
            ],
          ),
        );
      }
    );
  }
}
