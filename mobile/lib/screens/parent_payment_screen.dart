import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import 'parent_checkout_screen.dart';
import 'package:dio/dio.dart';

class ParentPaymentScreen extends StatefulWidget {
  const ParentPaymentScreen({Key? key}) : super(key: key);

  @override
  State<ParentPaymentScreen> createState() => _ParentPaymentScreenState();
}

class _ParentPaymentScreenState extends State<ParentPaymentScreen> {
  bool _isLoading = true;
  String _error = '';
  Map<String, dynamic> _billData = {};

  @override
  void initState() {
    super.initState();
    _fetchBills();
  }

  Future<void> _fetchBills() async {
    setState(() {
      _isLoading = true;
      _error = '';
    });
    
    try {
      final response = await ApiService().client.get('/student/bills');
      if (response.data['success']) {
        setState(() {
          _billData = response.data['data'];
          _isLoading = false;
        });
      } else {
        setState(() {
          _error = 'Gagal memuat data tagihan.';
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
          onRefresh: _fetchBills,
          child: SingleChildScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            child: Column(
              children: [
                _buildHeaderAndSelector(parentName, studentName, nis, classroom),
                const SizedBox(height: 24),
                
                if (_isLoading)
                  const Padding(padding: EdgeInsets.all(32), child: Center(child: CircularProgressIndicator()))
                else if (_error.isNotEmpty)
                  Padding(
                    padding: const EdgeInsets.all(32),
                    child: Center(
                      child: Column(
                        children: [
                          Text(_error, style: const TextStyle(color: Colors.red)),
                          TextButton(onPath: _fetchBills, child: const Text('Coba Lagi'))
                        ],
                      )
                    )
                  )
                else ...[
                  _buildSummaryCards(_billData['summary']),
                  const SizedBox(height: 24),
                  
                  _buildSectionTitle('Tagihan Aktif'),
                  const SizedBox(height: 12),
                  if ((_billData['active_bills'] as List).isEmpty)
                    _buildEmptyState('Tidak ada tagihan bulan ini.', Icons.sentiment_satisfied_alt)
                  else
                    ...(_billData['active_bills'] as List).map((bill) => _buildActiveBillCard(context, bill)).toList(),
                  
                  const SizedBox(height: 24),
                  _buildSectionTitle('Riwayat Pembayaran Terakhir', actionText: 'Lihat Semua'),
                  const SizedBox(height: 12),
                  if ((_billData['history_bills'] as List).isEmpty)
                    _buildEmptyState('Belum ada riwayat pembayaran.', Icons.history)
                  else
                    ...(_billData['history_bills'] as List).take(3).map((bill) => _buildHistoryBillRow(bill)).toList(),
                  
                  const SizedBox(height: 24),
                  _buildSectionTitle('Cara Pembayaran'),
                  const SizedBox(height: 12),
                  _buildPaymentMethods(),
                  const SizedBox(height: 24),
                  _buildPromoBanner(),
                  const SizedBox(height: 32),
                ]
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildEmptyState(String msg, IconData icon) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Center(
        child: Column(
          children: [
            Icon(icon, size: 48, color: Colors.grey.shade300),
            const SizedBox(height: 8),
            Text(msg, style: const TextStyle(color: Colors.grey, fontSize: 12)),
          ],
        ),
      ),
    );
  }

  Widget _buildHeaderAndSelector(String parentName, String studentName, String nis, String classroom) {
    return Stack(
      clipBehavior: Clip.none,
      children: [
        Container(
          padding: const EdgeInsets.only(left: 16, right: 16, top: 20, bottom: 60),
          decoration: const BoxDecoration(color: Color(0xFF1E56A0)),
          child: Row(
            children: [
              const CircleAvatar(
                radius: 24,
                backgroundImage: NetworkImage('https://ui-avatars.com/api/?background=random&color=fff'), 
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Pembayaran', style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 2),
                    Text(parentName, style: const TextStyle(color: Colors.white70, fontSize: 14)),
                    const Text('Orang Tua / Wali', style: TextStyle(color: Colors.white60, fontSize: 11)),
                  ],
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                decoration: BoxDecoration(color: Colors.white.withOpacity(0.15), borderRadius: BorderRadius.circular(20)),
                child: const Row(
                  children: [
                    Icon(Icons.calendar_today, color: Colors.white, size: 14),
                    SizedBox(width: 4),
                    Text('Riwayat', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
                  ],
                ),
              )
            ],
          ),
        ),
        Positioned(
          bottom: -25,
          left: 16,
          right: 16,
          child: Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, 5))],
            ),
            child: Row(
              children: [
                CircleAvatar(
                  radius: 20,
                  backgroundColor: Colors.blue.shade100,
                  child: const Icon(Icons.person, color: Colors.blue), 
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(studentName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14), maxLines: 1, overflow: TextOverflow.ellipsis),
                      Text('Kelas $classroom • NIS $nis', style: const TextStyle(color: Colors.grey, fontSize: 11), maxLines: 1, overflow: TextOverflow.ellipsis),
                    ],
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade300), borderRadius: BorderRadius.circular(8)),
                  child: const Row(
                    children: [
                      Text('Anak', style: TextStyle(fontSize: 10, color: Colors.grey)),
                      SizedBox(width: 4),
                      Icon(Icons.keyboard_arrow_down, color: Colors.grey, size: 14),
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

  Widget _buildSummaryCards(Map<String, dynamic> summary) {
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: const EdgeInsets.symmetric(horizontal: 16),
      physics: const BouncingScrollPhysics(),
      child: Row(
        children: [
          _buildSummaryCard('Sisa Tagihan', summary['sisa_tagihan'], '${summary['tagihan_belum_dibayar']} Tagihan Belum Dibayar', Icons.receipt_long, Colors.red),
          _buildSummaryCard('Total Terbayar', summary['total_terbayar'], '${summary['transaksi_berhasil']} Transaksi', Icons.check_circle_outline, Colors.green),
          _buildSummaryCard('Status Pembayaran', summary['status'], summary['tagihan_belum_dibayar'] == 0 ? 'Tidak ada tunggakan' : 'Segera lunasi', Icons.verified_user, summary['tagihan_belum_dibayar'] == 0 ? Colors.teal : Colors.orange),
        ],
      ),
    );
  }

  Widget _buildSummaryCard(String title, String value, String subtitle, IconData icon, Color color) {
    return Container(
      width: 140,
      margin: const EdgeInsets.only(right: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: const TextStyle(fontSize: 10, color: Colors.grey)),
          const SizedBox(height: 8),
          Text(value, style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: color)),
          const SizedBox(height: 4),
          Text(subtitle, style: const TextStyle(fontSize: 9, color: Colors.grey)),
          const SizedBox(height: 12),
          Container(
            padding: const EdgeInsets.all(6),
            decoration: BoxDecoration(color: color.withOpacity(0.1), shape: BoxShape.circle),
            child: Icon(icon, color: color, size: 16),
          )
        ],
      ),
    );
  }

  Widget _buildSectionTitle(String title, {String? actionText}) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
          if (actionText != null)
            Row(
              children: [
                Text(actionText, style: TextStyle(color: Colors.blue.shade700, fontSize: 12, fontWeight: FontWeight.bold)),
                Icon(Icons.keyboard_arrow_down, color: Colors.blue.shade700, size: 16),
              ],
            )
        ],
      ),
    );
  }

  Widget _buildActiveBillCard(BuildContext context, Map<String, dynamic> bill) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: Colors.red.shade100, width: 2),
          boxShadow: [BoxShadow(color: Colors.red.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, 4))],
        ),
        child: Column(
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(color: Colors.red.shade50, borderRadius: BorderRadius.circular(12)),
                  child: const Icon(Icons.account_balance_wallet, color: Colors.red),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(bill['title'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                      Text(bill['type'], style: const TextStyle(color: Colors.grey, fontSize: 11)),
                      const SizedBox(height: 6),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(color: Colors.red.shade50, borderRadius: BorderRadius.circular(12)),
                        child: Text(bill['status'], style: const TextStyle(color: Colors.red, fontSize: 9, fontWeight: FontWeight.bold)),
                      )
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            const Divider(),
            const SizedBox(height: 12),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Jumlah', style: TextStyle(color: Colors.grey, fontSize: 10)),
                    Text(bill['amount'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.red)),
                  ],
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Jatuh Tempo', style: TextStyle(color: Colors.grey, fontSize: 10)),
                    Text(bill['due_date'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: Colors.red)),
                  ],
                ),
                ElevatedButton.icon(
                  onPressed: () {
                    Navigator.push(context, MaterialPageRoute(builder: (context) => const ParentCheckoutScreen()));
                  },
                  icon: const Icon(Icons.payment, size: 14),
                  label: const Text('Bayar', style: TextStyle(fontSize: 12)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blue.shade700,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                  ),
                )
              ],
            )
          ],
        ),
      ),
    );
  }

  Widget _buildHistoryBillRow(Map<String, dynamic> bill) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Container(
        margin: const EdgeInsets.only(bottom: 12),
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 5, offset: const Offset(0, 2))],
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(color: Colors.green.withOpacity(0.1), borderRadius: BorderRadius.circular(8)),
              child: const Icon(Icons.check_circle, color: Colors.green, size: 20),
            ),
            const SizedBox(width: 12),
            Expanded(
              flex: 2,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(bill['title'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12), maxLines: 1, overflow: TextOverflow.ellipsis),
                  Text(bill['type'], style: const TextStyle(color: Colors.grey, fontSize: 9)),
                ],
              ),
            ),
            Expanded(
              flex: 2,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Tgl Bayar', style: TextStyle(color: Colors.grey, fontSize: 9)),
                  Text(bill['paid_at'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 10)),
                ],
              ),
            ),
            Expanded(
              flex: 2,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Jumlah', style: TextStyle(color: Colors.grey, fontSize: 9)),
                  Text(bill['amount'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 11)),
                ],
              ),
            ),
            OutlinedButton.icon(
              onPressed: () {},
              icon: const Icon(Icons.receipt, size: 12),
              label: const Text('Struk', style: TextStyle(fontSize: 10)),
              style: OutlinedButton.styleFrom(
                padding: const EdgeInsets.symmetric(horizontal: 8),
                side: BorderSide(color: Colors.blue.shade200),
                foregroundColor: Colors.blue.shade700,
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildPaymentMethods() {
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: const EdgeInsets.symmetric(horizontal: 16),
      physics: const BouncingScrollPhysics(),
      child: Row(
        children: [
          _buildMethodCard('Virtual Account', 'BCA, BNI, BRI, Mandiri', Icons.account_balance, Colors.blue),
          _buildMethodCard('E-Wallet', 'OVO, DANA, GoPay', Icons.account_balance_wallet, Colors.purple),
          _buildMethodCard('QRIS', 'Scan QR untuk bayar', Icons.qr_code_scanner, Colors.green),
          _buildMethodCard('Gerai Retail', 'Alfamart, Indomaret', Icons.storefront, Colors.orange),
        ],
      ),
    );
  }

  Widget _buildMethodCard(String title, String subtitle, IconData icon, Color color) {
    return Container(
      width: 120,
      margin: const EdgeInsets.only(right: 12),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: color.withOpacity(0.05),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: color.withOpacity(0.2)),
      ),
      child: Column(
        children: [
          Icon(icon, color: color, size: 28),
          const SizedBox(height: 12),
          Text(title, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 11, color: color), textAlign: TextAlign.center),
          const SizedBox(height: 4),
          Text(subtitle, style: const TextStyle(fontSize: 9, color: Colors.black54), textAlign: TextAlign.center, maxLines: 2, overflow: TextOverflow.ellipsis),
        ],
      ),
    );
  }

  Widget _buildPromoBanner() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(color: Colors.blue.shade50, borderRadius: BorderRadius.circular(12)),
        child: Row(
          children: [
            Icon(Icons.info_outline, color: Colors.blue.shade700, size: 24),
            const SizedBox(width: 12),
            const Expanded(
              child: Text(
                'Pembayaran SPP tepat waktu membantu sekolah meningkatkan kualitas layanan pendidikan.',
                style: TextStyle(color: Colors.blueGrey, fontSize: 11),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
