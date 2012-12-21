#!/usr/bin/env php
<?php
  include 'lib/Sentiment.php';

  $sentiment = new Sentiment();

  $example1 = $sentiment->categorize('Today was rubbish'); //Neg
  $example2 = $sentiment->categorize('Today was amazing'); // Pos
  $example3 = $sentiment->categorize('Today was ok'); // Neu


?>
  Example 1
  Today was rubbish
  Classification - <?php echo $example1; ?>


  Example 2
  Today was amazing
  Classification - <?php echo $example2; ?>


  Example 3
  Today was ok
  Classification - <?php echo $example3; ?>

