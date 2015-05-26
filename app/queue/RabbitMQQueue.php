<?php
namespace app\queue;

include __DIR__ . '/../../vendor/autoload.php';

use app\processors\AbstractProcessor;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Exception\AMQPRuntimeException;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQQueue {

	/**
	 * @var $channel AMQPChannel
	 */
	var $channel;

	/**
	 * @return AMQPChannel
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * @var $connection AMQPConnection
	 */
	var $connection;

	public function __construct() {
		try {
            $this->connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
			$this->channel = $this->connection->channel();
		} catch (Exception $e) {
			echo "warning: " . $e->getMessage();
			return false;
		}
		return false;
	}

	public function sendMessage($message, $routingKey, $exchange = 'critics') {
		try {
			if ($this->channel && $this->connection) {
				$this->channel = $this->connection->channel();
				$msg = new AMQPMessage($message);
				$this->channel->basic_publish($msg, $exchange, $routingKey);

				$this->channel->close();
				$this->connection->close();
			}
		} catch (Exception $e) {
			echo "warning: " . $e->getMessage();
			return false;
		}
		return false;
	}

	public function processQueue($queue_name, $processorName = AbstractProcessor::DEF) {
		$this->channel->basic_consume($queue_name, '', false, false, false, false, array($processorName, 'processRabbit'));

		while(count($this->channel->callbacks)) {
			$this->channel->wait();
		}

		$this->channel->close();
		$this->connection->close();
	}

}
