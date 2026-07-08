import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:file_picker/file_picker.dart';
import 'package:url_launcher/url_launcher.dart';
import '../providers/data_provider.dart';
import '../theme/app_theme.dart';
import '../utils/constants.dart';

class AssignmentDetailScreen extends StatefulWidget {
  final Map<String, dynamic> assignment;

  const AssignmentDetailScreen({Key? key, required this.assignment}) : super(key: key);

  @override
  _AssignmentDetailScreenState createState() => _AssignmentDetailScreenState();
}

class _AssignmentDetailScreenState extends State<AssignmentDetailScreen> {
  final TextEditingController _noteController = TextEditingController();
  File? _selectedFile;
  bool _isLoading = false;

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

  Future<void> _openFile(String? filePath) async {
    if (filePath == null || filePath.isEmpty) return;
    final urlString = '${Constants.baseUrl.replaceAll('/api', '')}/storage/$filePath';
    final url = Uri.parse(urlString);
    
    if (await canLaunchUrl(url)) {
      await launchUrl(url, mode: LaunchMode.externalApplication);
    } else {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Tidak dapat membuka file.')),
        );
      }
    }
  }

  Future<void> _pickFile() async {
    FilePickerResult? result = await FilePicker.platform.pickFiles();
    if (result != null) {
      setState(() {
        _selectedFile = File(result.files.single.path!);
      });
    }
  }

  Future<void> _submitAssignment() async {
    if (_selectedFile == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Pilih file terlebih dahulu.')),
      );
      return;
    }

    setState(() {
      _isLoading = true;
    });

    final provider = Provider.of<DataProvider>(context, listen: false);
    bool success = await provider.submitAssignment(
      widget.assignment['id'],
      _selectedFile!.path,
      _noteController.text,
    );

    setState(() {
      _isLoading = false;
    });

    if (success) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Tugas berhasil dikumpulkan.')),
        );
        Navigator.pop(context); // Kembali ke layar daftar tugas yang akan me-refresh
      }
    } else {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(provider.errorMessage ?? 'Gagal mengumpulkan tugas.')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final assignment = widget.assignment;
    final pastDue = _isPastDue(assignment['due_date']);
    final isSubmitted = (assignment['submissions'] != null && assignment['submissions'].isNotEmpty);
    final submission = isSubmitted ? assignment['submissions'][0] : null;
    final subject = assignment['subject']?['name'] ?? 'Mata Pelajaran';
    final teacher = assignment['teacher']?['name'] ?? 'Guru';

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      appBar: AppBar(
        title: const Text('Detail Tugas'),
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Info Tugas
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.04),
                    blurRadius: 8,
                    offset: const Offset(0, 2),
                  )
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: AppTheme.primaryColor.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      subject,
                      style: TextStyle(
                        color: AppTheme.primaryColor,
                        fontWeight: FontWeight.w600,
                        fontSize: 12,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    assignment['title'] ?? 'Judul Tugas',
                    style: const TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF1A1A2E),
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Oleh: $teacher',
                    style: TextStyle(color: Colors.grey.shade600, fontSize: 13),
                  ),
                  const Divider(height: 32),
                  const Text(
                    'Deskripsi:',
                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    assignment['description'] ?? '-',
                    style: const TextStyle(fontSize: 14, height: 1.5, color: Color(0xFF444444)),
                  ),
                  const SizedBox(height: 20),
                  Row(
                    children: [
                      Icon(Icons.calendar_today, size: 16, color: pastDue && !isSubmitted ? Colors.red : Colors.grey.shade600),
                      const SizedBox(width: 8),
                      Text(
                        'Tenggat Waktu: ${_formatDate(assignment['due_date'])}',
                        style: TextStyle(
                          color: pastDue && !isSubmitted ? Colors.red : Colors.grey.shade800,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ],
                  ),
                  if (assignment['file_path'] != null) ...[
                    const SizedBox(height: 20),
                    SizedBox(
                      width: double.infinity,
                      child: OutlinedButton.icon(
                        onPressed: () => _openFile(assignment['file_path']),
                        icon: const Icon(Icons.download_rounded, size: 18),
                        label: const Text('Download Lampiran Guru'),
                        style: OutlinedButton.styleFrom(
                          foregroundColor: AppTheme.primaryColor,
                          side: BorderSide(color: AppTheme.primaryColor.withOpacity(0.5)),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(10),
                          ),
                        ),
                      ),
                    ),
                  ],
                ],
              ),
            ),

            const SizedBox(height: 20),

            // Form / Status Pengumpulan
            const Text(
              'Status Pengumpulan',
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: Color(0xFF1A1A2E)),
            ),
            const SizedBox(height: 10),
            
            if (isSubmitted) ...[
              // Tampilan Sudah Dikumpulkan
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: Colors.green.shade200, width: 2),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.04),
                      blurRadius: 8,
                      offset: const Offset(0, 2),
                    )
                  ],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.check_circle, color: Colors.green),
                        const SizedBox(width: 8),
                        const Text(
                          'Sudah Dikumpulkan',
                          style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold, fontSize: 16),
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    Text('Waktu Kumpul: ${_formatDate(submission['submitted_at'])}', style: const TextStyle(fontSize: 13)),
                    const SizedBox(height: 8),
                    Text('Catatan: ${submission['student_note'] ?? '-'}', style: const TextStyle(fontSize: 13)),
                    if (submission['score'] != null) ...[
                      const Divider(height: 32),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('Nilai:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                          Text(
                            submission['score'].toString(),
                            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 24, color: AppTheme.primaryColor),
                          ),
                        ],
                      ),
                      if (submission['teacher_feedback'] != null) ...[
                        const SizedBox(height: 8),
                        Text('Feedback Guru: ${submission['teacher_feedback']}'),
                      ]
                    ],
                    if (submission['file_path'] != null) ...[
                      const SizedBox(height: 20),
                      SizedBox(
                        width: double.infinity,
                        child: OutlinedButton.icon(
                          onPressed: () => _openFile(submission['file_path']),
                          icon: const Icon(Icons.file_present),
                          label: const Text('Lihat File yang Dikumpulkan'),
                          style: OutlinedButton.styleFrom(
                            foregroundColor: Colors.green,
                            side: BorderSide(color: Colors.green.shade300),
                          ),
                        ),
                      ),
                    ],
                  ],
                ),
              ),
            ] else ...[
              // Form Belum Dikumpulkan
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: pastDue ? Border.all(color: Colors.red.shade200, width: 2) : null,
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.04),
                      blurRadius: 8,
                      offset: const Offset(0, 2),
                    )
                  ],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    if (pastDue) ...[
                      const Row(
                        children: [
                          Icon(Icons.warning_amber, color: Colors.red),
                          SizedBox(width: 8),
                          Text(
                            'Tugas ini sudah lewat tenggat waktu.',
                            style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                    ],
                    const Text('Upload File Jawaban (Max 10MB)', style: TextStyle(fontWeight: FontWeight.w600)),
                    const SizedBox(height: 10),
                    InkWell(
                      onTap: _pickFile,
                      child: Container(
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: Colors.grey.shade50,
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: Colors.grey.shade300, style: BorderStyle.solid),
                        ),
                        child: Row(
                          children: [
                            Icon(Icons.upload_file, color: AppTheme.primaryColor),
                            const SizedBox(width: 12),
                            Expanded(
                              child: Text(
                                _selectedFile != null ? _selectedFile!.path.split('/').last : 'Pilih File...',
                                style: TextStyle(
                                  color: _selectedFile != null ? Colors.black87 : Colors.grey,
                                ),
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 16),
                    const Text('Catatan Tambahan (Opsional)', style: TextStyle(fontWeight: FontWeight.w600)),
                    const SizedBox(height: 8),
                    TextField(
                      controller: _noteController,
                      maxLines: 3,
                      decoration: InputDecoration(
                        hintText: 'Masukkan pesan atau link jika ada...',
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                          borderSide: BorderSide(color: Colors.grey.shade300),
                        ),
                        contentPadding: const EdgeInsets.all(12),
                      ),
                    ),
                    const SizedBox(height: 24),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _isLoading ? null : _submitAssignment,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppTheme.primaryColor,
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                          elevation: 0,
                        ),
                        child: _isLoading
                            ? const SizedBox(
                                width: 24,
                                height: 24,
                                child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                              )
                            : const Text('Kumpulkan Tugas', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white)),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }
}
