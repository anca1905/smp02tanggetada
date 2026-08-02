import 'dart:math';
import 'package:flutter/material.dart';

class DonutChartData {
  final String label;
  final double value;
  final Color color;

  DonutChartData(this.label, this.value, this.color);
}

class DonutChart extends StatelessWidget {
  final List<DonutChartData> data;
  final double strokeWidth;
  final Widget centerContent;
  final double radius;

  const DonutChart({
    Key? key,
    required this.data,
    this.strokeWidth = 20.0,
    required this.centerContent,
    this.radius = 60,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Stack(
      alignment: Alignment.center,
      children: [
        CustomPaint(
          size: Size(radius * 2, radius * 2),
          painter: _DonutChartPainter(data, strokeWidth),
        ),
        centerContent,
      ],
    );
  }
}

class _DonutChartPainter extends CustomPainter {
  final List<DonutChartData> data;
  final double strokeWidth;

  _DonutChartPainter(this.data, this.strokeWidth);

  @override
  void paint(Canvas canvas, Size size) {
    double total = 0;
    for (var item in data) {
      total += item.value;
    }

    if (total == 0) return;

    final rect = Rect.fromLTWH(0, 0, size.width, size.height);
    double startAngle = -pi / 2; // Start from top

    for (var item in data) {
      final sweepAngle = (item.value / total) * 2 * pi;
      final paint = Paint()
        ..color = item.color
        ..style = PaintingStyle.stroke
        ..strokeWidth = strokeWidth;

      canvas.drawArc(rect, startAngle, sweepAngle, false, paint);
      startAngle += sweepAngle;
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) {
    return true;
  }
}
