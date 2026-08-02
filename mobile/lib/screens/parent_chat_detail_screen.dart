import 'package:flutter/material.dart';

class ParentChatDetailScreen extends StatefulWidget {
  final String name;
  final String role;
  final String avatar;

  const ParentChatDetailScreen({
    Key? key,
    required this.name,
    required this.role,
    required this.avatar,
  }) : super(key: key);

  @override
  State<ParentChatDetailScreen> createState() => _ParentChatDetailScreenState();
}

class _ParentChatDetailScreenState extends State<ParentChatDetailScreen> {
  final TextEditingController _msgController = TextEditingController();

  @override
  void dispose() {
    _msgController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8F9FA),
      appBar: _buildAppBar(),
      body: Column(
        children: [
          Expanded(
            child: ListView(
              padding: const EdgeInsets.all(16),
              physics: const BouncingScrollPhysics(),
              children: [
                _buildDateSeparator('Rabu, 9 Juli 2026'),
                _buildChatBubble(
                  'Assalamu\'alaikum Ibu Siti,\n\nSaya ingin menyampaikan bahwa Arsyad hari ini sangat aktif di kelas dan mengerjakan tugas dengan baik.\n\nTerima kasih🙏',
                  '09.15',
                  false,
                ),
                _buildChatBubble(
                  'Wa\'alaikumsalam Bu,\n\nAlhamdulillah, terima kasih atas informasinya Bu. Semoga Arsyad bisa terus semangat belajarnya.',
                  '09.20',
                  true,
                  true,
                ),
                _buildChatBubble(
                  'Amin Ibu, mohon dukunganya agar Arsyad tetap disiplin belajar di rumah ya Bu 🙏',
                  '09.25',
                  false,
                ),
                _buildChatBubble(
                  'InsyaAllah Bu, akan kami terus bimbing. Terima kasih banyak Bu Anita 😁🙏',
                  '09.27',
                  true,
                  true,
                ),
                _buildUnreadSeparator('1 PESAN BELUM DIBACA'),
                _buildChatBubble(
                  'Terima kasih kembali Ibu🙏',
                  '09.30',
                  false,
                ),
              ],
            ),
          ),
          _buildInputArea(),
        ],
      ),
    );
  }

  PreferredSizeWidget _buildAppBar() {
    bool isGroup = widget.avatar.isEmpty;
    return AppBar(
      backgroundColor: Colors.white,
      elevation: 1,
      shadowColor: Colors.black.withOpacity(0.1),
      leading: IconButton(
        icon: const Icon(Icons.arrow_back, color: Colors.black87),
        onPressed: () => Navigator.pop(context),
      ),
      titleSpacing: 0,
      title: Row(
        children: [
          if (isGroup)
            Container(width: 40, height: 40, decoration: BoxDecoration(color: Colors.green.withOpacity(0.1), shape: BoxShape.circle), child: const Icon(Icons.group, color: Colors.green))
          else
            Stack(
              children: [
                CircleAvatar(backgroundImage: NetworkImage(widget.avatar)),
                Positioned(
                  bottom: 0,
                  right: 0,
                  child: Container(width: 10, height: 10, decoration: BoxDecoration(color: Colors.green, shape: BoxShape.circle, border: Border.all(color: Colors.white, width: 2))),
                )
              ],
            ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(widget.name, style: const TextStyle(color: Colors.black87, fontSize: 16, fontWeight: FontWeight.bold), maxLines: 1, overflow: TextOverflow.ellipsis),
                Row(
                  children: [
                    Text(widget.role, style: const TextStyle(color: Colors.grey, fontSize: 11), maxLines: 1, overflow: TextOverflow.ellipsis),
                    if (!isGroup) ...[
                      const SizedBox(width: 6),
                      Container(width: 4, height: 4, decoration: const BoxDecoration(color: Colors.green, shape: BoxShape.circle)),
                      const SizedBox(width: 4),
                      const Text('Online', style: TextStyle(color: Colors.green, fontSize: 10)),
                    ]
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
      actions: [
        IconButton(icon: const Icon(Icons.phone, color: Colors.black54), onPressed: () {}),
        IconButton(icon: const Icon(Icons.more_vert, color: Colors.black54), onPressed: () {}),
      ],
    );
  }

  Widget _buildDateSeparator(String text) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 16),
      child: Center(
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
          decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(12), border: Border.all(color: Colors.grey.shade200)),
          child: Text(text, style: const TextStyle(fontSize: 10, color: Colors.grey)),
        ),
      ),
    );
  }

  Widget _buildUnreadSeparator(String text) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 16),
      child: Center(
        child: Text(text, style: const TextStyle(fontSize: 10, color: Colors.grey, fontWeight: FontWeight.bold)),
      ),
    );
  }

  Widget _buildChatBubble(String message, String time, bool isMe, [bool isRead = false]) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Row(
        mainAxisAlignment: isMe ? MainAxisAlignment.end : MainAxisAlignment.start,
        children: [
          Container(
            constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.75),
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: isMe ? Colors.blue.shade50 : Colors.white,
              borderRadius: BorderRadius.only(
                topLeft: const Radius.circular(16),
                topRight: const Radius.circular(16),
                bottomLeft: Radius.circular(isMe ? 16 : 0),
                bottomRight: Radius.circular(isMe ? 0 : 16),
              ),
              boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 5, offset: const Offset(0, 2))],
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Text(message, style: const TextStyle(fontSize: 13, color: Colors.black87, height: 1.4)),
                const SizedBox(height: 4),
                Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(time, style: const TextStyle(fontSize: 9, color: Colors.grey)),
                    if (isMe) ...[
                      const SizedBox(width: 4),
                      Icon(Icons.done_all, size: 14, color: isRead ? Colors.blue : Colors.grey),
                    ]
                  ],
                )
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInputArea() {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, -5))],
      ),
      child: SafeArea(
        child: Row(
          children: [
            IconButton(icon: const Icon(Icons.attach_file, color: Colors.grey), onPressed: () {}),
            Expanded(
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                decoration: BoxDecoration(color: Colors.grey.shade100, borderRadius: BorderRadius.circular(24)),
                child: Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: _msgController,
                        decoration: const InputDecoration(hintText: 'Ketik pesan...', border: InputBorder.none, hintStyle: TextStyle(fontSize: 14, color: Colors.grey)),
                      ),
                    ),
                    Icon(Icons.emoji_emotions_outlined, color: Colors.grey.shade600),
                  ],
                ),
              ),
            ),
            const SizedBox(width: 12),
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(color: Colors.blue.shade700, shape: BoxShape.circle),
              child: const Icon(Icons.send, color: Colors.white, size: 20),
            )
          ],
        ),
      ),
    );
  }
}
