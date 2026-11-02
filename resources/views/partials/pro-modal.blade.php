{{-- Modal Penawaran & Biaya Langganan Fitur PRO (Ultra Clean & High Contrast) --}}
@php
    $devWa = $site_settings['popup_wa_number'] ?? ($site_settings['developer_whatsapp'] ?? '0853465489992');
    $cleanWa = preg_replace('/[^0-9]/', '', (string)$devWa);
    if (str_starts_with($cleanWa, '0')) {
        $cleanWa = '62' . substr($cleanWa, 1);
    }
@endphp

<div id="simsProModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden transition-all duration-300" style="background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px);">
    <div class="relative w-full max-w-lg overflow-hidden bg-white rounded-2xl shadow-2xl border border-gray-200" style="animation: modalFadeIn 0.2s ease-out;">
        
        {{-- Header Solid Navy Bersih (Bukan gradasi gelap pudar) --}}
        <div style="background-color: #1e3a8a; padding: 20px 24px; color: #ffffff;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="background-color: #fef08a; color: #854d0e; padding: 3px 10px; border-radius: 9999px; font-size: 11px; font-weight: 800; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                        👑 SIMS PRO
                    </span>
                    <span id="proModalCategory" style="color: #cbd5e1; font-size: 12px; font-weight: 600; letter-spacing: 0.03em;">
                        Add-on Khusus
                    </span>
                </div>
                <button type="button" onclick="closeProModal()" style="color: #94a3b8; background: transparent; border: none; font-size: 20px; line-height: 1; cursor: pointer; padding: 4px 8px; border-radius: 6px;" onmouseover="this.style.color='#ffffff'; this.style.backgroundColor='rgba(255,255,255,0.1)'" onmouseout="this.style.color='#94a3b8'; this.style.backgroundColor='transparent'">
                    ✕
                </button>
            </div>
            
            <div style="display: flex; align-items: center; gap: 14px;">
                <div id="proModalIcon" style="width: 48px; height: 48px; min-width: 48px; border-radius: 12px; background-color: rgba(255, 255, 255, 0.15); border: 1.5px solid rgba(255, 255, 255, 0.25); display: flex; align-items: center; justify-content: center; font-size: 26px;">
                    ✨
                </div>
                <div>
                    <h3 id="proModalTitle" style="color: #ffffff; font-size: 17px; font-weight: 700; line-height: 1.35; margin: 0;">
                        Nama Fitur PRO
                    </h3>
                    <p style="color: #dbeafe; font-size: 12px; margin-top: 3px; margin-bottom: 0; line-height: 1.4;">
                        Tingkatkan digitalisasi dan efisiensi manajemen sekolah Anda
                    </p>
                </div>
            </div>
        </div>

        {{-- Body Content --}}
        <div style="padding: 22px 24px; background-color: #ffffff;">
            
            {{-- Deskripsi Fitur --}}
            <p id="proModalDesc" style="color: #334155; font-size: 13.5px; line-height: 1.6; margin-top: 0; margin-bottom: 16px;">
                Deskripsi fitur lengkap akan tampil di sini.
            </p>

            {{-- Keunggulan / Manfaat --}}
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin-bottom: 16px;">
                <div style="color: #1e293b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                    <span style="color: #16a34a; font-weight: 900; font-size: 13px;">✓</span> MANFAAT NYATA UNTUK SEKOLAH:
                </div>
                <ul id="proModalBenefits" style="list-style: none; padding: 0; margin: 0;">
                    {{-- Diisi secara dinamis via JS --}}
                </ul>
            </div>

            {{-- Box Harga & Anggaran BOS (Warna Emas/Kuning Jelas Berkontras) --}}
            <div style="background-color: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px; padding: 14px 16px; margin-bottom: 18px;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                    <div>
                        <div style="color: #92400e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                            BIAYA BERLANGGANAN ADD-ON:
                        </div>
                        <div id="proModalPrice" style="color: #78350f; font-size: 22px; font-weight: 800; font-family: monospace; margin-top: 2px;">
                            Rp 150.000 <span style="font-size: 12px; font-family: sans-serif; font-weight: normal; color: #b45309;">/ bulan</span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="background-color: #dcfce7; color: #15803d; border: 1.5px solid #86efac; border-radius: 8px; padding: 6px 12px; font-size: 11.5px; font-weight: 700; white-space: nowrap; display: inline-flex; align-items: center; gap: 4px;">
                            <span style="font-weight: 900;">✓</span> Dana BOS Ready
                        </div>
                    </div>
                </div>
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #fcd34d; font-size: 11.5px; color: #92400e; line-height: 1.4; display: flex; align-items: flex-start; gap: 6px;">
                    <span style="color: #d97706; font-size: 13px; line-height: 1;">ℹ️</span>
                    <span>Biaya dapat dibayarkan sah melalui <strong>DANA BOS Reguler</strong> (Standar Pembiayaan Langganan Daya & Jasa).</span>
                </div>
            </div>

            {{-- Action Buttons (Tombol WhatsApp Kontras Tinggi & Teks Jelas) --}}
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" onclick="closeProModal()" style="width: 35%; padding: 11px 16px; font-size: 13px; font-weight: 600; color: #475569; background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 12px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#e2e8f0'" onmouseout="this.style.backgroundColor='#f1f5f9'">
                    Nanti Saja
                </button>
                <a id="proModalWaBtn" href="#" target="_blank" style="width: 65%; padding: 11px 18px; font-size: 13px; font-weight: 700; color: #ffffff !important; background-color: #16a34a !important; border: 1px solid #15803d; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; box-shadow: 0 4px 14px rgba(22, 163, 74, 0.4); cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#15803d'" onmouseout="this.style.backgroundColor='#16a34a'">
                    <svg style="width: 18px; height: 18px; fill: #ffffff; flex-shrink: 0;" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <span style="color: #ffffff !important; font-weight: 700; white-space: nowrap;">Hubungi Pengembang (WA)</span>
                </a>
            </div>

        </div>

    </div>
</div>

<style>
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.96);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<script>
    const proFeaturesCatalog = {
        'absensi_guru': {
            title: 'Presensi Digital Guru & Tenaga Kependidikan',
            category: 'SDM & GTK',
            icon: '👨‍🏫',
            price: 'Rp 150.000 <span style="font-size: 12px; font-family: sans-serif; font-weight: normal; color: #b45309;">/ bulan</span>',
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
            price: 'Rp 150.000 <span style="font-size: 12px; font-family: sans-serif; font-weight: normal; color: #b45309;">/ bulan</span>',
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
            price: 'Rp 200.000 <span style="font-size: 12px; font-family: sans-serif; font-weight: normal; color: #b45309;">/ bulan</span>',
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
            price: 'Rp 150.000 <span style="font-size: 12px; font-family: sans-serif; font-weight: normal; color: #b45309;">/ bulan</span>',
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
            price: 'Rp 250.000 <span style="font-size: 12px; font-family: sans-serif; font-weight: normal; color: #b45309;">/ bulan</span>',
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

        // Render benefits with explicit styling
        const benefitsContainer = document.getElementById('proModalBenefits');
        benefitsContainer.innerHTML = '';
        data.benefits.forEach(benefit => {
            const li = document.createElement('li');
            li.style.display = 'flex';
            li.style.alignItems = 'flex-start';
            li.style.gap = '8px';
            li.style.fontSize = '12.5px';
            li.style.color = '#334155';
            li.style.lineHeight = '1.45';
            li.style.marginBottom = '8px';
            li.innerHTML = '<span style="color: #16a34a; font-weight: 800; font-size: 13px; line-height: 1.2;">✓</span> <span>' + benefit + '</span>';
            benefitsContainer.appendChild(li);
        });

        // WhatsApp Link with dynamic phone number
        const waNumber = '{{ $cleanWa }}';
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
