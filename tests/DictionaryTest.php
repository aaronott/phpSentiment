<?php

require_once "lib/Dictionary.php";

class DictionaryTest extends PHPUnit_Framework_TestCase
{
    public function testInvokeDictionary()
    {
      $dictionary = new Dictionary('neg');

      $list = $dictionary->getList();
      $this->assertEquals(1, sizeof($list));

      $this->assertEquals('neg', key($list));
    }

    public function testAddDictionary() {
      $dictionary = new Dictionary();

      $dictionary->addDictionary('neg');

      $list = $dictionary->getList();
      $this->assertEquals(1, sizeof($list));

      $this->assertEquals('neg', key($list));

      $dictionary->addDictionary('pos');

      $list = $dictionary->getList();
      $this->assertEquals(2, sizeof($list));

      $this->assertEquals('neg', key($list));

      // advance the pointer so we can make sure the dictionary was added.
      next($list);

      $this->assertEquals('pos', key($list));
    }

    public function testGetlist() {

      $dictionary = new Dictionary('ign');

      $list = $dictionary->getList('ign');
      $this->assertEquals('array', gettype($list));
      $this->assertGreaterThan(5, sizeOf($list));

      $this->assertEquals('ccedil', key($list));
    }
}
?>
