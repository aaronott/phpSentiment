<?php

require_once "lib/Sentiment.php";

class SentimentTest extends PHPUnit_Framework_TestCase
{

  /**
   * @covers Sentiment::categorize
   */
  public function testCategorize()
  {
    $sentiment = new Sentiment();
    $this->assertEquals('neg', $sentiment->categorize('Today was rubbish'));
    $this->assertEquals('pos', $sentiment->categorize('Today was amazing'));
    $this->assertEquals('neu', $sentiment->categorize('Today was ok'));
  }

  /**
   * @covers Sentiment::score
   */
  public function testScore() {
    $sentiment = new Sentiment();
    $scores = $sentiment->score('Today was rubbish');

    $this->assertEquals('array', gettype($scores));
    $this->assertEquals(3, sizeof($scores));

    $this->assertEquals(0.666666666666, $scores['neg']);
  }
}
?>
