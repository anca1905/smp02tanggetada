import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/data_provider.dart';
import '../theme/app_theme.dart';
import 'assignment_detail_screen.dart';

class AssignmentScreen extends StatefulWidget {
  const AssignmentScreen({Key? key}) : super(key: key);

  @override
  _AssignmentScreenState createState() => _AssignmentScreenState();
}

class _AssignmentScreenState extends State<AssignmentScreen> {
  bool _isInit = true;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_isInit) {
      Provider.of<DataProvider>(context, listen: false).fetchAssignments();
      _isInit = false;
    }
  }

  bool _isPastDue(String? dueDateStr) {
    if (dueDateStr == null) return false;
    try {
      final dueDate = DateTime.parse(dueDateStr);
      return dueDate.isBefore(DateTime.now());
    } catch (_) {
      return false;
    }
  }

  String _formatDate(String? dateStr) {
    if (dateStr == null) return '-';
    try {
      final date = DateTime.parse(dateStr);
      const months = [
        '', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
      ];
      return '${date.day} ${months[date.month]} ${date.year}';
    } catch (_) {
      return dateStr;
    }
  }

  @override
  Widget build(BuildContext context) {
    final dataProvider = Provider.of<DataProvider>(context);

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      appBar: AppBar(
        title: const Text('Daftar Tugas'),
        elevation: 0,
      ),
      body: dataProvider.isLoadingAssignments
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: () => dataProvider.fetchAssignments(),
              child: dataProvider.allAssignments.isEmpty
                  ? Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.assignment_outlined,
                              size: 80, color: Colors.grey.shade300),
                          const SizedBox(height: 16),
                          const Text('Belum ada tugas.',
                              style: TextStyle(color: Colors.grey, fontSize: 16)),
                        ],
                      ),
                    )
                  : ListView.builder(
                      padding: const EdgeInsets.all(16),
                      itemCount: dataProvider.allAssignments.length,
                      itemBuilder: (context, index) {
                        final assignment = dataProvider.allAssignments[index];
                        final pastDue = _isPastDue(assignment['due_date']);
                        final isSubmitted = (assignment['submissions'] != null && assignment['submissions'].isNotEmpty);
                        
                        Color statusColor;
                        String statusText;
                        IconData statusIcon;

                        if (isSubmitted) {
                          statusColor = Colors.green;
                          statusText = 'Sudah Dikerjakan';
                          statusIcon = Icons.check_circle;
                        } else if (pastDue) {
                          statusColor = Colors.red;
                          statusText = 'Lewat Tenggat';
                          statusIcon = Icons.warning_amber;
                        } else {
                          statusColor = Colors.orange;
                          statusText = 'Belum Dikerjakan';
                          statusIcon = Icons.pending_actions;
                        }

                        return GestureDetector(
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => AssignmentDetailScreen(assignment: assignment),
                              ),
                            );
                          },
                          child: Container(
                            margin: const EdgeInsets.only(bottom: 14),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(16),
                              border: (pastDue && !isSubmitted)
                                  ? Border.all(color: Colors.red.shade200, width: 1)
                                  : null,
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withOpacity(0.05),
                                  blurRadius: 8,
                                  offset: const Offset(0, 2),
                                )
                              ],
                            ),
                            child: Padding(
                              padding: const EdgeInsets.all(16),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  // Header Row
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                    children: [
                                      Row(
                                        children: [
                                          Container(
                                            padding: const EdgeInsets.all(6),
                                            decoration: BoxDecoration(
                                              color: AppTheme.primaryColor.withOpacity(0.1),
                                              borderRadius: BorderRadius.circular(8),
                                            ),
                                            child: Icon(Icons.book_outlined,
                                                size: 16, color: AppTheme.primaryColor),
                                          ),
                                          const SizedBox(width: 8),
                                          Text(
                                            assignment['subject']?['name'] ?? 'Mapel',
                                            style: TextStyle(
                                              color: AppTheme.primaryColor,
                                              fontWeight: FontWeight.w600,
                                              fontSize: 13,
                                            ),
                                          ),
                                        ],
                                      ),
                                      Container(
                                        padding: const EdgeInsets.symmetric(
                                            horizontal: 10, vertical: 4),
                                        decoration: BoxDecoration(
                                          color: statusColor.withOpacity(0.1),
                                          borderRadius: BorderRadius.circular(20),
                                        ),
                                        child: Row(
                                          children: [
                                            Icon(statusIcon,
                                                size: 12, color: statusColor),
                                            const SizedBox(width: 4),
                                            Text(
                                              statusText,
                                              style: TextStyle(
                                                color: statusColor,
                                                fontSize: 11,
                                                fontWeight: FontWeight.w600,
                                              ),
                                            ),
                                          ],
                                        ),
                                      ),
                                    ],
                                  ),
                                  const SizedBox(height: 10),
                                  // Judul Tugas
                                  Text(
                                    assignment['title'] ?? 'Tugas',
                                    style: const TextStyle(
                                      fontSize: 16,
                                      fontWeight: FontWeight.bold,
                                      color: Color(0xFF1A1A2E),
                                    ),
                                  ),
                                  if (assignment['description'] != null &&
                                      assignment['description'].toString().isNotEmpty) ...[
                                    const SizedBox(height: 6),
                                    Text(
                                      assignment['description'],
                                      style: TextStyle(
                                          color: Colors.grey.shade600, fontSize: 13),
                                      maxLines: 2,
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                  ],
                                  const SizedBox(height: 12),
                                  // Footer: tenggat
                                  Container(
                                    padding: const EdgeInsets.symmetric(
                                        horizontal: 12, vertical: 8),
                                    decoration: BoxDecoration(
                                      color: (pastDue && !isSubmitted)
                                          ? Colors.red.shade50
                                          : Colors.grey.shade50,
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    child: Row(
                                      children: [
                                        Icon(Icons.calendar_today,
                                            size: 14,
                                            color: (pastDue && !isSubmitted)
                                                ? Colors.red
                                                : Colors.grey.shade600),
                                        const SizedBox(width: 6),
                                        Text(
                                          'Tenggat: ${_formatDate(assignment['due_date'])}',
                                          style: TextStyle(
                                            color: (pastDue && !isSubmitted)
                                                ? Colors.red
                                                : Colors.grey.shade700,
                                            fontSize: 12,
                                            fontWeight: FontWeight.w500,
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
                      },
                    ),
            ),
    );
  }
}
