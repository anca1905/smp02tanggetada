import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import 'parent_chat_detail_screen.dart';

class ParentChatScreen extends StatelessWidget {
  const ParentChatScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      body: SafeArea(
        child: Column(
          children: [
            _buildHeader(context),
            Expanded(
              child: SingleChildScrollView(
                physics: const BouncingScrollPhysics(),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const SizedBox(height: 16),
                    _buildActionCards(),
                    const SizedBox(height: 24),
                    _buildTeacherContacts(context),
                    const SizedBox(height: 24),
                    _buildConversations(context),
                    const SizedBox(height: 24),
                    _buildEtikaBanner(),
                    const SizedBox(height: 24),
                  ],
                ),
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Container(
      padding: const EdgeInsets.only(left: 16, right: 16, top: 20, bottom: 20),
      decoration: const BoxDecoration(
        color: Color(0xFF0D47A1), // Darker blue like the screenshot
      ),
      child: Row(
        children: [
          const CircleAvatar(
            radius: 24,
            backgroundImage: NetworkImage('https://i.pravatar.cc/150?img=5'),
          ),
          const SizedBox(width: 16),
          const Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Chat', style: TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold)),
                SizedBox(height: 4),
                Text('Komunikasi dengan guru & sekolah', style: TextStyle(color: Colors.white70, fontSize: 12)),
              ],
            ),
          ),
          Stack(
            clipBehavior: Clip.none,
            children: [
              const Icon(Icons.notifications_none, color: Colors.white, size: 28),
              Positioned(
                right: -2,
                top: -2,
                child: Container(
                  padding: const EdgeInsets.all(4),
                  decoration: const BoxDecoration(color: Colors.red, shape: BoxShape.circle),
                  child: const Text('5', style: TextStyle(color: Colors.white, fontSize: 8, fontWeight: FontWeight.bold)),
                ),
              )
            ],
          ),
          const SizedBox(width: 16),
          const Icon(Icons.more_horiz, color: Colors.white, size: 28),
        ],
      ),
    );
  }

  Widget _buildActionCards() {
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: const EdgeInsets.symmetric(horizontal: 16),
      physics: const BouncingScrollPhysics(),
      child: Row(
        children: [
          _buildActionCard(Icons.chat_bubble_outline, 'Chat dengan Guru', 'Mulai percakapan', Colors.purple),
          _buildActionCard(Icons.group_outlined, 'Grup Kelas', 'Gabung grup kelas anak Anda', Colors.green),
          _buildActionCard(Icons.campaign_outlined, 'Pengumuman', 'Lihat semua pengumuman', Colors.orange),
          _buildActionCard(Icons.headset_mic_outlined, 'Bantuan', 'Hubungi admin sekolah', Colors.blue),
        ],
      ),
    );
  }

  Widget _buildActionCard(IconData icon, String title, String subtitle, Color iconColor) {
    return Container(
      width: 140,
      margin: const EdgeInsets.only(right: 12),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(color: iconColor.withOpacity(0.1), borderRadius: BorderRadius.circular(8)),
            child: Icon(icon, color: iconColor, size: 20),
          ),
          const SizedBox(height: 12),
          Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12), maxLines: 2),
          const SizedBox(height: 4),
          Text(subtitle, style: const TextStyle(color: Colors.grey, fontSize: 10), maxLines: 2, overflow: TextOverflow.ellipsis),
        ],
      ),
    );
  }

  Widget _buildTeacherContacts(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Kontak Guru', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
              Text('Lihat Semua >', style: TextStyle(color: Colors.blue.shade700, fontSize: 12, fontWeight: FontWeight.bold)),
            ],
          ),
        ),
        const SizedBox(height: 12),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          padding: const EdgeInsets.symmetric(horizontal: 16),
          physics: const BouncingScrollPhysics(),
          child: Row(
            children: [
              _buildTeacherContactCard(context, 'Bu Anita Sari', 'Wali Kelas XI IPA 1', 'https://i.pravatar.cc/150?img=9'),
              _buildTeacherContactCard(context, 'Pak Dwi Prasetyo', 'Guru Informatika', 'https://i.pravatar.cc/150?img=11'),
              _buildTeacherContactCard(context, 'Bu Lestari', 'Guru Kimia', 'https://i.pravatar.cc/150?img=5'),
              _buildTeacherContactCard(context, 'Pak Andi Wijaya', 'Guru Fisika', 'https://i.pravatar.cc/150?img=12'),
            ],
          ),
        )
      ],
    );
  }

  Widget _buildTeacherContactCard(BuildContext context, String name, String role, String avatar) {
    return Container(
      width: 120,
      margin: const EdgeInsets.only(right: 12),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        children: [
          Stack(
            children: [
              CircleAvatar(radius: 24, backgroundImage: NetworkImage(avatar)),
              Positioned(
                bottom: 0,
                right: 0,
                child: Container(width: 12, height: 12, decoration: BoxDecoration(color: Colors.green, shape: BoxShape.circle, border: Border.all(color: Colors.white, width: 2))),
              )
            ],
          ),
          const SizedBox(height: 8),
          Text(name, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 11), textAlign: TextAlign.center, maxLines: 1, overflow: TextOverflow.ellipsis),
          const SizedBox(height: 4),
          Text(role, style: const TextStyle(color: Colors.grey, fontSize: 9), textAlign: TextAlign.center, maxLines: 1, overflow: TextOverflow.ellipsis),
          const SizedBox(height: 12),
          SizedBox(
            width: double.infinity,
            height: 28,
            child: OutlinedButton(
              onPressed: () {
                Navigator.push(context, MaterialPageRoute(builder: (context) => ParentChatDetailScreen(name: name, role: role, avatar: avatar)));
              },
              style: OutlinedButton.styleFrom(
                side: BorderSide(color: Colors.blue.shade100),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                padding: EdgeInsets.zero,
              ),
              child: const Text('Chat', style: TextStyle(fontSize: 11, color: Colors.blue, fontWeight: FontWeight.bold)),
            ),
          )
        ],
      ),
    );
  }

  Widget _buildConversations(BuildContext context) {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Percakapan', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              Row(
                children: [
                  Icon(Icons.search, color: Colors.blue.shade700, size: 20),
                  const SizedBox(width: 12),
                  Icon(Icons.filter_list, color: Colors.blue.shade700, size: 20),
                ],
              )
            ],
          ),
        ),
        const SizedBox(height: 12),
        _buildConversationItem(context, 'Bu Anita Sari', 'Wali Kelas XI IPA 1', 'Terima kasih kembali Ibu 🙏', '09.30', 2, 'https://i.pravatar.cc/150?img=9', false),
        _buildConversationItem(context, 'Pak Dwi Prasetyo', 'Guru Informatika', 'Baik Ibu, tugasnya akan saya infokan di grup.', '08.45', 1, 'https://i.pravatar.cc/150?img=11', false),
        _buildConversationItem(context, 'Bu Lestari', 'Guru Kimia', 'Nilai ulangan harian sudah diperbarui ya Bu.', 'Kemarin', 0, 'https://i.pravatar.cc/150?img=5', false),
        _buildConversationItem(context, 'Grup Kelas XI IPA 1', '25 Anggota', 'Bu Anita: Pengumuman penting untuk orang tua 🙏', 'Kemarin', 3, '', true, Icons.group, Colors.green),
        _buildConversationItem(context, 'Pengumuman Sekolah', 'Admin', 'Libur Semester Ganjil dimulai 20 Juli - 2 Agustus 2026.', '2 Jul', 0, '', true, Icons.campaign, Colors.red),
        _buildConversationItem(context, 'Admin Sekolah', 'Sistem Informasi', 'Selamat datang di layanan chat SIMS Terpadu.', '1 Jul', 0, '', true, Icons.admin_panel_settings, Colors.purple),
      ],
    );
  }

  Widget _buildConversationItem(BuildContext context, String name, String subtitle, String lastMsg, String time, int unread, String avatar, bool isGroup, [IconData? icon, Color? iconColor]) {
    return InkWell(
      onTap: () {
        Navigator.push(context, MaterialPageRoute(builder: (context) => ParentChatDetailScreen(name: name, role: subtitle, avatar: avatar)));
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        decoration: BoxDecoration(
          color: unread > 0 ? Colors.blue.withOpacity(0.03) : Colors.transparent,
          border: Border(bottom: BorderSide(color: Colors.grey.shade200)),
        ),
        child: Row(
          children: [
            if (isGroup)
              Container(
                width: 50,
                height: 50,
                decoration: BoxDecoration(color: iconColor?.withOpacity(0.1), shape: BoxShape.circle),
                child: Icon(icon, color: iconColor),
              )
            else
              CircleAvatar(radius: 25, backgroundImage: NetworkImage(avatar)),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Expanded(child: Text(name, style: TextStyle(fontWeight: unread > 0 ? FontWeight.bold : FontWeight.w600, fontSize: 14), maxLines: 1, overflow: TextOverflow.ellipsis)),
                      Text(time, style: TextStyle(fontSize: 10, color: unread > 0 ? Colors.blue : Colors.grey, fontWeight: unread > 0 ? FontWeight.bold : FontWeight.normal)),
                    ],
                  ),
                  const SizedBox(height: 2),
                  Text(subtitle, style: const TextStyle(color: Colors.grey, fontSize: 10)),
                  const SizedBox(height: 6),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Expanded(child: Text(lastMsg, style: TextStyle(fontSize: 12, color: unread > 0 ? Colors.black87 : Colors.grey), maxLines: 1, overflow: TextOverflow.ellipsis)),
                      if (unread > 0)
                        Container(
                          padding: const EdgeInsets.all(6),
                          decoration: BoxDecoration(color: Colors.blue.shade700, shape: BoxShape.circle),
                          child: Text(unread.toString(), style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold)),
                        )
                    ],
                  )
                ],
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildEtikaBanner() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(color: Colors.blue.shade50, borderRadius: BorderRadius.circular(12)),
        child: Row(
          children: [
            Icon(Icons.shield, color: Colors.blue.shade700, size: 28),
            const SizedBox(width: 12),
            const Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Jaga Etika Komunikasi', style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold, fontSize: 12)),
                  SizedBox(height: 4),
                  Text('Mohon gunakan bahasa yang sopan dan santun saat berkomunikasi dengan guru dan pihak sekolah.', style: TextStyle(color: Colors.blueGrey, fontSize: 10)),
                ],
              ),
            ),
            const Icon(Icons.close, color: Colors.grey, size: 16),
          ],
        ),
      ),
    );
  }
}
