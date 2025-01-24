<?php

namespace Tests\Unit\damien;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TwoFactorControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ]);
    }

    public function test_opslaan_van_2fa_code_genereert_en_slaat_code_op()
    {
        $response = $this->postJson('/api/store-2fa-code');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'code'])
            ->assertJson(['status' => 'success', 'message' => '2FA-code succesvol gegenereerd']);

        $this->assertNotNull(Cache::get('2fa_code'));
    }

    public function test_verkrijg_2fa_code_genereert_nieuwe_code_indien_afwezig()
    {
        Cache::forget('2fa_code');

        $response = $this->getJson('/api/get-2fa-code');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'code'])
            ->assertJson(['status' => 'success']);

        $this->assertNotNull(Cache::get('2fa_code'));
    }

    public function test_2fa_code_verificatie_succesvol()
    {
        $code = random_int(100000, 999999);
        Cache::put('2fa_code', $code, now()->addMinutes(10));

        $response = $this->postJson('/verify-2fa', ['2fa_code' => $code]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success', 'message' => '2FA-verificatie succesvol.']);

        $this->assertNull(Cache::get('2fa_code'));
    }
}
