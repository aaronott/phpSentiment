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

foreach ($sentences as $sentence) {
  $category = $sentiment->categorize($sentence);

  echo $category . "| " . $sentence . "\n";
}

