<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_id',
        'product_id',
        'collection_id'
    ];

    public function rule()
    {
        return $this->belongsTo(Rule::class);
    }
}
