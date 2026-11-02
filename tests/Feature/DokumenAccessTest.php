<?php

namespace Tests\Feature;

use Tests\TestCase;

class DokumenAccessTest extends TestCase
{
    /**
     * Test accessing default dokumen hub.
     */
    public function test_can_access_dokumen_hub(): void
    {
        $response = $this->get('/dokumen');

        $response->assertStatus(200);
        $content = file_get_contents($response->getFile()->getPathname());
        $this->assertStringContainsString('SMP Negeri 2 Tanggetada', $content);
        $this->assertStringContainsString('penawaran-sims-enterprise-pro.html', $content);
    }

    /**
     * Test accessing the new penawaran document.
     */
    public function test_can_access_penawaran_enterprise_pro_document(): void
    {
        $response = $this->get('/dokumen/penawaran-sims-enterprise-pro.html');

        $response->assertStatus(200);
        $content = file_get_contents($response->getFile()->getPathname());
        $this->assertStringContainsString('Surat Penawaran SIMS Enterprise PRO & Kontrak 1 Tahun', $content);
        $this->assertStringContainsString('Rp 10.000.000', $content);
        $this->assertStringContainsString('Rp 2.500.000', $content);
        $this->assertStringContainsString('Rp 12.500.000', $content);
        $this->assertStringContainsString('Kartu Siswa PVC RFID', $content);
        $this->assertStringContainsString('USB RFID Reader', $content);
    }

    /**
     * Test path traversal is prevented.
     */
    public function test_path_traversal_is_blocked(): void
    {
        $response = $this->get('/dokumen/../.env');

        $response->assertStatus(404);
    }
}
