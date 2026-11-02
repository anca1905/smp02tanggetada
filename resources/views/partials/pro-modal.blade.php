{{-- Modal Penawaran & Biaya Langganan Fitur PRO --}}
<div id="simsProModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm hidden transition-all duration-300">
    <div class="relative w-full max-w-lg overflow-hidden bg-white rounded-2xl shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
        
        {{-- Header Gradient --}}
        <div class="relative px-6 py-5 bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-400 text-amber-950 tracking-wider shadow-sm">
                        👑 SIMS PRO
                    </span>
                    <span id="proModalCategory" class="text-xs text-blue-200 font-medium tracking-wide">
                        Add-on Khusus
                    </span>
                </div>
                <button type="button" onclick="closeProModal()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="mt-3 flex items-center space-x-3">
                <div id="proModalIcon" class="w-11 h-11 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center text-2xl shadow-inner border border-white/20">
                    ✨
                </div>
                <div>
                    <h3 id="proModalTitle" class="text-lg font-bold text-white leading-snug">
                        Nama Fitur PRO
                    </h3>
                    <p class="text-xs text-blue-200 mt-0.5">
                        Tingkatkan digitalisasi sekolah Anda ke level berikutnya
                    </p>
                </div>
            </div>
        </div>

        {{-- Body Content --}}
        <div class="p-6 space-y-4">
            
            {{-- Deskripsi Fitur --}}
            <p id="proModalDesc" class="text-sm text-slate-600 leading-relaxed">
                Deskripsi fitur lengkap akan tampil di sini.
            </p>

            {{-- Keunggulan / Manfaat --}}
            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5 flex items-center">
                    <svg class="w-4 h-4 text-emerald-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Manfaat Nyata untuk Sekolah:
                </h4>
                <ul id="proModalBenefits" class="text-xs text-slate-600 space-y-2">
                    {{-- Dinamis via JS --}}
                </ul>
            </div>

            {{-- Box Harga & Anggaran BOS --}}
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200/80 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-semibold text-amber-800 uppercase tracking-wide">
                            Biaya Berlangganan Add-on:
                        </div>
                        <div id="proModalPrice" class="text-xl font-extrabold text-amber-950 font-mono mt-0.5">
                            Rp 150.000 <span class="text-xs font-sans font-normal text-amber-800">/ bulan</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-2.5 py-1 text-[11px] font-bold text-emerald-800 bg-emerald-100 rounded-lg border border-emerald-300">
                            ✓ Dana BOS Ready
                        </span>
                    </div>
                </div>
                <div class="mt-2.5 pt-2.5 border-t border-amber-200/60 text-[11px] text-amber-800 leading-tight flex items-start space-x-1.5">
                    <span class="text-amber-600 text-xs mt-0.5">ℹ️</span>
                    <span>Biaya dapat dibayarkan sah melalui <strong>DANA BOS Reguler</strong> (Standar Pembiayaan Langganan Daya & Jasa).</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-2 flex items-center space-x-3">
                <button type="button" onclick="closeProModal()" class="w-1/3 py-2.5 px-4 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Nanti Saja
                </button>
                <a id="proModalWaBtn" href="#" target="_blank" class="w-2/3 py-2.5 px-4 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-lg shadow-emerald-600/25 flex items-center justify-center space-x-2 transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Hubungi Pengembang (WA)</span>
                </a>
            </div>

        </div>

    </div>
</div>

<script>
    const proFeaturesCatalog = {
        'absensi_guru': {
            title: 'Presensi Digital Guru & Tenaga Kependidikan',
            category: 'SDM & GTK',
            icon: '👨‍🏫',
            price: 'Rp 150.000 <span class="text-xs font-sans font-normal text-amber-800">/ bulan</span>',
            desc: 'Sistem presensi masuk & pulang khusus dewan guru dan staf TU dengan validasi foto dan rekapitulasi kehadiran dinas.',
            benefits: [
                'Laporan rekap kehadiran bulanan siap cetak untuk syarat Tunjangan Sertifikasi & TPP',
                'Validasi jam datang/pulang akurat bebas titip absen dengan foto selfie / QR khusus',
                'Kepala Sekolah dapat memantau kedisiplinan guru secara real-time dari HP'
            ],
            waMsg: 'Halo Skillance / Tim Pengembang SIMS, kami dari SMP Negeri 2 Tanggetada berminat mengaktifkan Fitur PRO Presensi Guru & Pegawai. Mohon info aktivasi dan persyaratannya.'
        },
        'spp_billing': {
            title: 'Manajemen SPP & Tagihan Komite Siswa',
            category: 'Keuangan Sekolah',
            icon: '💳',
            price: 'Rp 150.000 <span class="text-xs font-sans font-normal text-amber-800">/ bulan</span>',
            desc: 'Sistem pembukuan tagihan iuran komite, seragam, atau SPP bulanan siswa terintegrasi dengan kuitansi pembayaran resmi.',
            benefits: [
                'Membuat tagihan iuran massal ke seluruh siswa dalam 1 kali klik',
                'Mencetak kuitansi pembayaran digital sah untuk arsip bendahara & wali murid',
                'Rekap status tunggakan siswa dan laporan kas komite sekolah yang rapi dan transparan'
            ],
            waMsg: 'Halo Skillance / Tim Pengembang SIMS, kami dari SMP Negeri 2 Tanggetada berminat mengaktifkan Fitur PRO Tagihan & Pembukuan Siswa. Mohon info aktivasi dan persyaratannya.'
        },
        'whatsapp_gateway': {
            title: 'WhatsApp Gateway Otomatis ke Orang Tua',
            category: 'Komunikasi & Notifikasi',
            icon: '💬',
            price: 'Rp 200.000 <span class="text-xs font-sans font-normal text-amber-800">/ bulan</span>',
            desc: 'Setiap siswa scan QR presensi masuk atau pulang, pesan WhatsApp otomatis terkirim langsung ke nomor HP orang tua.',
            benefits: [
                'Notifikasi kehadiran real-time langsung ke WhatsApp orang tua detik itu juga',
                'Kirim broadcast pengumuman libur, surat edaran, atau rapat komite sekali klik',
                'Meningkatkan rasa aman orang tua dan menaikkan citra kecanggihan sekolah'
            ],
            waMsg: 'Halo Skillance / Tim Pengembang SIMS, kami dari SMP Negeri 2 Tanggetada berminat mengaktifkan Fitur PRO WhatsApp Gateway ke Orang Tua. Mohon info aktivasi dan persyaratannya.'
        },
        'jurnal_guru': {
            title: 'Jurnal Mengajar Harian (KBM Digital)',
            category: 'Akademik & Pembelajaran',
            icon: '📖',
            price: 'Rp 150.000 <span class="text-xs font-sans font-normal text-amber-800">/ bulan</span>',
            desc: 'Guru mengisi jurnal pembelajaran digital setiap selesai mengajar di kelas tanpa perlu membeli buku jurnal kertas.',
            benefits: [
                'Mencatat materi capaian KBM harian dan catatan siswa bermasalah per kelas',
                'Kepala Sekolah dapat memantau keterlaksanaan jam mengajar guru secara langsung',
                'Hemat anggaran pengadaan buku jurnal fisik guru setiap semester'
            ],
            waMsg: 'Halo Skillance / Tim Pengembang SIMS, kami dari SMP Negeri 2 Tanggetada berminat mengaktifkan Fitur PRO Jurnal Mengajar Digital. Mohon info aktivasi dan persyaratannya.'
        },
        'cbt_exam': {
            title: 'CBT Ujian Online Bebas Kertas (Anti-Curang)',
            category: 'Evaluasi & Ujian',
            icon: '📝',
            price: 'Rp 250.000 <span class="text-xs font-sans font-normal text-amber-800">/ bulan</span>',
            desc: 'Ujian sekolah (PTS, PAS, Asesmen) berbasis HP/komputer dengan koreksi otomatis dan fitur pengunci layar.',
            benefits: [
                'Memangkas biaya cetak & fotokopi kertas ujian jutaan rupiah setiap semester',
                'Fitur anti-curang otomatis mengunci jika siswa mencoba membuka browser lain',
                'Nilai siswa terhitung otomatis dan langsung terekap ke buku nilai guru'
            ],
            waMsg: 'Halo Skillance / Tim Pengembang SIMS, kami dari SMP Negeri 2 Tanggetada berminat mengaktifkan Fitur PRO CBT Ujian Online. Mohon info aktivasi dan persyaratannya.'
        }
    };

    function openProModal(featureKey) {
        const modal = document.getElementById('simsProModal');
        const data = proFeaturesCatalog[featureKey] || proFeaturesCatalog['absensi_guru'];

        document.getElementById('proModalTitle').innerText = data.title;
        document.getElementById('proModalCategory').innerText = data.category;
        document.getElementById('proModalIcon').innerText = data.icon;
        document.getElementById('proModalDesc').innerText = data.desc;
        document.getElementById('proModalPrice').innerHTML = data.price;

        // Render benefits
        const benefitsContainer = document.getElementById('proModalBenefits');
        benefitsContainer.innerHTML = '';
        data.benefits.forEach(benefit => {
            const li = document.createElement('li');
            li.className = 'flex items-start space-x-2';
            li.innerHTML = '<span class="text-emerald-500 font-bold">✓</span> <span>' + benefit + '</span>';
            benefitsContainer.appendChild(li);
        });

        // WhatsApp Link
        const waNumber = '{{ $site_settings['developer_whatsapp'] ?? '6281234567890' }}';
        const waUrl = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(data.waMsg);
        document.getElementById('proModalWaBtn').setAttribute('href', waUrl);

        modal.classList.remove('hidden');
    }

    function closeProModal() {
        document.getElementById('simsProModal').classList.add('hidden');
    }

    // Close on backdrop click
    document.getElementById('simsProModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeProModal();
        }
    });
</script>
