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

  /**
   * @covers Sentiment::analyze
   */
  public function testAnalyze() {
    $method = new ReflectionMethod('Sentiment', 'analyze');

    $method->setAccessible(True);

    $scores = $method->invokeArgs(new Sentiment(), array('Today was rubbish'));

    $this->assertEquals('array', gettype($scores));
    $this->assertEquals(3, sizeof($scores));

    $this->assertEquals(0.666666666666, $scores['neg']);
  }

  /**
   * @covers Sentiment::_tokenize
   */
  public function testTokanize() {
    $method = new ReflectionMethod('Sentiment', '_tokenize');

    $method->setAccessible(True);

    $tokens = $method->invokeArgs(new Sentiment(), array('Today was rubbish'));

    $this->assertEquals('array', gettype($tokens));
    $this->assertEquals(3, sizeof($tokens));
    $this->assertEquals('today', $tokens[0]);
  }

  /**
   * @covers Sentiment::_cleanString
   */
  public function testCleanString() {
    $method = new ReflectionMethod('Sentiment', '_cleanString');

    $method->setAccessible(True);

    $word1 = $method->invokeArgs(new Sentiment(), array('clean'));
    $this->assertEquals('clean', $word1);
  }

}
?>
