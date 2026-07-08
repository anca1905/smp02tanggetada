import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../providers/data_provider.dart';
import '../theme/app_theme.dart';
import '../utils/constants.dart';

class MaterialScreen extends StatefulWidget {
  const MaterialScreen({Key? key}) : super(key: key);

  @override
  _MaterialScreenState createState() => _MaterialScreenState();
}

class _MaterialScreenState extends State<MaterialScreen> {
  bool _isInit = true;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_isInit) {
      Provider.of<DataProvider>(context, listen: false).fetchMaterials();
      _isInit = false;
    }
  }

  Future<void> _openFile(String? filePath) async {
    if (filePath == null || filePath.isEmpty) return;
    
    // Asumsi file tersimpan di storage/app/public/materials dan bisa diakses via URL
    final urlString = '${Constants.baseUrl.replaceAll('/api', '')}/storage/$filePath';
    final url = Uri.parse(urlString);
    
    if (await canLaunchUrl(url)) {
      await launchUrl(url, mode: LaunchMode.externalApplication);
    } else {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Tidak dapat membuka file materi.')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final dataProvider = Provider.of<DataProvider>(context);
    final materials = dataProvider.allMaterials;

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      appBar: AppBar(
        title: const Text('Materi Pelajaran'),
        elevation: 0,
      ),
      body: dataProvider.isLoadingMaterials
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: () => dataProvider.fetchMaterials(),
              child: materials.isEmpty
                  ? Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.folder_off_outlined,
                              size: 80, color: Colors.grey.shade300),
                          const SizedBox(height: 16),
                          const Text('Belum ada materi dibagikan.',
                              style: TextStyle(color: Colors.grey, fontSize: 16)),
                        ],
                      ),
                    )
                  : ListView.builder(
                      padding: const EdgeInsets.all(16),
                      itemCount: materials.length,
                      itemBuilder: (context, index) {
                        final mat = materials[index];
                        final subject = mat['schedule']?['subject']?['name'] ?? 'Mapel';
                        final teacher = mat['schedule']?['teacher']?['name'] ?? 'Guru';
                        
                        return Container(
                          margin: const EdgeInsets.only(bottom: 16),
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
                          child: Padding(
                            padding: const EdgeInsets.all(16),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  children: [
                                    Container(
                                      padding: const EdgeInsets.all(8),
                                      decoration: BoxDecoration(
                                        color: const Color(0xFF06B6D4).withOpacity(0.1),
                                        borderRadius: BorderRadius.circular(10),
                                      ),
                                      child: const Icon(Icons.book, color: Color(0xFF06B6D4), size: 20),
                                    ),
                                    const SizedBox(width: 12),
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            subject,
                                            style: const TextStyle(
                                              fontWeight: FontWeight.bold,
                                              color: Color(0xFF06B6D4),
                                              fontSize: 13,
                                            ),
                                          ),
                                          Text(
                                            'Oleh: $teacher',
                                            style: TextStyle(
                                                fontSize: 11,
                                                color: Colors.grey.shade500),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 14),
                                Text(
                                  mat['title'] ?? 'Materi',
                                  style: const TextStyle(
                                      fontSize: 16,
                                      fontWeight: FontWeight.bold,
                                      color: Color(0xFF1A1A2E)),
                                ),
                                if (mat['description'] != null && mat['description'].toString().isNotEmpty) ...[
                                  const SizedBox(height: 6),
                                  Text(
                                    mat['description'],
                                    style: TextStyle(
                                        color: Colors.grey.shade600, fontSize: 13),
                                  ),
                                ],
                                const SizedBox(height: 14),
                                if (mat['file_path'] != null)
                                  SizedBox(
                                    width: double.infinity,
                                    child: OutlinedButton.icon(
                                      onPressed: () => _openFile(mat['file_path']),
                                      icon: const Icon(Icons.download_rounded, size: 18),
                                      label: const Text('Download Materi'),
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
                            ),
                          ),
                        );
                      },
                    ),
            ),
    );
  }
}
