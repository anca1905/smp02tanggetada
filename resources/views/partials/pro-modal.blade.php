{{-- Modal Penawaran & Aktivasi Paket SIMS Enterprise PRO (Sekali Bayar / All-In-One Lifetime) --}}
@php
    $devWa = $site_settings['popup_wa_number'] ?? ($site_settings['developer_whatsapp'] ?? '0853465489992');
    $cleanWa = preg_replace('/[^0-9]/', '', (string)$devWa);
    if (str_starts_with($cleanWa, '0')) {
        $cleanWa = '62' . substr($cleanWa, 1);
    }
@endphp

<div id="simsProModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden transition-all duration-300" style="background-color: rgba(15, 23, 42, 0.8); backdrop-filter: blur(5px);">
    <div class="relative w-full max-w-xl overflow-hidden bg-white rounded-2xl shadow-2xl border border-gray-200" style="animation: modalFadeIn 0.2s ease-out; max-height: 92vh; display: flex; flex-col; overflow-y: auto;">
        
        {{-- Header Solid Navy Bersih & Elegan --}}
        <div style="background-color: #1e3a8a; padding: 20px 24px; color: #ffffff; width: 100%;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="background-color: #fef08a; color: #854d0e; padding: 3px 10px; border-radius: 9999px; font-size: 11px; font-weight: 800; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                        👑 SIMS ENTERPRISE PRO
                    </span>
                    <span style="background-color: rgba(255,255,255,0.15); color: #cbd5e1; border: 1px solid rgba(255,255,255,0.25); padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 700;">
                        LISENSI PERMANEN (SEKALI BAYAR)
                    </span>
                </div>
                <button type="button" onclick="closeProModal()" style="color: #94a3b8; background: transparent; border: none; font-size: 22px; line-height: 1; cursor: pointer; padding: 4px 8px; border-radius: 6px;" onmouseover="this.style.color='#ffffff'; this.style.backgroundColor='rgba(255,255,255,0.1)'" onmouseout="this.style.color='#94a3b8'; this.style.backgroundColor='transparent'">
                    ✕
                </button>
            </div>
            
            <div style="display: flex; align-items: center; gap: 14px;">
                <div id="proModalIcon" style="width: 48px; height: 48px; min-width: 48px; border-radius: 12px; background-color: rgba(255, 255, 255, 0.15); border: 1.5px solid rgba(255, 255, 255, 0.25); display: flex; align-items: center; justify-content: center; font-size: 26px;">
                    ✨
                </div>
                <div>
                    <h3 id="proModalTitle" style="color: #ffffff; font-size: 17.5px; font-weight: 700; line-height: 1.35; margin: 0;">
                        Paket SIMS Enterprise PRO
                    </h3>
                    <p style="color: #dbeafe; font-size: 12.5px; margin-top: 3px; margin-bottom: 0; line-height: 1.4;">
                        1x Aktivasi Sekali Bayar &mdash; <strong style="color: #fef08a;">Semua Fitur PRO Terbuka Tanpa Batas Selamanya</strong>
                    </p>
                </div>
            </div>
        </div>

        {{-- Body Content --}}
        <div style="padding: 22px 24px; background-color: #ffffff; width: 100%;">
            
            {{-- Notifikasi Khusus Fitur yang Diklik --}}
            <div style="background-color: #eff6ff; border-left: 4px solid #2563eb; padding: 10px 14px; border-radius: 0 10px 10px 0; margin-bottom: 16px;">
                <p id="proModalDesc" style="color: #1e3a8a; font-size: 13px; font-weight: 600; margin: 0; line-height: 1.5;">
                    Fitur ini termasuk dalam Paket Lengkap SIMS Enterprise PRO.
                </p>
            </div>

            {{-- Daftar Semua Modul yang Terbuka Sekaligus --}}
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px; margin-bottom: 16px;">
                <div style="color: #1e293b; font-size: 11.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
                    <span style="display: flex; align-items: center; gap: 6px;">
                        <span style="color: #16a34a; font-weight: 900; font-size: 14px;">✓</span> SEMUA 5 FITUR PRO TERBUKA SEKALIGUS:
                    </span>
                    <span style="color: #2563eb; font-size: 11px; font-weight: 700; text-transform: none;">All-in-One Package</span>
                </div>
                
                <ul id="proModalBenefits" style="list-style: none; padding: 0; margin: 0; space-y: 2;">
                    <li style="display: flex; align-items: flex-start; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.45; margin-bottom: 7px;">
                        <span style="color: #16a34a; font-weight: 800; font-size: 13px;">✓</span>
                        <span><strong>Presensi Digital Guru & GTK:</strong> Rekap kehadiran dinas siap cetak untuk syarat Tunjangan Sertifikasi & TPP.</span>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.45; margin-bottom: 7px;">
                        <span style="color: #16a34a; font-weight: 800; font-size: 13px;">✓</span>
                        <span><strong>Manajemen SPP & Tagihan Siswa:</strong> Pembukuan iuran komite, kuitansi digital sah & laporan tunggakan transparan.</span>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.45; margin-bottom: 7px;">
                        <span style="color: #16a34a; font-weight: 800; font-size: 13px;">✓</span>
                        <span><strong>CBT Ujian Online Bebas Kertas:</strong> Ujian PTS/PAS via HP/komputer anti-curang, hemat jutaan rupiah biaya cetak kertas.</span>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.45; margin-bottom: 7px;">
                        <span style="color: #16a34a; font-weight: 800; font-size: 13px;">✓</span>
                        <span><strong>Kelulusan & SKL Online Mandiri:</strong> Pengumuman kelulusan terpadu, cetak Surat Kelulusan resmi ber-QR Code anti-pemalsuan.</span>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.45;">
                        <span style="color: #16a34a; font-weight: 800; font-size: 13px;">✓</span>
                        <span><strong>WhatsApp Gateway Notifikasi:</strong> Broadcast pengumuman dan kirim notifikasi presensi otomatis langsung ke HP orang tua.</span>
                    </li>
                </ul>
            </div>

            {{-- Box Harga Sekali Bayar & Anggaran BOS (High Contrast) --}}
            <div style="background-color: #fffbeb; border: 1.5px solid #fde68a; border-radius: 14px; padding: 14px 16px; margin-bottom: 18px;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <div style="color: #92400e; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">
                            BIAYA AKTIVASI LISENSI SEKOLAH:
                        </div>
                        <div id="proModalPrice" style="color: #78350f; font-size: 22px; font-weight: 800; font-family: monospace; margin-top: 2px;">
                            <span style="text-decoration: line-through; font-size: 14px; color: #9ca3af; font-weight: normal; margin-right: 6px;">Rp 4.500.000</span>
                            Rp 2.500.000 <span style="font-size: 11px; font-family: sans-serif; font-weight: 700; color: #15803d; background: #dcfce7; padding: 2px 8px; border-radius: 6px; margin-left: 4px;">SEKALI BAYAR SELAMANYA</span>
                        </div>
                        <p style="color: #b45309; font-size: 11px; margin: 3px 0 0 0; font-weight: 500;">
                            Tanpa biaya bulanan lagi. Seluruh menu dan pembaruan sistem aktif permanen.
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <div style="background-color: #dcfce7; color: #15803d; border: 1.5px solid #86efac; border-radius: 8px; padding: 6px 12px; font-size: 11.5px; font-weight: 700; white-space: nowrap; display: inline-flex; align-items: center; gap: 4px;">
                            <span style="font-weight: 900;">✓</span> SPJ DANA BOS READY
                        </div>
                    </div>
                </div>
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #fcd34d; font-size: 11.5px; color: #92400e; line-height: 1.4; display: flex; align-items: flex-start; gap: 6px;">
                    <span style="color: #d97706; font-size: 13px; line-height: 1;">ℹ️</span>
                    <span>Biaya dapat dibayarkan 100% sah melalui <strong>DANA BOS Reguler</strong> (Kwitansi resmi, BAST, dan Faktur Pajak lengkap disediakan).</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" onclick="closeProModal()" style="width: 32%; padding: 11px 16px; font-size: 13px; font-weight: 600; color: #475569; background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 12px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#e2e8f0'" onmouseout="this.style.backgroundColor='#f1f5f9'">
                    Nanti Saja
                </button>
                <a id="proModalWaBtn" href="#" target="_blank" style="width: 68%; padding: 11px 18px; font-size: 13px; font-weight: 700; color: #ffffff !important; background-color: #16a34a !important; border: 1px solid #15803d; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; box-shadow: 0 4px 14px rgba(22, 163, 74, 0.4); cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#15803d'" onmouseout="this.style.backgroundColor='#16a34a'">
                    <svg style="width: 18px; height: 18px; fill: #ffffff; flex-shrink: 0;" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <span style="color: #ffffff !important; font-weight: 700; white-space: nowrap;">Aktivasi Paket Lengkap (WA)</span>
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
    const proFeatureNames = {
        'absensi_guru': {
            name: 'Presensi Guru & Pegawai GTK',
            icon: '👨‍🏫',
            info: 'Kelola kehadiran dinas dan rekap sertifikasi guru tanpa ribet manual.'
        },
        'spp_billing': {
            name: 'Tagihan & SPP Siswa',
            icon: '💳',
            info: 'Kelola pembukuan iuran komite, seragam, dan SPP dengan bukti kuitansi digital.'
        },
        'cbt_exam': {
            name: 'CBT Ujian Online Bebas Kertas',
            icon: '📝',
            info: 'Ujian semester aman anti-curang berbasis gawai sekolah tanpa biaya fotokopi soal.'
        },
        'kelulusan_skl': {
            name: 'Kelulusan & SKL Online Mandiri',
            icon: '🎓',
            info: 'Pengumuman kelulusan serentak dan cetak SKL resmi ber-QR Code anti-pemalsuan.'
        },
        'whatsapp_gateway': {
            name: 'WhatsApp Gateway ke Orang Tua',
            icon: '💬',
            info: 'Notifikasi absensi dan pengumuman sekolah langsung masuk ke WhatsApp orang tua.'
        }
    };

    function openProModal(featureKey) {
        const modal = document.getElementById('simsProModal');
        const feature = proFeatureNames[featureKey] || {
            name: 'Modul SIMS Enterprise PRO',
            icon: '👑',
            info: 'Tingkatkan efisiensi dan transparansi manajemen sekolah Anda.'
        };

        document.getElementById('proModalTitle').innerText = 'Aktivasi: ' + feature.name;
        document.getElementById('proModalIcon').innerText = feature.icon;
        document.getElementById('proModalDesc').innerHTML = 'Anda mengeklik <strong>' + feature.name + '</strong>. ' + feature.info + ' Fitur ini termasuk dalam <strong>Paket Lengkap SIMS PRO Sekali Bayar</strong> (Semua 5 fitur terbuka selamanya).';

        // Dynamic WhatsApp negotiation link
        const waNumber = '{{ $cleanWa }}';
        const waMsg = 'Halo Skillance / Tim Pengembang SIMS, kami dari SMP Negeri 2 Tanggetada berminat untuk aktivasi Paket Lengkap SIMS Enterprise PRO (Sekali Bayar All-in-One). Kami tertarik untuk membuka fitur ' + feature.name + ' serta modul PRO lainnya. Mohon informasi prosedur aktivasi dan SPJ BOS nya.';
        const waUrl = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(waMsg);
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
