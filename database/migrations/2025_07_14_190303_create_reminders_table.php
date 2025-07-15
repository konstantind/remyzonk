<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id(); // auto-increment bigint unsigned
            $table->bigInteger('chat_id');       // Telegram Chat ID (может быть отрицательным, но Laravel не поддерживает signed bigInteger, можно int or string, но bigint лучше)
            $table->bigInteger('creator_id');    // Telegram User ID создателя
            $table->bigInteger('target_user_id'); // Telegram User ID того, кому напомнить
            $table->text('text');                 // Текст напоминания
            $table->dateTime('remind_at');       // Время напоминания
            $table->string('status', 20);        // Статус (pending, sent, done)
            $table->timestamps();                 // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
