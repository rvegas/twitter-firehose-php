<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../autoload.php';

use app\queue\RabbitMQQueue;

$rabbit = new RabbitMQQueue();
$rabbit->processQueue('english-tweets', \app\processors\AbstractProcessor::ELK);
