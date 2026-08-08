<?php

namespace Tests\Unit\Public\Actions;

use App\Actions\Public\ProcessPpdbRegistrationAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessPpdbRegistrationActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_registration_number_and_saves_data()
    {
        $data = [
            'nama_lengkap' => 'John Doe',
            'nisn' => '1234567890',
            'nik' => '3201234567890001',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2005-01-01',
            'alamat' => 'Jl. Sudirman',
            'asal_sekolah' => 'SMPN 1 Jakarta',
            'tahun_lulus' => '2020',
            'nama_ayah' => 'Budi',
            'nama_ibu' => 'Siti',
            'no_hp' => '081234567890',
            'jurusan' => 'RPL',
        ];

        $action = new ProcessPpdbRegistrationAction;
        $noReg = $action->execute($data);

        $year = date('Y');
        // Menggunakan regex assertMatchesRegularExpression agar lebih robust jika id database melompat karena auto increment
        $this->assertMatchesRegularExpression("/^REG-{$year}-\d{4}$/", $noReg);

        $this->assertDatabaseHas('ppdb', [
            'no_registrasi' => $noReg,
            'nama_lengkap' => 'JOHN DOE',
            'nisn' => '1234567890',
            'nik' => '3201234567890001',
            'jenis_kelamin' => 'L',
            'asal_sekolah' => 'SMPN 1 JAKARTA',
            'jurusan_pilihan' => 'RPL',
        ]);

        $data2 = $data;
        $data2['nama_lengkap'] = 'Jane Doe';

        $noReg2 = $action->execute($data2);

        $this->assertMatchesRegularExpression("/^REG-{$year}-\d{4}$/", $noReg2);
        $this->assertNotEquals($noReg, $noReg2);
    }
}
