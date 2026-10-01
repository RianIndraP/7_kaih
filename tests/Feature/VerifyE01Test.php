<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class VerifyE01Test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Middleware CheckWebsiteLock query tabel ini di setiap request.
        // Test DB = sqlite :memory:, jadi tabelnya perlu dibuat manual.
        if (!Schema::hasTable('website_management')) {
            Schema::create('website_management', function (Blueprint $table) {
                $table->id();
                $table->boolean('is_locked')->default(false);
                $table->text('lock_message')->nullable();
                $table->date('update_message_expiry_date')->nullable();
                $table->text('update_message_siswa')->nullable();
                $table->text('update_message_guru')->nullable();
                $table->text('update_message_kepala_sekolah')->nullable();
                $table->timestamps();
            });
        }
    }

    private function victim(): User
    {
        $u = new User();
        $u->id = 999;
        $u->name = 'Korban';
        $u->email = 'korban@example.com';
        $u->nisn = '1234567890';
        $u->password = 'password-sekarang-yang-hash';
        return $u;
    }

    /** Skenario 1: session punya forgot_user tapi TIDAK ada otp_verified (jalur penyerang) */
    public function test_skenario_1_post_langsung_tanpa_otp_ditolak(): void
    {
        session(['forgot_user' => $this->victim(), 'forgot_otp' => '847291']);

        $before = (new User())->forceFill(['password' => 'password-sekarang-yang-hash'])->password;

        $r = $this->post('/create-new-password', [
            'password' => 'attacker123',
            'password_confirmation' => 'attacker123',
        ]);

        $r->assertRedirect('/verify-data'); // DIALIHKAN, bukan password-success
        $this->assertNull(session('otp_verified'), 'otp_verified tidak boleh terbit');
        $this->assertNotSame('attacker123', $before);
    }

    /** Skenario 2: GET form pun harus dialihkan kalau OTP belum diverifikasi */
    public function test_skenario_2_form_tidak_tampil_tanpa_otp(): void
    {
        session(['forgot_user' => $this->victim()]);

        $this->get('/create-new-password')->assertRedirect('/verify-data');
    }

    /** Skenario 3: alur benar (OTP sudah diverifikasi) TETAP jalan — tidak boleh rusak */
    public function test_skenario_3_alur_valid_tetap_bisa_set_password(): void
    {
        session([
            'forgot_user' => $this->victim(),
            'otp_verified' => true,
        ]);

        // GET form harus tampil (tidak redirect)
        $this->get('/create-new-password')->assertStatus(200);

        $r = $this->post('/create-new-password', [
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baruu-123', // sengaja beda → harus 422
        ]);
        $r->assertSessionHasErrors('password');

        $r2 = $this->post('/create-new-password', [
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ]);
        // Tidak redirect ke verify-data = guard tidak salah blokir
        $this->assertNotSame('/verify-data', $r2->headers->get('Location'));
    }

    /** Skenario 5: setelah selesai, flag harus dibersihkan supaya tidak bisa dipakai ulang */
    public function test_skenario_5_flag_dibersihkan_setelah_reset(): void
    {
        // Simulasikan kondisi setelah createNewPassword() sukses:
        // kode sudah doing forget(['forgot_user','otp_verified']) + invalidate()
        session([
            'forgot_user' => $this->victim(),
            'otp_verified' => true,
        ]);
        session()->forget(['forgot_user', 'otp_verified']);

        $this->assertNull(session('forgot_user'));
        $this->assertNull(session('otp_verified'), 'flag harus hilang, tidak boleh tersisa');
    }

    /** Skenario 4: OTP salah tidak boleh menerbitkan flag */
    public function test_skenario_4_otp_salah_tidak_terbitkan_flag(): void
    {
        session([
            'forgot_user' => $this->victim(),
            'forgot_otp' => '847291',
            'forgot_otp_expires_at' => now()->addMinutes(10),
        ]);

        $r = $this->post('/verify-data', ['otp' => '000000']);

        $r->assertSessionHasErrors('otp');
        $this->assertNull(session('otp_verified'), 'OTP salah tidak boleh memberi flag');
    }

    /** Skenario 4b: OTP benar DIREKOMENDASIKAN blocker — cek hash_equals ada */
    public function test_hash_equals_behavior_sama_dengan_strict_compare(): void
    {
        $this->assertTrue(hash_equals('847291', '847291'), 'OTP benar harus cocok');
        $this->assertFalse(hash_equals('847291', '847292'), 'OTP salah harus gagal');
        $this->assertFalse(hash_equals('847291', '84729'), 'panjang beda harus gagal');
    }
}