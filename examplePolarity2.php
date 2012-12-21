#!/usr/bin/env php
<?php

require 'lib/Sentiment.php';

$story = file_get_contents('specialDelivery.txt');

$sentences = preg_split('/\.|\?/', $story);
array_pop($sentences);

$sentiment = new Sentiment();

foreach ($sentences as $sentence) {
  $polarity = $sentiment->score($sentence);


  arsort($polarity);
  echo key($polarity) . '::';
  echo 'pos: '. number_format($polarity['pos'], 2) . " | ";
  echo 'neg: '. number_format($polarity['neg'], 2) . " | ";
  echo 'neu: '. number_format($polarity['neu'], 2) . " | ";
  echo $sentence . "\n";
}

