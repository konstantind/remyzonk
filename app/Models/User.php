<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'telegram_id';
    public $incrementing = false;

    protected $fillable = [
        'telegram_id',
        'username',
        'private_chat_id',
    ];
}
