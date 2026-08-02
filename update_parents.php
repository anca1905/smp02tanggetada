<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$students = \App\Models\Student::whereNull('parent_password')->get();
foreach ($students as $student) {
    $student->parent_password = bcrypt('ortu' . $student->nis);
    $student->save();
    echo "Updated parent password for NIS: " . $student->nis . "\n";
}
echo "Done!\n";
