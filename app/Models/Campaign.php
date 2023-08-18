<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'order',
        'type',
        'status',
        'start_date'
    ];

    public function scopeFindActiveCampaigns($query)
    {
        return $query->where('status', 'active')
            ->where('type', '!=', 'rvm')
            ->orderBy('order', 'desc')
            ->limit(10);
    }
}

