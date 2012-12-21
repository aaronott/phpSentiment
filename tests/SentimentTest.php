<?php

require_once "lib/Sentiment.php";

class SentimentTest extends PHPUnit_Framework_TestCase
{
    public function testSentiment()
    {
      $sentiment = new Sentiment();
        $this->assertEquals('neg', $sentiment->categorize('Today was rubbish'));
        $this->assertEquals('pos', $sentiment->categorize('Today was amazing'));
        $this->assertEquals('neu', $sentiment->categorize('Today was ok'));
    }

    public function testTokenize() {
      $sentiment = new Sentiment();

      $tokens = $sentiment->_tokenize("Tokenize this string yo!");
      $this->assertEquals('array', gettype($tokens));
      $this->assertEquals('tokenize', $tokens[0]);
      $this->assertEquals('yo!', end($tokens));
      $this->assertEquals(4, sizeof($tokens));

    }

}
?>
