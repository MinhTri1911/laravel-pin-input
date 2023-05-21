<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    private const DEFAULT_PIN_LENGTH = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pin_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function genratePinCode(): string
    {
        $counter = 0;
        $pin = '';

        while ($counter < env('PIN_CODE_LENGTH', self::DEFAULT_PIN_LENGTH)) {
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $counter++;
        }

        return $pin;
    }

    public function setPinCode(): string
    {
        $this->attributes['pin_code'] = self::genratePinCode();

        return $this->attributes['pin_code'];
    }

    public function getPinCode(): ?string
    {
        return $this->attributes['pin_code'];
    }

    public function clearPinCode(): void
    {
        $this->attributes['pin_code'] = null;
    }
}
