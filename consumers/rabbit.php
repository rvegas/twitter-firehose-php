<?php
require '../vendor/autoload.php';
require '../autoload.php';

use app\queue\RabbitMQQueue;

$rabbit = new RabbitMQQueue();
$rabbit->processQueue('english-tweets', \app\processors\AbstractProcessor::ELK);
