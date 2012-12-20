<?php

require_once "dictionary.php";

class DictionaryTest extends PHPUnit_Framework_TestCase
{
    public function testInvokeDictionary()
    {
      $dictionary = new Dictionary('neg');

      $list = $dictionary->getList();
      $this->assertEquals(1, sizeof($list));

      $classes = array_keys($list);
      $class = array_shift($classes);

      $this->assertEquals('neg', $class);
    }

    public function testAddDictionary() {
      $dictionary = new Dictionary();

      $dictionary->addDictionary('neg');

      $list = $dictionary->getList();
      $this->assertEquals(1, sizeof($list));

      $classes = array_keys($list);
      $class = array_shift($classes);
      $this->assertEquals('neg', $class);

      $dictionary->addDictionary('pos');

      $list = $dictionary->getList();
      $this->assertEquals(2, sizeof($list));

      $class_keys = array_keys($list);
      $class = array_shift($class_keys);
      $this->assertEquals('neg', $class);

      $class = array_shift($class_keys);
      $this->assertEquals('pos', $class);
    }

    public function testGetlist() {

      $dictionary = new Dictionary('ign');

      $list = $dictionary->getList('ign');
      $this->assertEquals('array', gettype($list));
      $this->assertGreaterThan(5, sizeOf($list));

      $words = array_keys($list);
      $this->assertEquals('ccedil', $words[0]);
    }
}
?>
