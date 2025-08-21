<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'customer_name',
        'amount',
        'quantity',
        'category',
        'payment_method',
        'inventory',
        'notes',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'inventory' => 'array',
        'quantity' => 'integer',
    ];

    protected $attributes = [
        'status' => 'en-attente',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryLabelAttribute()
    {
        $categories = [
            'lavage-simple' => 'Lavage simple',
            'lavage-repassage' => 'Lavage + Repassage',
            'repassage-seul' => 'Repassage seulement',
            'nettoyage-sec' => 'Nettoyage à sec',
            'costume' => 'Costume complet',
            'robe-ceremonie' => 'Robe de cérémonie',
        ];

        return $categories[$this->category] ?? $this->category;
    }

    public function getPaymentMethodLabelAttribute()
    {
        $methods = [
            'cash' => 'Espèces',
            'wave' => 'Wave',
            'orange-money' => 'Orange Money',
            'free-money' => 'Free Money',
            'bank' => 'Virement bancaire',
            'check' => 'Chèque',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'en-attente' => 'En attente',
            'en-cours' => 'En cours',
            'termine' => 'Terminé',
            'recupere' => 'Récupéré',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForDateRange($query, $startDate, $endDate = null)
    {
        if ($endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->whereDate('created_at', $startDate);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
