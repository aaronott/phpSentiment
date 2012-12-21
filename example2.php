#!/usr/bin/env php
<?php

require 'lib/Sentiment.php';

$story = file_get_contents('specialDelivery.txt');

$sentences = preg_split('/\.|\?/', $story);
array_pop($sentences);

$sentiment = new Sentiment();

foreach ($sentences as $sentence) {
  $category = $sentiment->categorize($sentence);

  echo $category . "| " . $sentence . "\n";
}

