<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Missing XI classes
$xiClasses = [
    ['nama_kelas' => 'XI PPLG 1', 'guru_id' => null, 'color_index' => 4],
    ['nama_kelas' => 'XI PPLG 2', 'guru_id' => null, 'color_index' => 8],
    ['nama_kelas' => 'XI PPLG 3', 'guru_id' => null, 'color_index' => 9],
    ['nama_kelas' => 'XI TJKT 1', 'guru_id' => null, 'color_index' => 0],
    ['nama_kelas' => 'XI TJKT 2', 'guru_id' => null, 'color_index' => 8],
    ['nama_kelas' => 'XI TJKT 3', 'guru_id' => null, 'color_index' => 6],
    ['nama_kelas' => 'XI BP', 'guru_id' => null, 'color_index' => 10],
];

// Missing XII classes
$xiiClasses = [
    ['nama_kelas' => 'XII TJAT 1', 'guru_id' => null, 'color_index' => 2],
    ['nama_kelas' => 'XII TJAT 2', 'guru_id' => null, 'color_index' => 5],
    ['nama_kelas' => 'XII TKJ 1', 'guru_id' => null, 'color_index' => 10],
    ['nama_kelas' => 'XII TKJ 2', 'guru_id' => null, 'color_index' => 5],
    ['nama_kelas' => 'XII PF 1', 'guru_id' => null, 'color_index' => 0],
    ['nama_kelas' => 'XII PF 2', 'guru_id' => null, 'color_index' => 11],
    ['nama_kelas' => 'XII PPLG 1', 'guru_id' => null, 'color_index' => 4],
    ['nama_kelas' => 'XII PPLG 2', 'guru_id' => null, 'color_index' => 8],
    ['nama_kelas' => 'XII PPLG 3', 'guru_id' => null, 'color_index' => 9],
    ['nama_kelas' => 'XII TJKT 1', 'guru_id' => null, 'color_index' => 0],
    ['nama_kelas' => 'XII TJKT 2', 'guru_id' => null, 'color_index' => 8],
    ['nama_kelas' => 'XII TJKT 3', 'guru_id' => null, 'color_index' => 6],
    ['nama_kelas' => 'XII BP', 'guru_id' => null, 'color_index' => 10],
    ['nama_kelas' => 'XII RPL 3', 'guru_id' => null, 'color_index' => 3],
];

echo "Adding missing XI classes...\n";
foreach ($xiClasses as $class) {
    $exists = DB::table('kelas')->where('nama_kelas', $class['nama_kelas'])->exists();
    if (!$exists) {
        DB::table('kelas')->insert($class + ['created_at' => now(), 'updated_at' => now()]);
        echo "Added: {$class['nama_kelas']}\n";
    } else {
        echo "Skipped (already exists): {$class['nama_kelas']}\n";
    }
}

echo "\nAdding missing XII classes...\n";
foreach ($xiiClasses as $class) {
    $exists = DB::table('kelas')->where('nama_kelas', $class['nama_kelas'])->exists();
    if (!$exists) {
        DB::table('kelas')->insert($class + ['created_at' => now(), 'updated_at' => now()]);
        echo "Added: {$class['nama_kelas']}\n";
    } else {
        echo "Skipped (already exists): {$class['nama_kelas']}\n";
    }
}

echo "\nDone! Missing classes have been added.\n";
