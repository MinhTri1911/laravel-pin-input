<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testGeneratePinCode(): void
    {
        $code = User::genratePinCode();

        $this->assertEquals(strlen($code), env('PIN_CODE_LENGTH'));
        $this->assertTrue(is_numeric($code));
    }
}
