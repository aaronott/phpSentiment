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

require_once "dictionary.php";


class Sentiment{
  private $minTokenLength = 1; //min token length for it to be taken into consideration
  private $maxTokenLength = 15; //max token length for it to be taken into consideration
  private $prior = array('pos' => 0.333333333333, 'neg' => 0.333333333333, 'neu' => 0.333333333333); //The original probability of a tweet being categorised as one of the three

  //Function to categorise a tweet/sentence
  public function categorise($sentence) {
    //Access these text files to get the dictionary for each category
    $dictionary = new Dictionary();
    $dictionary->addDictionary('neg');
    $dictionary->addDictionary('pos');
    $dictionary->addDictionary('neu');

    $ignoreDictionary = new Dictionary('ign');
    $prefixDictionary = new Dictionary('prefix');

    $this->dictionary = $dictionary->getList();

    //For each negative prefix in the list
    foreach($prefixDictionary->getList('prefix') as $negPrefix){
      //Search if that prefix is in the document
      if(strpos($sentence, $negPrefix)){
        //Reove the white space after the negative prefix
        $sentence = str_replace ($negPrefix . ' ', $negPrefix, $sentence);
      }//Close if statement
    }//Close categories function

    //Tokenise Document
    $tokens = $this->_getTokens($sentence);
    // calculate the score in each category
    $total_words = 0;
    $total_score = 0;
    // $ncat var set to zero
    $ncat = 0;
    //Empty array for the scores for each of the possible categories
    $scores = array();

    //Loop through all of the different classes set in the $classes variable
    foreach($this->dictionary as $class => $list) {

      //In the scores array add another dimention for the class and set it's value to 1. EG $scores->neg->1
      $scores[$class] = 1;

      //For each of the individual words used loop through to see if they match anything in the $dictionary
      foreach($tokens as $token){

        //If statement so to ignore tokens which are either too long or too short or in the ignore list
        if(strlen($token) > $this->minTokenLength && strlen($token) < $this->maxTokenLength && !in_array($token, $ignoreDictionary->getList('ign'))){//
        //If dictionary[token][class] is set
          if(isset($list[$token])){
            //Set count equal to it
            $count = $list[$token];
          }else{
            $count = 0;
          }

          $scores[$class] *= ($count + 1);
        }//Close if statement

      }//Close loop for tokens

      //Score for this class is the prior probability multiplyied by the score for this class
      $scores[$class] = $this->prior[$class] * $scores[$class];

    }//Close loop for classes

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

  }//Close categorise Function

  public function _getTokens($string) // Function which breaks sting down into tokens/single words
  {
    //Clean the string so is free from accents
    $string = $this->_cleanString($string);
    //Make all texts lowercase as the database of words in in lowercase
    $string = strtolower($string);
    //Break string into individual words using explode putting them into an array
    $matches = explode(" ", $string);
    //Return array with each individual token
    return $matches;
  }//Close _getTokens Function

  public function getLiist($type) //Function to turn words from database in array
  {
    //Set up empty word list array
    $wordList = array();

    $fn = dirname(dirname(__FILE__)) . '/phpInsight/data/data.' . $type . '.php';
    if (file_exists($fn)) {
      $temp = file_get_contents($fn);
      $words = unserialize($temp);
    } else {
      throw new Sentiment_Exception("File does not exist: $fn.");
    }

    //Loop through results
    foreach ($words as $word) {
      //remove any slashes
      $word = stripcslashes($word);
      //Trim word
      $trimmed = trim($word);

      //Push results into $wordList array
      array_push($wordList, $trimmed);

    }
    //Return $wordList
    return $wordList;
  }//Close getIgnoreList Function

  //Function to clean a string so all characters with accents are turned into ASCII characters. EG: ‡ = a
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
  }//Close _cleanString Function
}

class Sentiment_Exception extends Exception {}
