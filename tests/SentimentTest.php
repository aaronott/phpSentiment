<?php

require_once "sentiment.class.php";

class SentimentTest extends PHPUnit_Framework_TestCase
{
    public function testSentiment()
    {
      $sentiment = new Sentiment();
        $this->assertEquals('neg', $sentiment->categorise('Today was rubbish'));
        $this->assertEquals('pos', $sentiment->categorise('Today was amazing'));
        $this->assertEquals('neu', $sentiment->categorise('Today was ok'));
    }

    public function testGetTokens() {
      $sentiment = new Sentiment();

      $tokens = $sentiment->_getTokens("Tokenize this string yo!");
      $this->assertEquals('array', gettype($tokens));
      $this->assertEquals('tokenize', $tokens[0]);
      $this->assertEquals('yo!', end($tokens));
      $this->assertEquals(4, sizeof($tokens));

    }
}
?>
