<?php

require_once __DIR__ . '/vendor/autoload.php';

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

/**
 * Method which actually sends a message to Telegram
 * @param  array $message
 * @return void
 */
function send($message) {
	$message = $message['attachments'][0];
	$content = '<a href="' . htmlspecialchars($message['title_link'], ENT_QUOTES) . '">' . $message['title'] . '</a>: ' . $message['fallback'];

	$data = createMessagePayload($content);

	$result = Request::sendMessage($data);
}

/**
 * Checks wether the Connector works with the current settings
 * @return boolean
 */
function works() {
	$data = createMessagePayload('Flarum is able to contact this Telegram room. Great!');

	try {
		$result = Request::sendMessage($data);

		return $result->isOk();
	} catch (TelegramException $e) {
		return false;
	}
}

/**
 * Creates a payload to be sent using the API.
 * @param string $message
 * @return array
 */
function createMessagePayload($message) {
	global $config;
	return [
		'chat_id' => $config['chatId'],
		'text' => $message,
		'parse_mode' => 'HTML',
	];
}


$credentials = $_ENV['CREDENTIALS_DIRECTORY'] ?? null;
if ($credentials !== null) {
	$config = [
		'apiKey' => file_get_contents($credentials . '/apiKey'),
		'botName' => file_get_contents($credentials . '/botName'),
		'chatId' => file_get_contents($credentials . '/chatId'),
		'token' => file_get_contents($credentials . '/token'),
	];
} else {
	$config = require __DIR__ . '/config.php';
}

if (!isset($_GET['token']) || $_GET['token'] !== $config['token']) {
	http_response_code(403);
	echo 'Invalid webhook token' . PHP_EOL;
	die;
}

if (isset($_GET['action'])) {
	$telegram = new Telegram($config['apiKey'], $config['botName']);

	if ($_GET['action'] === 'test') {
		works();
		die;
	} elseif ($_GET['action'] === 'hook') {
		$json = file_get_contents('php://input');
		$message = json_decode($json, TRUE);
		send($message);
		die;
	}
}

http_response_code(400);
echo 'Unknown action' . PHP_EOL;
