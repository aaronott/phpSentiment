#!/usr/bin/env php
<?php
require 'lib/Sentiment.php';

if (!isset($argv[1])) {
  die("Usage: $argv[0] <text file>\n");
}

if (!file_exists($argv[1])) {
  die("File not found: $argv[1]\n");
}

$story = file_get_contents($argv[1]);

$sentences = preg_split('/\.|\?/', $story);
array_pop($sentences);

$sentiment = new Sentiment();

$overall_score = array('pos' => 0, 'neg' => 0, 'neu' => 0);
foreach ($sentences as $sentence) {
  $polarity = $sentiment->score($sentence);

  arsort($polarity);
  echo key($polarity) . '::';
  echo 'pos: '. number_format($polarity['pos'], 2) . " | ";
  echo 'neg: '. number_format($polarity['neg'], 2) . " | ";
  echo 'neu: '. number_format($polarity['neu'], 2) . " | ";
  echo $sentence . "\n";

  $overall_score['pos'] += $polarity['pos'];
  $overall_score['neg'] += $polarity['neg'];
  $overall_score['neu'] += $polarity['neu'];
}

echo "\n==========================================\n";
echo 'pos: '. number_format($overall_score['pos'], 2) . " | ";
echo 'neg: '. number_format($overall_score['neg'], 2) . " | ";
echo 'neu: '. number_format($overall_score['neu'], 2) . " | ";

arsort($overall_score);
echo "Overall Category: " . key($overall_score) . "\n";
