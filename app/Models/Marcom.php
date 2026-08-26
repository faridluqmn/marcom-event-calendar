<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marcom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand_id', 'branch_id'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
