<?php

namespace Database\Factories;

use App\Models\Ppdb;
use Illuminate\Database\Eloquent\Factories\Factory;

class PpdbFactory extends Factory
{
    protected $model = Ppdb::class;

    public function definition(): array
    {
        return [
            'no_registrasi' => 'PPDB-' . $this->faker->unique()->numberBetween(1000, 9999),
            'nama_lengkap' => $this->faker->name(),
            'nisn' => $this->faker->numerify('##########'),
            'nik' => $this->faker->numerify('################'),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'alamat' => $this->faker->address(),
            'asal_sekolah' => 'SMPN 1 Jakarta',
            'tahun_lulus' => '2024',
            'nama_ayah' => $this->faker->name(),
            'nama_ibu' => $this->faker->name(),
            'no_hp' => $this->faker->phoneNumber(),
            'jurusan_pilihan' => 'RPL',
            'status_pendaftaran' => 'Pending',
            'tanggal_daftar' => now(),
        ];
    }
}
