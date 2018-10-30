<?php

namespace app\processors;

use Elasticsearch\Client;
use PhpAmqpLib\Message\AMQPMessage;

class ElasticProcessor extends AbstractProcessor {

	public static function processRabbit(AMQPMessage $message) {

		$hosts = ['hosts' => ['192.168.56.101:9200', 'localhost:9200']];
		$indexParams['index']  = 'twitter'; //index

		$client = new Client($hosts);
		try {
			$client->indices()->create($indexParams);
		} catch (\Exception $e) {
			//just to create the index if its not there
		}

		$body = json_decode($message->body, true);

		$params = [
			'index' => 'twitter',
			'type'  => 'tweets',
			'body'	=> $body
		];

		$client->index($params);

		$message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

		echo "\n[x] PROCESSED and sent to elastic \n";
	}
}
