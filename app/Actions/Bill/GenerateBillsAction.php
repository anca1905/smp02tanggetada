<?php

namespace App\Actions\Bill;

use App\Models\Bill;
use App\Models\Student;

class GenerateBillsAction
{
    /**
     * Mengeksekusi pembuatan tagihan bulanan untuk seluruh siswa secara massal dan cepat (Batch Insert).
     *
     * @param  array  $data  Data validasi yang berisi: month, amount, due_date
     * @return string Judul tagihan (title) yang selesai dibuat
     */
    public function execute(array $data): string
    {
        $title = 'SPP '.$data['month'];
        $now = now();

        // 1. Mengambil hanya kolom ID siswa untuk menghemat konsumsi memori RAM server
        $studentIds = Student::pluck('id');

        $records = [];
        foreach ($studentIds as $id) {
            $records[] = [
                'student_id' => $id,
                'title' => $title,
                'type' => 'SPP Bulanan',
                'amount' => $data['amount'],
                'due_date' => $data['due_date'],
                'status' => 'unpaid',
                // Karena kita memakai model Bill::insert() batch kueri, timestamps harus kita pasang secara manual
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 2. Mengeksekusi simpan massal per 500 baris agar tidak melampaui limit eksekusi database (Safe & Super Fast)
        foreach (array_chunk($records, 500) as $chunk) {
            Bill::insert($chunk);
        }

        return $title;
    }
}
