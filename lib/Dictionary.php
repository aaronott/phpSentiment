<?php


class Dictionary {

  private $list;

  public function __construct($class = NULL) {

    if (!is_null($class)) {
      $this->addDictionary($class);
    }
  }

  public function addDictionary($class) {

    // @TODO FIX THIS SO WE DON'T HAVE THE FILE PATH HERE.
    $fn = dirname(dirname(__FILE__)) . '/data/data.' . $class . '.php';
    if (file_exists($fn)) {
      $temp = file_get_contents($fn);
      $words = unserialize($temp);
    } else {
      throw new Dictionary_Exception("File does not exist: $fn.");
    }

    foreach ($words as $word) {
      $word = stripcslashes($word);
      $word = trim($word);

      //If this word isn't already in the dictionary with this class
      if(!isset($this->list[$class][$word])) {

        // Add to this word to the dictionary and set counter value as one. This
        // function ensures that if a word is in the text file more than once it
        // still is only accounted for one in the array
        $this->list[$class][$word] = 1;
      }
    }

    return true;
  }

  public function getClass() {
    return $this->class;
  }

  public function inList($list, $token) {

  }

  public function getList($class = NULL) {

    if (!is_null($class) && isset($this->list[$class])) {
      return $this->list[$class];
    }

    return $this->list;
  }

  /**
   * Magic __get function
   */
  public function __get($name) {
    if (is_function("get_" . $name)) {
      return 'get_' . $name;
    }
  }
}

class Dictionary_Exception extends Exception {}
