<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Income extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'house_id',
        'member_id',
        'financial_year',
        'paid_date',
        'no_of_year',
        'income_category_id',
        'amount',
        'payment_type',
        'bank_name',
        'cheque_number',
        'transaction_number',
        'transaction_date',
        'to_date',
        'from_date',
        'is_taxable',
        'is_late_paid',
        'note',
        'created_by'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'incomes';

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
     * Get the member that owns the Income
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    /**
     * Get the member that owns the Income
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class, 'house_id', 'id');
    }

    /**
     * Get the incomeCatgory that owns the Income
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function incomeCatgory(): BelongsTo
    {
        return $this->belongsTo(IncomeCategory::class, 'income_category_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->full_name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Income')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Income";
            });
    }
}
