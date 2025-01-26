<?php

namespace Tests\Feature\wessam;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class AccounttoevoegenControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_new_user_and_user_info()
    {
        $this->refreshDatabase();
    
        $role = Role::firstOrCreate(['role_name' => 'manager']); 
        $adminUser = User::factory()->create(); 
        $this->actingAs($adminUser);
    
        $data = [
            'voornaam' => 'John',
            'tussennaam' => 'van',
            'achternaam' => 'Doe',
            'telefoon' => '1234567890',
            'email' => 'john.doe@example.com', 
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $role->id,
        ];
    
        $response = $this->post(route('account-toevoegen.store'), $data);
        
        $response->assertRedirect(route('account-toevoegen.index'));
        $response->assertSessionHas('success', 'Gebruiker succesvol aangemaakt.');
    

        $this->assertDatabaseHas('users', [
            'id' => $adminUser->id,
        ]);
    
        // Controleer 'user_info' of  'email'bestaat
        $this->assertDatabaseHas('user_info', [
            'email' => 'john.doe@example.com',
            'voornaam' => 'John',
            'achternaam' => 'Doe',
            'role_id' => $role->id,
        ]);
    }
}
