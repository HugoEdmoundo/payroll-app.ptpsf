<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SystemSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove all komponen_gaji_labels from system_settings
        SystemSetting::where('group', 'komponen_gaji_labels')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore komponen_gaji_labels if needed
        $labels = [
            ['group' => 'komponen_gaji_labels', 'key' => 'gaji_pokok', 'value' => 'Gaji Pokok', 'order' => 1],
            ['group' => 'komponen_gaji_labels', 'key' => 'tunjangan_prestasi', 'value' => 'Tunjangan Prestasi', 'order' => 2],
            ['group' => 'komponen_gaji_labels', 'key' => 'tunjangan_konjungtur', 'value' => 'Tunjangan Konjungtur', 'order' => 3],
            ['group' => 'komponen_gaji_labels', 'key' => 'benefit_ibadah', 'value' => 'Benefit Ibadah', 'order' => 4],
            ['group' => 'komponen_gaji_labels', 'key' => 'benefit_komunikasi', 'value' => 'Benefit Komunikasi', 'order' => 5],
            ['group' => 'komponen_gaji_labels', 'key' => 'benefit_operasional', 'value' => 'Benefit Operasional', 'order' => 6],
            ['group' => 'komponen_gaji_labels', 'key' => 'reward', 'value' => 'Reward', 'order' => 7],
            ['group' => 'komponen_gaji_labels', 'key' => 'bpjs_kesehatan', 'value' => 'BPJS Kesehatan', 'order' => 8],
            ['group' => 'komponen_gaji_labels', 'key' => 'bpjs_kecelakaan_kerja', 'value' => 'BPJS Kecelakaan Kerja', 'order' => 9],
            ['group' => 'komponen_gaji_labels', 'key' => 'bpjs_kematian', 'value' => 'BPJS Kematian', 'order' => 10],
            ['group' => 'komponen_gaji_labels', 'key' => 'bpjs_jht', 'value' => 'BPJS JHT', 'order' => 11],
            ['group' => 'komponen_gaji_labels', 'key' => 'bpjs_jp', 'value' => 'BPJS JP', 'order' => 12],
            ['group' => 'komponen_gaji_labels', 'key' => 'tabungan_koperasi', 'value' => 'Tabungan Koperasi', 'order' => 13],
            ['group' => 'komponen_gaji_labels', 'key' => 'koperasi', 'value' => 'Koperasi', 'order' => 14],
            ['group' => 'komponen_gaji_labels', 'key' => 'kasbon', 'value' => 'Kasbon', 'order' => 15],
            ['group' => 'komponen_gaji_labels', 'key' => 'umroh', 'value' => 'Umroh', 'order' => 16],
            ['group' => 'komponen_gaji_labels', 'key' => 'kurban', 'value' => 'Kurban', 'order' => 17],
            ['group' => 'komponen_gaji_labels', 'key' => 'mutabaah', 'value' => 'Mutabaah', 'order' => 18],
            ['group' => 'komponen_gaji_labels', 'key' => 'potongan_absensi', 'value' => 'Potongan Absensi', 'order' => 19],
            ['group' => 'komponen_gaji_labels', 'key' => 'potongan_kehadiran', 'value' => 'Potongan Kehadiran', 'order' => 20],
        ];

        foreach ($labels as $label) {
            SystemSetting::create($label);
        }
    }
};
