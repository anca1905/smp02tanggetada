import 'package:barcode_widget/barcode_widget.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class StudentCardScreen extends StatefulWidget {
  const StudentCardScreen({super.key});

  @override
  State<StudentCardScreen> createState() => _StudentCardScreenState();
}

class _StudentCardScreenState extends State<StudentCardScreen> {
  bool _showBack = true; // Default to showing back where the barcode is!

  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<AuthProvider>(context);
    final user = auth.user ?? {};

    final studentName = user['student_name'] ?? user['name'] ?? 'Siswa';
    final nis = user['nis']?.toString() ?? '120001';
    final nisn = user['nisn']?.toString() ?? '-';
    final classroom = user['classroom']?['name'] ?? user['homeroom_class'] ?? 'Kelas';
    final gender = user['gender'] == 'M' ? 'Laki-laki' : user['gender'] == 'F' ? 'Perempuan' : '-';
    final address = user['address'] ?? 'Tanggetada, Kolaka';

    return Scaffold(
      backgroundColor: const Color(0xFFF1F5F9), // Slate 100
      appBar: AppBar(
        backgroundColor: const Color(0xFF0F172A),
        elevation: 0,
        title: const Text(
          'Kartu Pelajar Digital',
          style: TextStyle(
            color: Colors.white,
            fontSize: 17,
            fontWeight: FontWeight.bold,
          ),
        ),
        actions: [
          IconButton(
            tooltip: _showBack ? 'Lihat Tampak Depan' : 'Lihat Tampak Belakang (Barcode)',
            icon: Icon(
              _showBack ? Icons.flip_to_front : Icons.flip_to_back,
              color: Colors.white,
            ),
            onPressed: () {
              setState(() => _showBack = !_showBack);
            },
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 20),
        child: Column(
          children: [
            // Instruction Banner
            Container(
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(
                color: const Color(0xFFEFF6FF), // Blue 50
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: const Color(0xFFBFDBFE)),
              ),
              child: Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: const Color(0xFF2563EB).withValues(alpha: 0.12),
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.qr_code_scanner, color: Color(0xFF2563EB), size: 22),
                  ),
                  const SizedBox(width: 12),
                  const Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Barcode Presensi Otomatis',
                          style: TextStyle(
                            fontSize: 13,
                            fontWeight: FontWeight.bold,
                            color: Color(0xFF1E3A8A),
                          ),
                        ),
                        SizedBox(height: 2),
                        Text(
                          'Tunjukkan barcode ini ke kamera HP guru untuk absen cepat tanpa perlu dipanggil satu per satu.',
                          style: TextStyle(fontSize: 11, color: Color(0xFF3B82F6), height: 1.3),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 18),

            // Toggle Tab (Depan / Belakang)
            Container(
              padding: const EdgeInsets.all(4),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(10),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Row(
                children: [
                  Expanded(
                    child: GestureDetector(
                      onTap: () => setState(() => _showBack = false),
                      child: Container(
                        padding: const EdgeInsets.symmetric(vertical: 8),
                        decoration: BoxDecoration(
                          color: !_showBack ? const Color(0xFF166534) : Colors.transparent,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Center(
                          child: Text(
                            'Tampak Depan',
                            style: TextStyle(
                              color: !_showBack ? Colors.white : const Color(0xFF64748B),
                              fontWeight: FontWeight.bold,
                              fontSize: 12,
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                  Expanded(
                    child: GestureDetector(
                      onTap: () => setState(() => _showBack = true),
                      child: Container(
                        padding: const EdgeInsets.symmetric(vertical: 8),
                        decoration: BoxDecoration(
                          color: _showBack ? const Color(0xFF166534) : Colors.transparent,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Center(
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(
                                Icons.barcode_reader,
                                size: 16,
                                color: _showBack ? Colors.white : const Color(0xFF64748B),
                              ),
                              const SizedBox(width: 6),
                              Text(
                                'Tampak Belakang (Barcode)',
                                style: TextStyle(
                                  color: _showBack ? Colors.white : const Color(0xFF64748B),
                                  fontWeight: FontWeight.bold,
                                  fontSize: 12,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 18),

            // The ID Card View
            AnimatedSwitcher(
              duration: const Duration(milliseconds: 300),
              transitionBuilder: (child, anim) => FadeTransition(opacity: anim, child: child),
              child: _showBack
                  ? _buildBackCard(nis)
                  : _buildFrontCard(studentName, nis, nisn, classroom, gender, address),
            ),

            const SizedBox(height: 20),

            // Card Action Buttons
            Row(
              children: [
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: () => setState(() => _showBack = !_showBack),
                    icon: const Icon(Icons.sync_alt, size: 18),
                    label: Text(_showBack ? 'Balik ke Depan' : 'Lihat Barcode'),
                    style: OutlinedButton.styleFrom(
                      foregroundColor: const Color(0xFF166534),
                      side: const BorderSide(color: Color(0xFF166534)),
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  // Tampak Depan
  Widget _buildFrontCard(
    String name,
    String nis,
    String nisn,
    String classroom,
    String gender,
    String address,
  ) {
    return Container(
      key: const ValueKey('front_card'),
      width: double.infinity,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.12),
            blurRadius: 16,
            offset: const Offset(0, 6),
          ),
        ],
        border: Border.all(color: const Color(0xFF166534), width: 1.5),
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(16),
        child: Column(
          children: [
            // Header Card
            Container(
              padding: const EdgeInsets.fromLTRB(14, 12, 14, 10),
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [Color(0xFF166534), Color(0xFF15803D)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
              ),
              child: Row(
                children: [
                  Container(
                    width: 38,
                    height: 38,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      shape: BoxShape.circle,
                      border: Border.all(color: const Color(0xFFEAB308), width: 1.5),
                    ),
                    child: const Icon(Icons.school, color: Color(0xFF166534), size: 22),
                  ),
                  const SizedBox(width: 10),
                  const Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'SMP NEGERI 2 TANGGETADA',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 13,
                            fontWeight: FontWeight.bold,
                            letterSpacing: 0.5,
                          ),
                        ),
                        Text(
                          'KARTU TANDA PELAJAR',
                          style: TextStyle(
                            color: Color(0xFFFDE047), // Gold
                            fontSize: 11,
                            fontWeight: FontWeight.w700,
                            letterSpacing: 0.8,
                          ),
                        ),
                        Text(
                          'Kec. Tanggetada, Kab. Kolaka, Sulawesi Tenggara',
                          style: TextStyle(color: Colors.white70, fontSize: 8),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            // Gold stripe
            Container(height: 3, color: const Color(0xFFEAB308)),

            // Body Card
            Padding(
              padding: const EdgeInsets.all(14),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Photo Avatar
                  Container(
                    width: 80,
                    height: 100,
                    decoration: BoxDecoration(
                      color: const Color(0xFFF1F5F9),
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(color: const Color(0xFFCBD5E1), width: 1.5),
                    ),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(Icons.person, size: 48, color: Color(0xFF64748B)),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 2),
                          decoration: BoxDecoration(
                            color: const Color(0xFF166534),
                            borderRadius: BorderRadius.circular(4),
                          ),
                          child: Text(
                            classroom,
                            style: const TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.bold),
                          ),
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(width: 14),

                  // Student Details
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          name,
                          style: const TextStyle(
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                            color: Color(0xFF0F172A),
                          ),
                        ),
                        const SizedBox(height: 6),
                        _buildDetailRow('NIS', nis),
                        _buildDetailRow('NISN', nisn),
                        _buildDetailRow('Kelas', classroom),
                        _buildDetailRow('Gender', gender),
                        _buildDetailRow('Alamat', address),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            // Footer Card
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
              color: const Color(0xFFF8FAFC),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'Berlaku Selama Menjadi Siswa',
                    style: TextStyle(fontSize: 9, color: Color(0xFF64748B), fontStyle: FontStyle.italic),
                  ),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      const Text(
                        'Kepala Sekolah',
                        style: TextStyle(fontSize: 9, fontWeight: FontWeight.bold, color: Color(0xFF334155)),
                      ),
                      Container(
                        margin: const EdgeInsets.only(top: 2),
                        height: 1,
                        width: 70,
                        color: const Color(0xFF94A3B8),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Tampak Belakang dengan Barcode
  Widget _buildBackCard(String nis) {
    return Container(
      key: const ValueKey('back_card'),
      width: double.infinity,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.12),
            blurRadius: 16,
            offset: const Offset(0, 6),
          ),
        ],
        border: Border.all(color: const Color(0xFF166534), width: 1.5),
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(16),
        child: Column(
          children: [
            // Top Bar
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
              color: const Color(0xFF166534),
              child: const Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.shield, color: Color(0xFFEAB308), size: 14),
                  SizedBox(width: 6),
                  Text(
                    'KETENTUAN PENGGUNAAN KARTU',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 11,
                      fontWeight: FontWeight.bold,
                      letterSpacing: 0.5,
                    ),
                  ),
                ],
              ),
            ),

            // Rules text
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 12, 16, 10),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildRuleItem('1.', 'Kartu ini berlaku selama pemilik berstatus sebagai siswa aktif.'),
                  _buildRuleItem('2.', 'Kartu tidak boleh dipindahtangankan kepada orang lain.'),
                  _buildRuleItem('3.', 'Gunakan barcode di bawah untuk absensi kehadiran harian (Apel, Kelas, Pulang).'),
                  _buildRuleItem('4.', 'Tunjukkan barcode ke kamera guru untuk absen instan tanpa panggil satu-satu.'),
                ],
              ),
            ),

            const Divider(height: 1, color: Color(0xFFE2E8F0)),

            // Big Barcode Box
            Container(
              margin: const EdgeInsets.all(14),
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: const Color(0xFF0F172A), width: 1.2),
              ),
              child: Column(
                children: [
                  // High contrast Barcode
                  BarcodeWidget(
                    barcode: Barcode.code128(),
                    data: nis,
                    width: double.infinity,
                    height: 75,
                    drawText: true,
                    style: const TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                      letterSpacing: 3,
                      color: Colors.black,
                    ),
                  ),
                ],
              ),
            ),

            // Bottom Info
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
              color: const Color(0xFFF8FAFC),
              child: const Center(
                child: Text(
                  'SMP NEGERI 2 TANGGETADA • SISTEM INFORMASI MANAJEMEN SEKOLAH',
                  style: TextStyle(fontSize: 8.5, color: Color(0xFF64748B), fontWeight: FontWeight.bold),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 3),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 48,
            child: Text(
              label,
              style: const TextStyle(fontSize: 10.5, color: Color(0xFF64748B)),
            ),
          ),
          const Text(': ', style: TextStyle(fontSize: 10.5, color: Color(0xFF64748B))),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                fontSize: 10.5,
                fontWeight: FontWeight.w600,
                color: Color(0xFF1E293B),
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildRuleItem(String num, String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(num, style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Color(0xFF166534))),
          const SizedBox(width: 6),
          Expanded(
            child: Text(
              text,
              style: const TextStyle(fontSize: 10, color: Color(0xFF475569), height: 1.3),
            ),
          ),
        ],
      ),
    );
  }
}
