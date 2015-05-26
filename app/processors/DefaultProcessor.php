<?php

namespace app\processors;

use Guzzle\Service\Resource\Model;
use PhpAmqpLib\Message\AMQPMessage;

class DefaultProcessor extends AbstractProcessor {

	public static function processRabbit(AMQPMessage $message) {
		echo ' [x] PROCESSED ', $message->body, "\n";
	}
}