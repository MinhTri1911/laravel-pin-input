<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TwoFAControllerTest extends TestCase
{
    /**
     * @test
     */
    public function testAccess2faWithoutLogin(): void
    {
        $response = $this->get('/2fa');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function testAccess2faWithLoginSuccess(): void
    {
        DB::beginTransaction();
        $code = User::genratePinCode();

        $this->login($code);
        $response = $this->get('/2fa');

        $response->assertStatus(200);
        DB::rollBack();
    }

    /**
     * @test
     */
    public function testAccess2faWithLoginFail(): void
    {
        DB::beginTransaction();
        $this->login();

        $response = $this->get('/2fa');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        DB::rollBack();
    }

    private function login(?string $code = null): void
    {
        $user = User::create([
            'name' => 'test-user2',
            'email' => 'test-user2@gmail.com',
            'password' => Hash::make('12345678'),
            'pin_code' => $code,
        ]);

        $this->actingAs($user);
    }

    public function testAssessHomePageSuccess(): void
    {
        DB::beginTransaction();
        $code = User::genratePinCode();
        $this->login($code);
        $response = $this->get('/2fa');

        $response->assertSee([
            '2FA Verification',
        ]);
        $response->assertStatus(200);
        $response = $this->post('/2fa', [
            'code' => $code,
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'shouldRedirect' => true,
            'url' => route('home'),
        ]);
        DB::rollBack();
    }

    public function testAssessHomePageFail(): void
    {
        DB::beginTransaction();
        $code = User::genratePinCode();
        $this->login($code);
        $response = $this->get('/2fa');

        $response->assertStatus(200);
        $response = $this->post('/2fa', [
            'code' => '12345',
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'shouldRedirect' => false,
        ]);
        DB::rollBack();
    }
}
