<?php
/**
 * Implements the Dictionary class.
 */

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
      $word = trim(stripcslashes($word));

      $this->list[$class][$word] = 1;
    }
  }

  /**
   * Check if a word is in a given class list in the dictionary.
   *
   * @param string
   *   Class name for the list to be checked against.
   *
   * @param string
   *   Token to be checked.
   *
   * @return bool
   */
  public function inList($class, $token) {
    return isset($this->list[$class][$token]);
  }

  public function getWeight($class, $token) {
    return $this->inList($class, $token) ? $this->list[$class][$token] : 0;
  }

  public function getList($class = NULL) {

    if (!is_null($class) && isset($this->list[$class])) {
      return $this->list[$class];
    }

    return $this->list;
  }

}

class Dictionary_Exception extends Exception {}
