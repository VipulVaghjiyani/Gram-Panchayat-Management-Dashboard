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

class Bank extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'account_name',
        'account_number',
        'ifcs_code',
        'branch',
        'opening_balance',
        'created_by'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'banks';

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
     * Get the user associated with the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bankTransaction(): HasMany
    {
        return $this->hasMany(BankTransaction::class, 'bank_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->full_name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Bank')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Bank";
            });
    }
}
