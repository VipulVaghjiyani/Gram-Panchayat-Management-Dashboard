<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'permanent_address',
        'is_same_as_permanent_address',
        'current_address',
        'gaam',
        'taluka',
        'district',
        'state',
        'country',
        'post_code',
    ];

    /**
     * Get the member that owns the MemberAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function getFullCurrentAddressAttribute()
    {
        if (!empty($this->current_address)) {
            return ($this->current_address ? $this->current_address . ", " : " ") . ($this->gaam ? $this->gaam . ", " : " ") . ($this->taluka ? $this->taluka . ", " : " ") . ($this->district ? $this->district . ", " : " ") . ($this->state ? $this->state . ", " : " ") . ($this->country ? $this->country . ", " : " ") . ($this->post_code ?  " - " .$this->post_code : " ");
            // return $this->current_address . ", " . $this->gaam . ", " . $this->taluka . ", " . $this->district . ", " . $this->state . ", " . $this->country . " - " . $this->post_code;
        }
    }

    public function getFullPermanentAddressAttribute()
    {
        if (!empty($this->permanent_address)) {
            return ($this->permanent_address ? $this->permanent_address . ", " : " ") . ($this->gaam ? $this->gaam . ", " : " ") . ($this->taluka ? $this->taluka . ", " : " ") . ($this->district ? $this->district . ", " : " ") . ($this->state ? $this->state . ", " : " ") . ($this->country ? $this->country . ", " : " ") . ($this->post_code ?  " - " .$this->post_code : " ");
            // return $this->permanent_address . ", " . $this->gaam . ", " . $this->taluka . ", " . $this->district . ", " . $this->state . ", " . $this->country . " - " . $this->post_code;
        }
    }
}
