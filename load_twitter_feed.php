<?php
require 'vendor/autoload.php';
require 'autoload.php';
include('vendor/fennb/phirehose/lib/Phirehose.php');
include('vendor/fennb/phirehose/lib/OauthPhirehose.php');

use app\queue\RabbitMQQueue;


/**
 * Example of using Phirehose to display a live filtered stream using track words
 */
class FilterTrackConsumer extends OauthPhirehose {

	/**
	 * Enqueue each status
	 *
	 * @param string $status
	 */
	public function enqueueStatus($status)
	{

		$rabbitHandler = new RabbitMQQueue();
		/*
		 * In this simple example, we will just display to STDOUT rather than enqueue.
		 * NOTE: You should NOT be processing tweets at this point in a real application, instead they should be being
		 *       enqueued and processed asyncronously from the collection process.
		 */
		$data = json_decode($status, true);
		if (is_array($data) && isset($data['user']['screen_name'])) {
			echo $data['lang'] . " : " .$data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n";

			$body = [
				"user" 		=> $data['user']['screen_name'],
				"language" 	=> $data['lang'],
				"message"	=> $data['text'],
				"hashtags"	=> $data['entities']['hashtags'],
			];


			$rabbitHandler->sendMessage(json_encode($body), $data['lang'] . '.tweet', 'tweets');
		}
	}
}

// The OAuth credentials you received when registering your app at Twitter
define("TWITTER_CONSUMER_KEY", "");
define("TWITTER_CONSUMER_SECRET", "");

// The OAuth data for the twitter account
define("OAUTH_TOKEN", "");
define("OAUTH_SECRET", "");

$items = array("Mcdonalds", "People", "Avengers", "Football");

$sc = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
$sc->setTrack($items);
$sc->consume();
