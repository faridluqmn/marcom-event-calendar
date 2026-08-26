<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'start_date',
        'end_date',
        'user_id',
        'marcom_id',
        'branch_id',
        'brand_id',
        'estimation',
        'result',
        'is_regional'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_regional' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marcom()
    {
        return $this->belongsTo(Marcom::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
