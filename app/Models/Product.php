<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'ruules_id',
        'product_id',
        'product_title',
    ];


    public function rule()
    {
        return $this->belongsTo(Ruule::class);
    }
}
