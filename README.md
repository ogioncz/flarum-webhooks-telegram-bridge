# flarum-webhooks-telegram-bridge

This is a bridge between Flarum [Webhooks](https://github.com/FriendsOfFlarum/webhooks) extension and Telegram.

## How to use

1. Follow the [instructions](https://github.com/php-telegram-bot/core/blob/master/README.md#create-your-first-bot) to create a Telegram bot.
2. Invite the bot to the room where you want it to post and [obtain the room id](https://stackoverflow.com/a/32572159/160386).
3. Clone this repository somewhere (e.g. into your Flarum installation’s `public/` directory).
4. Run `composer install` in the directory to install dependencies.
5. Create `config.php` file in the directory, containing:

    ```php
    <?php

    return [
        'apiKey' => 'your-api-key',
        'botName' => 'your-bot-name',
        'chatId' => 'your-chat-id',
        'token' => 'your-random-string',
    ];
    ```
6. Replace `your-bot-name` with the bot’s username, `your-api-key` with the “token to access the HTTP API” you received from BotFather, `your-chat-id` with the id of the room id and `your-random-string` with random string.
7. In the “FoF Webhooks” section of the Flarum admin panel add a webhook for “Slack” and set the URL to the location where you installed this project and pass `action=hook` and `token=your-random-string` in the query string (e.g. `https://your-flarum-forum.com/flarum-webhooks-telegram-bridge/?action=hook&token=your-random-string`)

## Related projects

- [Telegram support for (no longer supported) flarum-ext-notify](https://github.com/manelizzard/flarum-notify/pull/6)

## License

Please see the LICENSE included in this repository for a full copy of the MIT license, which this project is licensed under.
