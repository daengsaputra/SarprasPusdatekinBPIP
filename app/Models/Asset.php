<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    public const KIND_LOANABLE = 'loanable';
    public const KIND_INVENTORY = 'inventory';

    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'kind',
        'quantity_total',
        'quantity_available',
        'status',
        'photo',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
