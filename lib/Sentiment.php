<?php
/*
 phpInsight is a Naive Bayes classifier to calculate sentiment. The program
 uses a database of words categorised as positive, negative or neutral

 Copyright (C) 2012  James Hennessey

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>

 */

require_once "lib/Dictionary.php";


class Sentiment{
  private $dictionary;
  private $ignoreDictionary;
  private $previxDictionary;
  private $minTokenLength = 1; //min token length for it to be taken into consideration
  private $maxTokenLength = 15; //max token length for it to be taken into consideration
  private $prior = array('pos' => 0.333333333333, 'neg' => 0.333333333333, 'neu' => 0.333333333333); //The original probability of a tweet being categorised as one of the three

  /**
   * Constructor
   *
   * Build the dictionary to check against.
   */
  public function __construct() {
    $dictionary = new Dictionary();
    $dictionary->addDictionary('neg');
    $dictionary->addDictionary('pos');
    $dictionary->addDictionary('neu');

    $this->dictionary = $dictionary->getList();
    $this->ignoreDictionary = new Dictionary('ign');
    $this->prefixDictionary = new Dictionary('prefix');
  }

  /**
   * Categorize a sentence.
   *
   * Process a sentence through the dictionaries and compute a score. This
   * will return the actual category (neg, pos, neu) after computing.
   *
   * @param string
   *   Sentence or grouping of words.
   *
   * @return string
   *   Returns one of the following based on the highest ranking after
   *   computing the score.
   *    - neg -- Negative
   *    - pos -- Positive
   *    - neu -- Neutral
   */
  public function categorise($sentence) {

    // Using the prefixDictionary, check to see if there are any prefixes that
    // have been separated from their word by a space. If so, remove the space
    // so the word may get the proper scoring.
    foreach($this->prefixDictionary->getList('prefix') as $negPrefix){
      if (strpos($sentence, $negPrefix)) {
        $sentence = str_replace ($negPrefix . ' ', $negPrefix, $sentence);
      }
    }

    $tokens = $this->_tokenize($sentence);

    // Initialize these for the upcomming loop.
    $total_words = 0;
    $total_score = 0;
    $ncat = 0;
    $scores = array();

    foreach($this->dictionary as $class => $list) {

      $scores[$class] = 1;

      foreach ($tokens as $token) {

        // Make sure the toekn is not in the ignore list and that it's within
        // the given length boundaries.
        if (strlen($token) > $this->minTokenLength
          && strlen($token) < $this->maxTokenLength
          && !in_array($token, $this->ignoreDictionary->getList('ign'))) {

          if(isset($list[$token])){
            $count = $list[$token];
          }else{
            $count = 0;
          }

          $scores[$class] *= ($count + 1);
        }
      }

      //Score for this class is the prior probability multiplyied by the score for this class
      $scores[$class] = $this->prior[$class] * $scores[$class];
    }

    //Makes the scores relative percents
    $classes = array_keys($this->dictionary);
    foreach($classes as $class) {
      $total_score += $scores[$class];
    }

    foreach($classes as $class) {
      $scores[$class] = $scores[$class] / $total_score;
    }

    //Sort array in reverse order
    arsort($scores);

    //Classification is the key to the scores array
    $classification = key($scores);

    //Return the Classification
    return $classification;
  }

  /**
   * Tokenize a string
   *
   * Split the string into single words cleaning them on the way.
   *
   * @param string
   *   String of words.
   *
   * @return array
   *   Array of single strings that have been passed through the cleanString
   *   method.
   *
   * @see _cleanString
   */
  public function _tokenize($string) {
    $string = $this->_cleanString($string);
    $string = strtolower($string);
    $matches = explode(" ", $string);

    return $matches;
  }

  /**
   * Clean the string of accented characters.
   *
   * @param string
   *   String that needs some cleaning
   *
   * @return string
   *   String that has been cleaned. This has also been transformed to
   *   lowercase
   */
  private function _cleanString($string) {
    $diac =
    /* A */   chr(192).chr(193).chr(194).chr(195).chr(196).chr(197).
    /* a */   chr(224).chr(225).chr(226).chr(227).chr(228).chr(229).
    /* O */   chr(210).chr(211).chr(212).chr(213).chr(214).chr(216).
    /* o */   chr(242).chr(243).chr(244).chr(245).chr(246).chr(248).
    /* E */   chr(200).chr(201).chr(202).chr(203).
    /* e */   chr(232).chr(233).chr(234).chr(235).
    /* Cc */  chr(199).chr(231).
    /* I */   chr(204).chr(205).chr(206).chr(207).
    /* i */   chr(236).chr(237).chr(238).chr(239).
    /* U */   chr(217).chr(218).chr(219).chr(220).
    /* u */   chr(249).chr(250).chr(251).chr(252).
    /* yNn */ chr(255).chr(209).chr(241);
    return strtolower(strtr($string, $diac, 'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn'));
  }
}

class Sentiment_Exception extends Exception {}
