<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Member extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'is_income_member',
        'is_expance_member',
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'email',
        'mobile',
        'alternate_number',
        'main_member_id',
        'house_id',
        'expense_id',
        /* 'address',
        'taluka',
        'district',
        'state',
        'country',
        'post_code', */
        'house_hold_type',
        'is_house_exist',
        'created_by',
        'customer_no',
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'members';

    /**
     * Get the user that owns the Gaam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the house that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class, 'house_id', 'id');
    }

    public function expense_members(): BelongsTo
    {
        return $this->belongsTo(ExpenseMember::class, 'expense_id', 'id');
    }

    /**
     * Get all of the comments for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function houses(): HasMany
    {
        return $this->hasMany(House::class, 'owner_member_id', 'id');
    }

    /**
     * Get all of the comments for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(MemberAddress::class, 'member_id', 'id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->middle_name . " " . $this->last_name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->full_name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Member')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Member";
            });
    }
}
