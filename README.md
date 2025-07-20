```shell
php artisan tinker

$telegram = app(\Telegram\Bot\Api::class);
$telegram->setWebhook(['url' => 'https://ee4c5739de96.ngrok-free.app/api/telegram/webhook']);
```

```shell
php artisan telegram:setWebhook
```

```shell
php artisan reminders:send
php artisan schedule:work
```

```shell
composer dump-autoload
```
