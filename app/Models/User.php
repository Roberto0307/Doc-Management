<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasPanelShield, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function ownedSubProcesses()
    {
        return $this->hasMany(SubProcess::class, 'user_id');
    }

    public function subProcesses()
    {
        return $this->belongsToMany(SubProcess::class, 'user_has_sub_process');
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos útiles
    |--------------------------------------------------------------------------
    */

    public function isLeaderOfSubProcess(?int $subProcessId): bool
    {
        return SubProcess::where('id', $subProcessId)
            ->where('user_id', $this->id)
            ->exists();
    }

    public function validSubProcess($subProcessId): bool
    {
        return $this->subProcesses()->where('sub_process_id', $subProcessId)->exists();
    }

    public function leaderOfSubProcess(): ?SubProcess
    {
        return SubProcess::with('process')
            ->where('user_id', $this->id)
            ->first();
    }

    public function isActive(): bool
    {
        return (bool) $this->active;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->hasRole('super_admin')) {
            return true;
        }

        if (! $this->isActive()) {
            Auth::logout();

            Notification::make()
                ->title('Deactivated accounts')
                ->body('Your account has been deactivated. Contact the administrator.')
                ->danger()
                ->persistent()
                ->send();
        }

        return true;
    }
}
