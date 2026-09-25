<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'employee_id',
        'company_id',
        'contract_type',
        'start_date',
        'end_date',
        'salary',
        'working_hours',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'salary'        => 'decimal:2',
        'working_hours' => 'integer',
        'status'        => 'string',
        'contract_type' => 'string',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('end_date')
                    ->whereBetween('end_date', [now(), now()->addDays($days)]);
    }
}