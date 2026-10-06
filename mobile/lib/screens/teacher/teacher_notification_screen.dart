import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../services/api_service.dart';

class TeacherNotificationScreen extends StatefulWidget {
  const TeacherNotificationScreen({super.key});

  @override
  State<TeacherNotificationScreen> createState() => _TeacherNotificationScreenState();
}

class _TeacherNotificationScreenState extends State<TeacherNotificationScreen> {
  final ApiService _apiService = ApiService();
  bool _isLoading = true;
  String _selectedCategory = 'Semua';
  List<Map<String, dynamic>> _notifications = [];
  Set<String> _readNotificationIds = {};

  final List<String> _categories = ['Semua', 'Presensi', 'Pengumuman', 'Sistem'];

  @override
  void initState() {
    super.initState();
    _loadNotifications();
  }

  Future<void> _loadNotifications() async {
    setState(() => _isLoading = true);

    try {
      final prefs = await SharedPreferences.getInstance();
      final readIds = prefs.getStringList('teacher_read_notifications') ?? [];
      _readNotificationIds = readIds.toSet();

      final response = await _apiService.client.get('/teacher/notifications');
      if (response.statusCode == 200 && response.data['success'] == true) {
        final List<dynamic> data = response.data['data'] ?? [];
        _notifications = data.map((item) => Map<String, dynamic>.from(item)).toList();
      } else {
        _notifications = _getDefaultNotifications();
      }
    } catch (_) {
      _notifications = _getDefaultNotifications();
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  List<Map<String, dynamic>> _getDefaultNotifications() {
    final now = DateTime.now();
    return [
      {
        'id': 'att_apel_default',
        'title': 'Pengingat Apel Pagi',
        'message': 'Lakukan pengecekan kehadiran apel pagi sebelum pukul 07:15 WITA.',
        'full_message': 'Kepada Bapak/Ibu Guru piket dan wali kelas, mohon memastikan kehadiran siswa pada saat apel pagi sebelum pembelajaran dimulai pukul 07:15 WITA di lapangan upacara.',
        'type': 'attendance',
        'category': 'Presensi',
        'created_at': DateTime(now.year, now.month, now.day, 6, 45).toIso8601String(),
      },
      {
        'id': 'ann_kurikulum_default',
        'title': 'Rapat Evaluasi Pembelajaran & E-Rapor',
        'message': 'Rapat pleno dewan guru dilaksanakan pada hari Sabtu pukul 09:00 WITA di Ruang Guru.',
        'full_message': 'Diberitahukan kepada seluruh dewan guru SMP Negeri 2 Tanggetada bahwa rapat koordinasi evaluasi bulanan dan sinkronisasi data presensi siswa akan diselenggarakan di ruang guru.',
        'type': 'announcement',
        'category': 'Pengumuman',
        'created_at': DateTime(now.year, now.month, now.day - 1, 10, 0).toIso8601String(),
      },
      {
        'id': 'sys_sync_default',
        'title': 'Penyimpanan Offline & Sinkronisasi',
        'message': 'Sistem offline siap digunakan. Data absensi akan otomatis terkirim saat tersambung internet.',
        'full_message': 'Fitur penyimpanan offline aktif. Anda tetap dapat mengabsen siswa tanpa koneksi internet di ruang kelas. Semua data antrean akan otomatis terkirim ke server begitu HP terhubung sinyal internet.',
        'type': 'system',
        'category': 'Sistem',
        'created_at': DateTime(now.year, now.month, now.day - 2, 7, 30).toIso8601String(),
      },
      {
        'id': 'att_pulang_default',
        'title': 'Presensi Jam Pulang Siswa',
        'message': 'Pengecekan absensi kepulangan dibuka pukul 13:30 WITA.',
        'full_message': 'Bapak/Ibu guru jam pelajaran terakhir dimohon memastikan presensi kepulangan siswa telah terdata sebelum siswa meninggalkan ruang kelas.',
        'type': 'attendance',
        'category': 'Presensi',
        'created_at': DateTime(now.year, now.month, now.day - 3, 13, 0).toIso8601String(),
      },
    ];
  }

  Future<void> _markAsRead(String id) async {
    _readNotificationIds.add(id);
    final prefs = await SharedPreferences.getInstance();
    await prefs.setStringList('teacher_read_notifications', _readNotificationIds.toList());
    setState(() {});
  }

  Future<void> _markAllAsRead() async {
    for (final notif in _notifications) {
      final id = notif['id']?.toString() ?? '';
      if (id.isNotEmpty) {
        _readNotificationIds.add(id);
      }
    }
    final prefs = await SharedPreferences.getInstance();
    await prefs.setStringList('teacher_read_notifications', _readNotificationIds.toList());
    setState(() {});

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Semua notifikasi ditandai telah dibaca'),
          duration: Duration(seconds: 2),
        ),
      );
    }
  }

  void _showDetail(Map<String, dynamic> notif) {
    final id = notif['id']?.toString() ?? '';
    if (id.isNotEmpty) {
      _markAsRead(id);
    }

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => Container(
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
        ),
        padding: const EdgeInsets.fromLTRB(24, 16, 24, 32),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Center(
              child: Container(
                width: 40,
                height: 4,
                decoration: BoxDecoration(
                  color: Colors.grey.shade300,
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
            ),
            const SizedBox(height: 20),
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: _getColorForType(notif['type']).withOpacity(0.12),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    notif['category'] ?? 'Notifikasi',
                    style: TextStyle(
                      color: _getColorForType(notif['type']),
                      fontWeight: FontWeight.bold,
                      fontSize: 12,
                    ),
                  ),
                ),
                const Spacer(),
                Text(
                  _formatTime(notif['created_at']),
                  style: TextStyle(color: Colors.grey.shade500, fontSize: 12),
                ),
              ],
            ),
            const SizedBox(height: 14),
            Text(
              notif['title'] ?? 'Notifikasi',
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Color(0xFF0F172A),
              ),
            ),
            const SizedBox(height: 12),
            Text(
              notif['full_message'] ?? notif['message'] ?? '-',
              style: const TextStyle(
                fontSize: 14,
                color: Color(0xFF475569),
                height: 1.5,
              ),
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () => Navigator.pop(ctx),
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF1E40AF),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  padding: const EdgeInsets.symmetric(vertical: 12),
                ),
                child: const Text('Tutup', style: TextStyle(fontWeight: FontWeight.bold)),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Color _getColorForType(dynamic type) {
    switch (type) {
      case 'attendance':
        return const Color(0xFF2563EB);
      case 'announcement':
        return const Color(0xFFD97706);
      case 'system':
        return const Color(0xFF10B981);
      default:
        return const Color(0xFF1E40AF);
    }
  }

  IconData _getIconForType(dynamic type) {
    switch (type) {
      case 'attendance':
        return Icons.access_time_filled_rounded;
      case 'announcement':
        return Icons.campaign_rounded;
      case 'system':
        return Icons.cloud_sync_rounded;
      default:
        return Icons.notifications_rounded;
    }
  }

  String _formatTime(dynamic timeStr) {
    if (timeStr == null) return '';
    try {
      final dt = DateTime.parse(timeStr.toString());
      final now = DateTime.now();
      if (dt.year == now.year && dt.month == now.month && dt.day == now.day) {
        final hour = dt.hour.toString().padLeft(2, '0');
        final min = dt.minute.toString().padLeft(2, '0');
        return '$hour:$min WITA';
      }
      return '${dt.day}/${dt.month}/${dt.year}';
    } catch (_) {
      return '';
    }
  }

  @override
  Widget build(BuildContext context) {
    final filtered = _notifications.where((n) {
      if (_selectedCategory == 'Semua') return true;
      return (n['category']?.toString().toLowerCase() == _selectedCategory.toLowerCase());
    }).toList();

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        backgroundColor: const Color(0xFF0F172A),
        elevation: 0,
        title: const Text(
          'Notifikasi & Pengumuman',
          style: TextStyle(
            color: Colors.white,
            fontSize: 17,
            fontWeight: FontWeight.bold,
          ),
        ),
        actions: [
          IconButton(
            tooltip: 'Tandai Semua Dibaca',
            icon: const Icon(Icons.done_all_rounded, color: Colors.white70),
            onPressed: _notifications.isEmpty ? null : _markAllAsRead,
          ),
        ],
      ),
      body: Column(
        children: [
          // Filter Chips
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            color: Colors.white,
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                children: _categories.map((cat) {
                  final isSelected = _selectedCategory == cat;
                  return Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: ChoiceChip(
                      label: Text(
                        cat,
                        style: TextStyle(
                          color: isSelected ? Colors.white : const Color(0xFF475569),
                          fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                          fontSize: 12,
                        ),
                      ),
                      selected: isSelected,
                      selectedColor: const Color(0xFF1E40AF),
                      backgroundColor: const Color(0xFFF1F5F9),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                      side: BorderSide.none,
                      onSelected: (_) => setState(() => _selectedCategory = cat),
                    ),
                  );
                }).toList(),
              ),
            ),
          ),

          const Divider(height: 1, thickness: 1, color: Color(0xFFE2E8F0)),

          // List Content
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : filtered.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.notifications_off_outlined, size: 56, color: Colors.grey.shade400),
                            const SizedBox(height: 12),
                            Text(
                              'Tidak ada notifikasi',
                              style: TextStyle(
                                fontSize: 15,
                                color: Colors.grey.shade600,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                          ],
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: _loadNotifications,
                        child: ListView.separated(
                          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
                          itemCount: filtered.length,
                          separatorBuilder: (_, __) => const SizedBox(height: 10),
                          itemBuilder: (ctx, index) {
                            final notif = filtered[index];
                            final id = notif['id']?.toString() ?? '';
                            final isRead = _readNotificationIds.contains(id) || (notif['is_read'] == true);
                            final typeColor = _getColorForType(notif['type']);

                            return InkWell(
                              onTap: () => _showDetail(notif),
                              borderRadius: BorderRadius.circular(14),
                              child: Container(
                                padding: const EdgeInsets.all(14),
                                decoration: BoxDecoration(
                                  color: isRead ? Colors.white : const Color(0xFFF0F7FF),
                                  borderRadius: BorderRadius.circular(14),
                                  border: Border.all(
                                    color: isRead ? const Color(0xFFE2E8F0) : const Color(0xFFBFDBFE),
                                    width: isRead ? 1 : 1.5,
                                  ),
                                  boxShadow: [
                                    BoxShadow(
                                      color: Colors.black.withOpacity(0.02),
                                      blurRadius: 4,
                                      offset: const Offset(0, 2),
                                    ),
                                  ],
                                ),
                                child: Row(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Container(
                                      width: 42,
                                      height: 42,
                                      decoration: BoxDecoration(
                                        color: typeColor.withOpacity(0.12),
                                        borderRadius: BorderRadius.circular(12),
                                      ),
                                      child: Icon(_getIconForType(notif['type']), color: typeColor, size: 22),
                                    ),
                                    const SizedBox(width: 12),
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Row(
                                            children: [
                                              Expanded(
                                                child: Text(
                                                  notif['title'] ?? 'Notifikasi',
                                                  style: TextStyle(
                                                    fontWeight: isRead ? FontWeight.w600 : FontWeight.bold,
                                                    fontSize: 14,
                                                    color: const Color(0xFF0F172A),
                                                  ),
                                                ),
                                              ),
                                              if (!isRead)
                                                Container(
                                                  width: 8,
                                                  height: 8,
                                                  decoration: const BoxDecoration(
                                                    color: Color(0xFFEF4444),
                                                    shape: BoxShape.circle,
                                                  ),
                                                ),
                                            ],
                                          ),
                                          const SizedBox(height: 4),
                                          Text(
                                            notif['message'] ?? '',
                                            maxLines: 2,
                                            overflow: TextOverflow.ellipsis,
                                            style: const TextStyle(
                                              fontSize: 12,
                                              color: Color(0xFF64748B),
                                              height: 1.3,
                                            ),
                                          ),
                                          const SizedBox(height: 6),
                                          Text(
                                            _formatTime(notif['created_at']),
                                            style: TextStyle(
                                              fontSize: 11,
                                              color: Colors.grey.shade400,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            );
                          },
                        ),
                      ),
          ),
        ],
      ),
    );
  }
}
