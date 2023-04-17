<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'enable_app',
        'customize_change_btn',
        // 'text_size'=>12,
        // 'text_color',
        // 'text_background_color',
        'change_btn_text',
        'update_btn_text',
        'cancel_btn_text',
        'enable_merchant_msg',
        'enable_customer_msg',
        'enable_survey_question',
        'merchant_msg',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
