import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class EditProfileScreen extends StatefulWidget {
  const EditProfileScreen({Key? key}) : super(key: key);

  @override
  _EditProfileScreenState createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<EditProfileScreen> {
  final _formKey = GlobalKey<FormState>();
  bool _isLoading = false;

  late TextEditingController _addressController;
  late TextEditingController _phoneController;
  late TextEditingController _emailController;

  late TextEditingController _fatherNameController;
  late TextEditingController _motherNameController;
  late TextEditingController _parentPhoneController;

  @override
  void initState() {
    super.initState();
    final user = Provider.of<AuthProvider>(context, listen: false).user;
    
    _addressController = TextEditingController(text: user?['address'] ?? 'Jl. Pendidikan No. 10');
    _phoneController = TextEditingController(text: user?['phone_number'] ?? '081234567890');
    _emailController = TextEditingController(text: user?['email'] ?? 'student@mail.com');

    _fatherNameController = TextEditingController(text: 'Bapak Ahmad');
    _motherNameController = TextEditingController(text: 'Ibu Siti');
    _parentPhoneController = TextEditingController(text: '0812-xxxx');
  }

  @override
  void dispose() {
    _addressController.dispose();
    _phoneController.dispose();
    _emailController.dispose();
    _fatherNameController.dispose();
    _motherNameController.dispose();
    _parentPhoneController.dispose();
    super.dispose();
  }

  void _save() async {
    if (!_formKey.currentState!.validate()) return;
    
    setState(() => _isLoading = true);
    // Simulasi simpan ke server
    await Future.delayed(const Duration(seconds: 2));
    setState(() => _isLoading = false);

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Profil berhasil diperbarui')));
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Edit Profil'),
        actions: [
          TextButton(
            onPressed: _isLoading ? null : _save,
            child: _isLoading 
              ? const SizedBox(height: 16, width: 16, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
              : const Text('Simpan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
          )
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Informasi Pribadi', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.blue)),
              const SizedBox(height: 16),
              _buildTextField('Alamat Domisili', _addressController),
              _buildTextField('Nomor HP', _phoneController, isPhone: true),
              _buildTextField('Email', _emailController, isEmail: true),
              
              const SizedBox(height: 24),
              const Divider(),
              const SizedBox(height: 16),

              const Text('Informasi Orang Tua / Wali', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.blue)),
              const SizedBox(height: 16),
              _buildTextField('Nama Ayah', _fatherNameController),
              _buildTextField('Nama Ibu', _motherNameController),
              _buildTextField('Nomor HP Orang Tua/Wali', _parentPhoneController, isPhone: true),
              
              const SizedBox(height: 24),
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(color: Colors.orange.shade50, borderRadius: BorderRadius.circular(8)),
                child: const Row(
                  children: [
                    Icon(Icons.info_outline, color: Colors.orange),
                    SizedBox(width: 8),
                    Expanded(child: Text('Untuk mengubah data penting seperti Nama Lengkap atau NIS, silakan hubungi Tata Usaha sekolah.', style: TextStyle(fontSize: 12))),
                  ],
                ),
              )
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildTextField(String label, TextEditingController controller, {bool isPhone = false, bool isEmail = false}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: TextFormField(
        controller: controller,
        keyboardType: isPhone ? TextInputType.phone : (isEmail ? TextInputType.emailAddress : TextInputType.text),
        decoration: InputDecoration(
          labelText: label,
          border: const OutlineInputBorder(),
          contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        ),
        validator: (value) {
          if (value == null || value.isEmpty) {
            return '$label tidak boleh kosong';
          }
          return null;
        },
      ),
    );
  }
}
