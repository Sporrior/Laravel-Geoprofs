<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Cache;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class TwoFactorControllerTest extends DuskTestCase
{
    public function testGenerate2FACode()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                ->visit('/2fa/store')
                ->waitForText('2FA code generated successfully')
                ->assertSee('2FA code generated successfully');

            $code = Cache::get('2fa_code_' . $user->id);
            $this->assertNotNull($code);
        });
    }

    public function testVerify2FACode()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $code = random_int(100000, 999999);
            Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));

            $browser->loginAs($user)
                ->visit('/2fa/verify')
                ->type('2fa_code', $code)
                ->press('Verify')
                ->assertPathIs('/dashboard')
                ->assertSee('2FA verification successful!');
        });
    }

    public function testIncorrect2FACode()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $correctCode = random_int(100000, 999999);
            Cache::put('2fa_code_' . $user->id, $correctCode, now()->addMinutes(10));

            $browser->loginAs($user)
                ->visit('/2fa/verify')
                ->type('2fa_code', '123456')
                ->press('Verify')
                ->assertPathIs('/2fa/verify')
                ->assertSee('The code you entered is incorrect. Please try again.');
        });
    }
}