<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Expense extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'member_id',
        'expense_member_id',
        'account_id',
        'financial_year',
        'date',
        'expense_category_id',
        'amount',
        'payment_type',
        'bank_name',
        'cheque_number',
        'transaction_number',
        'transaction_date',
        'note',
        'created_by'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'expenses';

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
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    /**
     * Get the incomeCatgory that owns the Income
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expenseCatgory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id', 'id');
    }

    /**
     * Get the member that owns the expenseMember
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expenseMember(): BelongsTo
    {
        return $this->belongsTo(ExpenseMember::class, 'expense_member_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->full_name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Expense')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Expense";
            });
    }
}
