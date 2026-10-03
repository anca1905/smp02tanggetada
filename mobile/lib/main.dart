import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'theme/app_theme.dart';
import 'providers/auth_provider.dart';
import 'providers/data_provider.dart';
import 'providers/teacher_provider.dart';
import 'screens/login_screen.dart';
import 'screens/home_screen.dart';
import 'screens/parent_main_screen.dart';
import 'screens/teacher/teacher_home_screen.dart';

void main() {
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        ChangeNotifierProvider(create: (_) => DataProvider()),
        ChangeNotifierProvider(create: (_) => TeacherProvider()),
      ],
      child: const StudentApp(),
    ),
  );
}

class StudentApp extends StatelessWidget {
  const StudentApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'SIMS Edu',
      theme: AppTheme.lightTheme,
      debugShowCheckedModeBanner: false,
      home: Consumer<AuthProvider>(
        builder: (context, auth, _) {
          if (auth.isAuthenticated) {
            if (auth.isTeacher) {
              return const TeacherHomeScreen();
            } else if (auth.isParent) {
              return const ParentMainScreen();
            } else {
              return const HomeScreen();
            }
          }
          return const LoginScreen();
        },
      ),
    );
  }
}
