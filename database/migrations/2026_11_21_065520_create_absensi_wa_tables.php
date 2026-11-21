<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel siswa (Data Master Siswa)
        Schema::create('siswa', function (Blueprint $table) {
            $table->integer('id_siswa', true); // INT(11) PRIMARY KEY Auto Increment
            $table->string('nisn', 10)->unique();
            $table->string('nama_siswa', 100);
            $table->string('kelas', 20);
            $table->string('nomor_ortu', 20);
        });

        // 2. Tabel pengaturan_jam
        Schema::create('pengaturan_jam', function (Blueprint $table) {
            $table->id();
            $table->time('jam_masuk');
            $table->time('jam_pulang');
        });

        // 3. Tabel hari_libur
        Schema::create('hari_libur', function (Blueprint $table) {
            $table->date('tanggal')->primary();
        });

        // 4. Tabel absensi
        Schema::create('absensi', function (Blueprint $table) {
            $table->integer('id_absensi', true); // INT(11) PRIMARY KEY Auto Increment
            $table->integer('id_siswa'); // FOREIGN KEY ke tabel siswa
            $table->dateTime('waktu_masuk');
            $table->dateTime('waktu_pulang')->nullable();
            $table->string('status', 20); // Hadir, Terlambat, Izin, Sakit, Alpa
            $table->string('cara_input', 30); // QR Code Scan, Manual Operator

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
        });

        // 5. Tabel izin
        Schema::create('izin', function (Blueprint $table) {
            $table->integer('id_izin', true); // INT(11) PRIMARY KEY Auto Increment
            $table->integer('id_siswa'); // FOREIGN KEY ke tabel siswa
            $table->string('keterangan', 50); // Sakit / Izin Acara Keluarga

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
        });

        // 6. Tabel log_notifikasi
        Schema::create('log_notifikasi', function (Blueprint $table) {
            $table->integer('id_log', true); // INT(11) PRIMARY KEY Auto Increment
            $table->string('nomor_tujuan', 20);
            $table->string('jenis_pesan', 50); // Masuk, Pulang, Izin, Terlambat, Alpa
            $table->text('isi_pesan');
            $table->string('status', 20); // terkirim, gagal, pending
            $table->integer('jumlah_percobaan')->default(0);
            $table->text('pesan_error')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_notifikasi');
        Schema::dropIfExists('izin');
        Schema::dropIfExists('absensi');
        Schema::dropIfExists('hari_libur');
        Schema::dropIfExists('pengaturan_jam');
        Schema::dropIfExists('siswa');
    }
};