<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class House extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'is_currently_living',
        'owner_member_id',
        'rental_member_id',
        'house_no',
        'address',
        'taluka',
        'district',
        'state',
        'country',
        'post_code',
        'total_members',
        'note',
        'created_by'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'houses';

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
     * Get the user that owns the House
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function income(): HasOne
    {
        return $this->hasOne(Income::class, 'house_id', 'id');
    }

    /**
     * Get all of the members for the House
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function houses(): HasMany
    {
        return $this->hasMany(HouseOwner::class, 'house_id', 'id');
    }

    public function getFullAddressAttribute()
    {
        if (!empty($this->address)) {
            return ($this->address ? $this->address . ", " : " ") . ($this->gaam ? $this->gaam . ", " : " ") . ($this->taluka ? $this->taluka . ", " : " ") . ($this->district ? $this->district . ", " : " ") . ($this->state ? $this->state . ", " : " ") . ($this->country ? $this->country . ", " : " ") . ($this->post_code ?  " - " .$this->post_code : " ");
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->full_name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('House')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} House";
            });
    }
}
