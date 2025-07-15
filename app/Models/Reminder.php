<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table = 'reminders';

    protected $fillable = [
        'chat_id',
        'creator_id',
        'target_user_id',
        'text',
        'remind_at',
        'status',
    ];

    protected $casts = [
        'chat_id' => 'integer',
        'creator_id' => 'integer',
        'target_user_id' => 'integer',
        'remind_at' => 'datetime',
        'status' => 'string',
    ];

    public $timestamps = true;
}
