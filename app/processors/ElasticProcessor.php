<?php

namespace app\processors;

use Elasticsearch\Client;
use PhpAmqpLib\Message\AMQPMessage;

class ElasticProcessor extends AbstractProcessor {

	public static function processRabbit(AMQPMessage $message) {

		$hosts = ['localhost:9200'];
		$client = new Client($hosts);

		$body = json_decode($message->body, true);

		//var_dump($body);die;
		$params = [
			'index' => 'twitter',
			'type'  => 'tweets',
			'body'	=> $body
		];

		$client->index($params);

		$message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

		echo " [x] PROCESSED and sent to elastic \n";
	}
}