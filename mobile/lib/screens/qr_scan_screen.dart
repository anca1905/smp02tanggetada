import 'package:flutter/material.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';

class QrScanScreen extends StatefulWidget {
  const QrScanScreen({Key? key}) : super(key: key);

  @override
  State<QrScanScreen> createState() => _QrScanScreenState();
}

class _QrScanScreenState extends State<QrScanScreen>
    with SingleTickerProviderStateMixin, WidgetsBindingObserver {
  final MobileScannerController _scannerController = MobileScannerController(
    detectionSpeed: DetectionSpeed.noDuplicates,
  );

  final ApiService _apiService = ApiService();

  bool _isProcessing = false;
  bool _isDone = false;
  String? _resultMessage;
  bool _isSuccess = false;

  late AnimationController _pulseController;
  late Animation<double> _pulseAnimation;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _pulseController = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 1),
    )..repeat(reverse: true);
    _pulseAnimation = Tween<double>(begin: 0.85, end: 1.0).animate(
      CurvedAnimation(parent: _pulseController, curve: Curves.easeInOut),
    );
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    super.didChangeAppLifecycleState(state);
    if (state == AppLifecycleState.resumed && !_isDone) {
      _scannerController.start();
    } else if (state == AppLifecycleState.paused) {
      _scannerController.stop();
    }
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    _scannerController.dispose();
    _pulseController.dispose();
    super.dispose();
  }

  Future<void> _onQrDetected(BarcodeCapture capture) async {
    if (_isProcessing || _isDone) return;

    final barcodes = capture.barcodes;
    if (barcodes.isEmpty) return;

    final raw = barcodes.first.rawValue;
    if (raw == null || raw.isEmpty) return;

    setState(() => _isProcessing = true);
    await _scannerController.stop();

    try {
      final response = await _apiService.client.post(
        '/student/attendance/checkin',
        data: {'qr_token': raw},
      );

      final data = response.data;
      setState(() {
        _isDone = true;
        _isSuccess = data['success'] == true;
        _resultMessage = data['message'] ?? 'Terjadi kesalahan.';
      });
    } catch (e) {
      String msg = 'Terjadi kesalahan koneksi.';
      if (e.toString().contains('422') || e.toString().contains('403')) {
        try {
          final dioErr = e as dynamic;
          msg = dioErr.response?.data?['message'] ?? msg;
        } catch (_) {}
      }
      setState(() {
        _isDone = true;
        _isSuccess = false;
        _resultMessage = msg;
      });
    } finally {
      setState(() => _isProcessing = false);
    }
  }

  void _retry() {
    setState(() {
      _isDone = false;
      _resultMessage = null;
      _isProcessing = false;
    });
    _scannerController.start();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios, color: Colors.white),
          onPressed: () => Navigator.pop(context),
        ),
        title: const Text(
          'Scan QR Presensi',
          style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.flash_on, color: Colors.white),
            onPressed: () => _scannerController.toggleTorch(),
          ),
        ],
      ),
      body: Stack(
        children: [
          // ── Kamera ──────────────────────────────────────────────
          if (!_isDone)
            MobileScanner(
              controller: _scannerController,
              onDetect: _onQrDetected,
              // v7: errorBuilder hanya 2 parameter
              errorBuilder: (context, error) {
                final isPermission =
                    error.errorCode == MobileScannerErrorCode.permissionDenied;
                return Container(
                  color: Colors.black,
                  child: Center(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 32),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Container(
                            width: 80,
                            height: 80,
                            decoration: BoxDecoration(
                              color: Colors.red.withOpacity(0.15),
                              shape: BoxShape.circle,
                            ),
                            child: Icon(
                              isPermission
                                  ? Icons.no_photography_outlined
                                  : Icons.camera_alt_outlined,
                              size: 40,
                              color: Colors.redAccent,
                            ),
                          ),
                          const SizedBox(height: 20),
                          Text(
                            isPermission
                                ? 'Izin Kamera Diperlukan'
                                : 'Kamera Tidak Dapat Dibuka',
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                            textAlign: TextAlign.center,
                          ),
                          const SizedBox(height: 12),
                          Text(
                            isPermission
                                ? 'Buka Pengaturan > Aplikasi > SIMS Mobile > Izin > Kamera untuk mengaktifkan.'
                                : 'Kode error: ${error.errorCode.name}\n${error.errorDetails?.message ?? ""}',
                            textAlign: TextAlign.center,
                            style: const TextStyle(
                              color: Colors.white60,
                              fontSize: 13,
                              height: 1.6,
                            ),
                          ),
                          const SizedBox(height: 24),
                          ElevatedButton.icon(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppTheme.primaryColor,
                              padding: const EdgeInsets.symmetric(
                                  horizontal: 28, vertical: 14),
                              shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(10)),
                            ),
                            icon: const Icon(Icons.refresh, color: Colors.white),
                            label: const Text(
                              'Coba Lagi',
                              style: TextStyle(color: Colors.white),
                            ),
                            onPressed: () async {
                              await _scannerController.stop();
                              await Future.delayed(
                                  const Duration(milliseconds: 500));
                              await _scannerController.start();
                            },
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              },
            ),

          // ── Overlay frame QR ─────────────────────────────────────
          if (!_isDone && !_isProcessing) _buildScanOverlay(),

          // ── Loading saat proses ──────────────────────────────────
          if (_isProcessing)
            Container(
              color: Colors.black87,
              child: const Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    CircularProgressIndicator(color: Colors.white),
                    SizedBox(height: 16),
                    Text(
                      'Memproses presensi...',
                      style: TextStyle(color: Colors.white, fontSize: 16),
                    ),
                  ],
                ),
              ),
            ),

          // ── Result card ──────────────────────────────────────────
          if (_isDone) _buildResultCard(),
        ],
      ),
    );
  }

  Widget _buildScanOverlay() {
    final size = MediaQuery.of(context).size;
    final boxSize = size.width * 0.7;

    return Column(
      children: [
        Expanded(
          child: Stack(
            children: [
              ColorFiltered(
                colorFilter: ColorFilter.mode(
                  Colors.black.withOpacity(0.6),
                  BlendMode.srcOut,
                ),
                child: Stack(
                  children: [
                    Container(
                      decoration: const BoxDecoration(
                        color: Colors.black,
                        backgroundBlendMode: BlendMode.dstOut,
                      ),
                    ),
                    Center(
                      child: Container(
                        width: boxSize,
                        height: boxSize,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              Center(
                child: AnimatedBuilder(
                  animation: _pulseAnimation,
                  builder: (context, child) => Transform.scale(
                    scale: _pulseAnimation.value,
                    child: SizedBox(
                      width: boxSize,
                      height: boxSize,
                      child: CustomPaint(
                        painter: _CornerPainter(
                            color: AppTheme.primaryColor, strokeWidth: 4),
                      ),
                    ),
                  ),
                ),
              ),
              Align(
                alignment: const Alignment(0, 0.55),
                child: Container(
                  padding: const EdgeInsets.symmetric(
                      horizontal: 20, vertical: 8),
                  decoration: BoxDecoration(
                    color: Colors.black54,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: const Text(
                    'Arahkan kamera ke QR Code',
                    style: TextStyle(color: Colors.white, fontSize: 14),
                  ),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildResultCard() {
    return Container(
      color: _isSuccess ? const Color(0xFF0F172A) : const Color(0xFF1A0000),
      child: Center(
        child: Padding(
          padding: const EdgeInsets.all(32),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                width: 100,
                height: 100,
                decoration: BoxDecoration(
                  color: (_isSuccess ? Colors.green : Colors.red)
                      .withOpacity(0.15),
                  shape: BoxShape.circle,
                ),
                child: Icon(
                  _isSuccess
                      ? Icons.check_circle_rounded
                      : Icons.error_rounded,
                  size: 60,
                  color: _isSuccess ? Colors.greenAccent : Colors.redAccent,
                ),
              ),
              const SizedBox(height: 24),
              Text(
                _isSuccess ? 'Presensi Berhasil!' : 'Gagal Check-in',
                style: TextStyle(
                  color: _isSuccess ? Colors.greenAccent : Colors.redAccent,
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 12),
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.07),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(
                  _resultMessage ?? '',
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    color: Colors.white70,
                    fontSize: 15,
                    height: 1.5,
                  ),
                ),
              ),
              const SizedBox(height: 32),
              if (!_isSuccess) ...[
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppTheme.primaryColor,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12)),
                    ),
                    icon: const Icon(Icons.qr_code_scanner, color: Colors.white),
                    label: const Text(
                      'Scan Ulang',
                      style: TextStyle(
                          color: Colors.white,
                          fontSize: 16,
                          fontWeight: FontWeight.bold),
                    ),
                    onPressed: _retry,
                  ),
                ),
                const SizedBox(height: 12),
              ],
              SizedBox(
                width: double.infinity,
                child: OutlinedButton(
                  style: OutlinedButton.styleFrom(
                    side: const BorderSide(color: Colors.white30),
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12)),
                  ),
                  child: const Text(
                    'Kembali',
                    style: TextStyle(color: Colors.white70, fontSize: 16),
                  ),
                  onPressed: () => Navigator.pop(context),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _CornerPainter extends CustomPainter {
  final Color color;
  final double strokeWidth;

  const _CornerPainter({required this.color, required this.strokeWidth});

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = color
      ..strokeWidth = strokeWidth
      ..style = PaintingStyle.stroke
      ..strokeCap = StrokeCap.round;

    const cornerLen = 28.0;
    const r = 12.0;

    canvas.drawPath(
        Path()
          ..moveTo(0, cornerLen)
          ..lineTo(0, r)
          ..arcToPoint(Offset(r, 0),
              radius: const Radius.circular(r), clockwise: true)
          ..lineTo(cornerLen, 0),
        paint);
    canvas.drawPath(
        Path()
          ..moveTo(size.width - cornerLen, 0)
          ..lineTo(size.width - r, 0)
          ..arcToPoint(Offset(size.width, r),
              radius: const Radius.circular(r), clockwise: true)
          ..lineTo(size.width, cornerLen),
        paint);
    canvas.drawPath(
        Path()
          ..moveTo(0, size.height - cornerLen)
          ..lineTo(0, size.height - r)
          ..arcToPoint(Offset(r, size.height),
              radius: const Radius.circular(r), clockwise: false)
          ..lineTo(cornerLen, size.height),
        paint);
    canvas.drawPath(
        Path()
          ..moveTo(size.width - cornerLen, size.height)
          ..lineTo(size.width - r, size.height)
          ..arcToPoint(Offset(size.width, size.height - r),
              radius: const Radius.circular(r), clockwise: false)
          ..lineTo(size.width, size.height - cornerLen),
        paint);
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
