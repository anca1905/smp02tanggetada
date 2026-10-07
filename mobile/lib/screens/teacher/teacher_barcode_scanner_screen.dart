import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import 'package:provider/provider.dart';
import '../../providers/teacher_provider.dart';
import '../../services/api_service.dart';

class TeacherBarcodeScannerScreen extends StatefulWidget {
  final String sessionType;
  final String className;
  final dynamic classId;
  final dynamic subjectId;
  final DateTime date;

  const TeacherBarcodeScannerScreen({
    super.key,
    required this.sessionType,
    required this.className,
    required this.classId,
    required this.date,
    this.subjectId,
  });

  @override
  State<TeacherBarcodeScannerScreen> createState() => _TeacherBarcodeScannerScreenState();
}

class _TeacherBarcodeScannerScreenState extends State<TeacherBarcodeScannerScreen>
    with SingleTickerProviderStateMixin, WidgetsBindingObserver {
  final MobileScannerController _controller = MobileScannerController(
    detectionSpeed: DetectionSpeed.normal,
  );
  final ApiService _apiService = ApiService();

  late AnimationController _laserController;
  late Animation<double> _laserAnimation;

  // Scan cooldown & history
  String? _lastScannedNis;
  DateTime? _lastScannedTime;
  final List<Map<String, String>> _recentScans = [];

  // Feedback banner state
  String? _feedbackMessage;
  Color _feedbackColor = Colors.green;
  IconData _feedbackIcon = Icons.check_circle;
  Timer? _feedbackTimer;

  bool _isTorchOn = false;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);

    _laserController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1800),
    )..repeat(reverse: true);

    _laserAnimation = Tween<double>(begin: 0.1, end: 0.9).animate(
      CurvedAnimation(parent: _laserController, curve: Curves.easeInOut),
    );
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    super.didChangeAppLifecycleState(state);
    if (state == AppLifecycleState.resumed) {
      _controller.start();
    } else if (state == AppLifecycleState.paused) {
      _controller.stop();
    }
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    _feedbackTimer?.cancel();
    _laserController.dispose();
    _controller.dispose();
    super.dispose();
  }

  void _showFeedback(String message, Color color, IconData icon) {
    _feedbackTimer?.cancel();
    setState(() {
      _feedbackMessage = message;
      _feedbackColor = color;
      _feedbackIcon = icon;
    });
    _feedbackTimer = Timer(const Duration(seconds: 3), () {
      if (mounted) {
        setState(() => _feedbackMessage = null);
      }
    });
  }

  Future<void> _handleBarcodeDetected(BarcodeCapture capture) async {
    final barcodes = capture.barcodes;
    if (barcodes.isEmpty) return;

    final raw = barcodes.first.rawValue?.trim();
    if (raw == null || raw.isEmpty) return;

    // Debounce duplicate scans within 1.5 seconds for same student
    final now = DateTime.now();
    if (_lastScannedNis == raw &&
        _lastScannedTime != null &&
        now.difference(_lastScannedTime!).inMilliseconds < 1800) {
      return;
    }

    _lastScannedNis = raw;
    _lastScannedTime = now;

    final teacher = Provider.of<TeacherProvider>(context, listen: false);
    final students = teacher.currentStudents;

    // Search student in current class
    final student = students.firstWhere(
      (s) => (s['nis']?.toString() == raw || s['nisn']?.toString() == raw),
      orElse: () => null,
    );

    if (student == null) {
      HapticFeedback.heavyImpact();
      _showFeedback(
        'NIS $raw tidak terdaftar di kelas ${widget.className}!',
        const Color(0xFFEF4444),
        Icons.cancel_rounded,
      );
      return;
    }

    final studentName = student['student_name'] ?? 'Siswa';
    final nis = student['nis']?.toString() ?? raw;
    final currentStatus = teacher.attendanceMap[nis];

    // Play feedback sound and haptic vibration
    SystemSound.play(SystemSoundType.click);
    HapticFeedback.mediumImpact();

    final timeStr =
        '${now.hour.toString().padLeft(2, '0')}:${now.minute.toString().padLeft(2, '0')}:${now.second.toString().padLeft(2, '0')}';

    if (currentStatus == 'present') {
      _showFeedback(
        '$studentName ($nis) — Sudah Hadir Sebelumnya',
        const Color(0xFFF59E0B),
        Icons.info_outline_rounded,
      );
    } else {
      // Mark as present in local state
      teacher.setStudentStatus(nis, 'present');

      setState(() {
        _recentScans.insert(0, {
          'name': studentName,
          'nis': nis,
          'time': timeStr,
        });
      });

      _showFeedback(
        '$studentName ($nis) — HADIR',
        const Color(0xFF10B981),
        Icons.check_circle_rounded,
      );

      // Asynchronously send to server if connected
      _sendScanToServer(nis);
    }
  }

  Future<void> _sendScanToServer(String nis) async {
    try {
      await _apiService.client.post('/teacher/attendance/scan', data: {
        'nis': nis,
        'session_type': widget.sessionType,
        'class_id': widget.classId,
        'date': widget.date.toIso8601String().substring(0, 10),
        if (widget.subjectId != null) 'subject_id': widget.subjectId,
      });
    } catch (_) {
      // Non-blocking: data is safely stored in local state and offline queue
    }
  }

  String _getSessionTitle() {
    switch (widget.sessionType) {
      case 'apel':
        return 'Apel Pagi';
      case 'pulang':
        return 'Pulang Sekolah';
      case 'kelas':
      default:
        return 'Masuk Kelas';
    }
  }

  @override
  Widget build(BuildContext context) {
    final teacher = Provider.of<TeacherProvider>(context);
    final totalStudents = teacher.currentStudents.length;
    final presentCount = teacher.countPresent;

    return Scaffold(
      backgroundColor: Colors.black,
      body: Stack(
        children: [
          // Camera Scanner
          MobileScanner(
            controller: _controller,
            onDetect: _handleBarcodeDetected,
          ),

          // Scan Reticle Overlay
          _buildScannerOverlay(),

          // Top Header Bar
          Positioned(
            top: 0,
            left: 0,
            right: 0,
            child: _buildTopHeader(presentCount, totalStudents),
          ),

          // Instant Feedback Banner
          if (_feedbackMessage != null)
            Positioned(
              top: 110,
              left: 20,
              right: 20,
              child: _buildFeedbackBanner(),
            ),

          // Bottom Bar & Scanned History
          Positioned(
            bottom: 0,
            left: 0,
            right: 0,
            child: _buildBottomControls(presentCount, totalStudents),
          ),
        ],
      ),
    );
  }

  Widget _buildTopHeader(int presentCount, int totalStudents) {
    return Container(
      padding: EdgeInsets.fromLTRB(16, MediaQuery.of(context).padding.top + 8, 16, 14),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [
            Colors.black.withValues(alpha: 0.85),
            Colors.transparent,
          ],
          begin: Alignment.topCenter,
          end: Alignment.bottomCenter,
        ),
      ),
      child: Row(
        children: [
          // Close / Back Button
          IconButton(
            icon: const Icon(Icons.arrow_back_ios_new, color: Colors.white, size: 22),
            onPressed: () => Navigator.pop(context),
          ),
          const SizedBox(width: 4),
          // Title
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Row(
                  children: [
                    Text(
                      'Scan Barcode: ${_getSessionTitle()}',
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 15,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
                Text(
                  '${widget.className} • $presentCount / $totalStudents Hadir',
                  style: const TextStyle(color: Color(0xFF93C5FD), fontSize: 12),
                ),
              ],
            ),
          ),
          // Flashlight Toggle
          IconButton(
            icon: Icon(
              _isTorchOn ? Icons.flash_on : Icons.flash_off,
              color: _isTorchOn ? Colors.amber : Colors.white70,
            ),
            onPressed: () {
              _controller.toggleTorch();
              setState(() => _isTorchOn = !_isTorchOn);
            },
          ),
          // Switch Camera
          IconButton(
            icon: const Icon(Icons.cameraswitch_outlined, color: Colors.white70),
            onPressed: () => _controller.switchCamera(),
          ),
        ],
      ),
    );
  }

  Widget _buildScannerOverlay() {
    return LayoutBuilder(
      builder: (context, constraints) {
        final scanAreaWidth = constraints.maxWidth * 0.82;
        final scanAreaHeight = 180.0;

        return Stack(
          children: [
            // Darkened borders outside scan area
            ColorFiltered(
              colorFilter: ColorFilter.mode(
                Colors.black.withValues(alpha: 0.55),
                BlendMode.srcOut,
              ),
              child: Stack(
                children: [
                  Container(
                    decoration: const BoxDecoration(
                      color: Colors.transparent,
                      backgroundBlendMode: BlendMode.dstOut,
                    ),
                  ),
                  Center(
                    child: Container(
                      width: scanAreaWidth,
                      height: scanAreaHeight,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(16),
                      ),
                    ),
                  ),
                ],
              ),
            ),

            // Reticle Border & Animated Laser Line
            Center(
              child: Container(
                width: scanAreaWidth,
                height: scanAreaHeight,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFF60A5FA), width: 2),
                ),
                child: Stack(
                  children: [
                    // Corner accents
                    _buildReticleCorners(),

                    // Animated Laser line
                    AnimatedBuilder(
                      animation: _laserAnimation,
                      builder: (context, child) {
                        return Positioned(
                          top: scanAreaHeight * _laserAnimation.value,
                          left: 12,
                          right: 12,
                          child: Container(
                            height: 2.5,
                            decoration: BoxDecoration(
                              gradient: const LinearGradient(
                                colors: [
                                  Colors.transparent,
                                  Color(0xFF38BDF8),
                                  Color(0xFF10B981),
                                  Color(0xFF38BDF8),
                                  Colors.transparent,
                                ],
                              ),
                              boxShadow: [
                                BoxShadow(
                                  color: const Color(0xFF38BDF8).withValues(alpha: 0.8),
                                  blurRadius: 8,
                                  spreadRadius: 1,
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),

                    // Center Target Instruction
                    const Positioned(
                      bottom: 8,
                      left: 0,
                      right: 0,
                      child: Center(
                        child: Text(
                          'Arahkan Barcode NIS ke Kotak Ini',
                          style: TextStyle(
                            color: Colors.white70,
                            fontSize: 11,
                            fontWeight: FontWeight.w500,
                            shadows: [
                              Shadow(color: Colors.black, blurRadius: 4),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        );
      },
    );
  }

  Widget _buildReticleCorners() {
    const cornerSize = 20.0;
    const cornerWidth = 4.0;
    const cornerColor = Color(0xFF10B981);

    return Stack(
      children: [
        // Top-Left
        Positioned(
          top: -1,
          left: -1,
          child: Container(
            width: cornerSize,
            height: cornerSize,
            decoration: const BoxDecoration(
              border: Border(
                top: BorderSide(color: cornerColor, width: cornerWidth),
                left: BorderSide(color: cornerColor, width: cornerWidth),
              ),
            ),
          ),
        ),
        // Top-Right
        Positioned(
          top: -1,
          right: -1,
          child: Container(
            width: cornerSize,
            height: cornerSize,
            decoration: const BoxDecoration(
              border: Border(
                top: BorderSide(color: cornerColor, width: cornerWidth),
                right: BorderSide(color: cornerColor, width: cornerWidth),
              ),
            ),
          ),
        ),
        // Bottom-Left
        Positioned(
          bottom: -1,
          left: -1,
          child: Container(
            width: cornerSize,
            height: cornerSize,
            decoration: const BoxDecoration(
              border: Border(
                bottom: BorderSide(color: cornerColor, width: cornerWidth),
                left: BorderSide(color: cornerColor, width: cornerWidth),
              ),
            ),
          ),
        ),
        // Bottom-Right
        Positioned(
          bottom: -1,
          right: -1,
          child: Container(
            width: cornerSize,
            height: cornerSize,
            decoration: const BoxDecoration(
              border: Border(
                bottom: BorderSide(color: cornerColor, width: cornerWidth),
                right: BorderSide(color: cornerColor, width: cornerWidth),
              ),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildFeedbackBanner() {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      decoration: BoxDecoration(
        color: _feedbackColor,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.3),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          Icon(_feedbackIcon, color: Colors.white, size: 24),
          const SizedBox(width: 10),
          Expanded(
            child: Text(
              _feedbackMessage!,
              style: const TextStyle(
                color: Colors.white,
                fontWeight: FontWeight.bold,
                fontSize: 13,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildBottomControls(int presentCount, int totalStudents) {
    return Container(
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 24),
      decoration: BoxDecoration(
        color: const Color(0xFF0F172A).withValues(alpha: 0.95),
        borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.4),
            blurRadius: 12,
            offset: const Offset(0, -4),
          ),
        ],
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Progress bar
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Kehadiran: $presentCount dari $totalStudents Siswa',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 13,
                  fontWeight: FontWeight.bold,
                ),
              ),
              Text(
                '${totalStudents > 0 ? ((presentCount / totalStudents) * 100).toInt() : 0}%',
                style: const TextStyle(
                  color: Color(0xFF10B981),
                  fontSize: 13,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          ClipRRect(
            borderRadius: BorderRadius.circular(6),
            child: LinearProgressIndicator(
              value: totalStudents > 0 ? (presentCount / totalStudents) : 0,
              backgroundColor: const Color(0xFF334155),
              color: const Color(0xFF10B981),
              minHeight: 8,
            ),
          ),

          const SizedBox(height: 12),

          // Recent scanned list (Collapsible / Mini list)
          if (_recentScans.isNotEmpty) ...[
            Container(
              constraints: const BoxConstraints(maxHeight: 110),
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
              decoration: BoxDecoration(
                color: const Color(0xFF1E293B),
                borderRadius: BorderRadius.circular(10),
              ),
              child: ListView.separated(
                shrinkWrap: true,
                padding: EdgeInsets.zero,
                itemCount: _recentScans.length > 3 ? 3 : _recentScans.length,
                separatorBuilder: (_, __) => const Divider(height: 8, color: Color(0xFF334155)),
                itemBuilder: (ctx, idx) {
                  final item = _recentScans[idx];
                  return Row(
                    children: [
                      const Icon(Icons.check_circle, color: Color(0xFF10B981), size: 16),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          '${item['name']} (NIS: ${item['nis']})',
                          style: const TextStyle(color: Colors.white, fontSize: 11.5),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                      Text(
                        item['time'] ?? '',
                        style: const TextStyle(color: Color(0xFF94A3B8), fontSize: 10),
                      ),
                    ],
                  );
                },
              ),
            ),
            const SizedBox(height: 12),
          ],

          // "Selesai" Action Button
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () => Navigator.pop(context),
              icon: const Icon(Icons.check, size: 20),
              label: const Text(
                'Selesai & Lihat Rekap Presensi',
                style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF2563EB),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 13),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
