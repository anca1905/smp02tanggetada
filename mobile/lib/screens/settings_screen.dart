import 'package:flutter/material.dart';

class SettingsScreen extends StatefulWidget {
  const SettingsScreen({Key? key}) : super(key: key);

  @override
  _SettingsScreenState createState() => _SettingsScreenState();
}

class _SettingsScreenState extends State<SettingsScreen> {
  bool _notifPush = true;
  bool _notifEmail = false;
  bool _biometricLogin = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Pengaturan'),
      ),
      body: ListView(
        children: [
          _buildSectionHeader('Notifikasi'),
          SwitchListTile(
            title: const Text('Push Notification'),
            subtitle: const Text('Terima notifikasi di perangkat ini'),
            value: _notifPush,
            onChanged: (val) => setState(() => _notifPush = val),
          ),
          SwitchListTile(
            title: const Text('Email Notification'),
            subtitle: const Text('Terima notifikasi via email'),
            value: _notifEmail,
            onChanged: (val) => setState(() => _notifEmail = val),
          ),
          
          const Divider(),
          _buildSectionHeader('Keamanan Akun'),
          SwitchListTile(
            title: const Text('Login dengan Biometrik'),
            subtitle: const Text('Gunakan sidik jari atau Face ID'),
            value: _biometricLogin,
            onChanged: (val) => setState(() => _biometricLogin = val),
          ),
          ListTile(
            title: const Text('Aktivitas Login'),
            trailing: const Icon(Icons.chevron_right),
            onTap: () {
              ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Menampilkan riwayat perangkat...')));
            },
          ),
          
          const Divider(),
          _buildSectionHeader('Umum'),
          ListTile(
            title: const Text('Bahasa'),
            subtitle: const Text('Bahasa Indonesia'),
            trailing: const Icon(Icons.chevron_right),
            onTap: () {
              _showLanguagePicker(context);
            },
          ),
          ListTile(
            title: const Text('Tentang Aplikasi'),
            trailing: const Icon(Icons.chevron_right),
            onTap: () {
              showAboutDialog(
                context: context,
                applicationName: 'SIMS Student App',
                applicationVersion: '1.0.0',
                applicationIcon: const Icon(Icons.school, size: 40, color: Colors.blue),
                children: [
                  const Text('Aplikasi Sistem Informasi Manajemen Sekolah untuk Siswa.'),
                ],
              );
            },
          ),
        ],
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
      child: Text(
        title,
        style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
      ),
    );
  }

  void _showLanguagePicker(BuildContext context) {
    showModalBottomSheet(
      context: context,
      builder: (context) {
        return SafeArea(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const ListTile(title: Text('Pilih Bahasa', style: TextStyle(fontWeight: FontWeight.bold))),
              ListTile(
                leading: const Icon(Icons.check, color: Colors.blue),
                title: const Text('Bahasa Indonesia'),
                onTap: () => Navigator.pop(context),
              ),
              ListTile(
                leading: const SizedBox(width: 24),
                title: const Text('English'),
                onTap: () {
                  Navigator.pop(context);
                  ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Language changed to English')));
                },
              ),
            ],
          ),
        );
      }
    );
  }
}
