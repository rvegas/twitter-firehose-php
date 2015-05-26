# twitter-firehose-php
A small project to retrieve tweets from live updates and feed it into RabbitMQ.
Please refer to http://ricardo.vegas for setting up the auxiliary tools

## Usage
Just run 
  composer update
After that's done simply run
  php load_twitter_feed.php
When you're satisfied with the amount of tweets or you get blocked by twitter run the consumer
  php consumers/rabbit.php
