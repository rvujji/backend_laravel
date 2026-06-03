<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'email_verified',
        'phone_verified',
    ];

    public function getEmailVerifiedAttribute(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    public function getPhoneVerifiedAttribute(): bool
    {
        return ! is_null($this->phone_verified_at);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function workshops(): HasMany
    {
        return $this->hasMany(
            Workshop::class,
            'owner_id'
        );
    }

    public function workshopOfferingEnrollments(): HasMany
    {
        return $this->hasMany(
            WorkshopOfferingEnrollment::class,
            'student_id'
        );
    }

    public function sendPasswordResetNotification(
        $token
    ): void {

        $this->notify(
            new ResetPasswordNotification(
                $token
            )
        );
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(
            new VerifyEmailNotification()
        );
    }
}
