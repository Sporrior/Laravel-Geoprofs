<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    private function maakTestGebruiker()
{
    $user = User::factory()->create([
        'password' => Hash::make('wachtwoord123'),
    ]);

    UserInfo::factory()->create([
        'id' => $user->id,
        'voornaam' => 'Test',
        'achternaam' => 'Gebruiker',
        'email' => 'testgebruiker@gmail.com', 
        'telefoon' => '0123456789',
        'account_locked' => false,
        'blocked_until' => null,
        'failed_login_attempts' => 0,
        'verlof_dagen' => 20,     
    ]);

    return $user;
}

    

    public function test_toont_inlogformulier()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function test_succesvolle_inlog()
    {
        $user = $this->maakTestGebruiker();

        $response = $this->post(route('login.submit'), [
            'email' => 'testgebruiker@gmail.com',
            'password' => 'wachtwoord123',
        ]);

        $response->assertRedirect(route('2fa.show'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_inlog_met_ongeldig_emailadres()
    {
        $response = $this->post(route('login.submit'), [
            'email' => 'nietbestaand@example.com',
            'password' => 'wachtwoord123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_inlog_met_ongeldig_wachtwoord()
    {
        $user = $this->maakTestGebruiker();

        $response = $this->post(route('login.submit'), [
            'email' => $user->email,
            'password' => 'foutwachtwoord',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_tijdelijke_blokkade_na_mislukte_pogingen()
    {
        $user = $this->maakTestGebruiker();
        $userInfo = $user->userInfo;
        $userInfo->update(['failed_login_attempts' => 2]);

        $response = $this->post(route('login.submit'), [
            'email' => $user->email,
            'password' => 'foutwachtwoord',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email' => 'Te veel mislukte inlogpogingen. Probeer het opnieuw over 5 minuten.']);

        $userInfo->refresh();
        $this->assertNotNull($userInfo->blocked_until);
        $this->assertGuest();
    }

    public function test_permanente_blokkade()
    {
        $user = $this->maakTestGebruiker();
        $userInfo = $user->userInfo;
        $userInfo->update(['account_locked' => true]);

        $response = $this->post(route('login.submit'), [
            'email' => $user->email,
            'password' => 'wachtwoord123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email' => 'Uw account is permanent geblokkeerd door meerdere mislukte inlogpogingen.']);
        $this->assertGuest();
    }
}
