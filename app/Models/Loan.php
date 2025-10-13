<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'batch_code',
        'asset_id',
        'borrower_name',
        'borrower_contact',
        'unit',
        'quantity',
        'loan_date',
        'return_date_planned',
        'return_date_actual',
        'status',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'return_date_planned' => 'date',
        'return_date_actual' => 'date',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
