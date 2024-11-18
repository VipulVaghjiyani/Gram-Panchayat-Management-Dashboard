<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'picture',
        'mobile',
        'status',
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

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->middle_name . " " . $this->last_name;
    }

    /**
     * Get the user that owns the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany('App\Models\Role', 'id', 'role_id');
    }

    public function permissions()
    {
        return $this->hasMany('App\Models\Permission', 'role_id', 'role_id');
    }

    public function hasRolePermission($module)
    {
        if ($this->permissions()->where('module', $module)->first()) {
            return true;
        }
        return false;
    }

    public function hasRoleCRUDPermission($module,$permission)
    {
        if ($this->permissions()->where([['module', $module],[$permission,'on']])->first()) {
            return true;
        }
        return false;
    }

    public function getrole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->full_name ?? "";

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('User')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} User";
            });
    }
}
