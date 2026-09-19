<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'phone_number',
        'sender_name',
        'raw_content',
        'passed_filter',
        'ai_analysis',
        'status',
    ];

    protected $casts = [
        'ai_analysis' => 'array',
        'passed_filter' => 'boolean',
    ];

    public function task()
    {
        return $this->hasOne(Task::class);
    }
}