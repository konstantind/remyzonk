```shell
php artisan tinker

$telegram = app(\Telegram\Bot\Api::class);
$telegram->setWebhook(['url' => 'https://mysite.loc/api/telegram/webhook']);
```
