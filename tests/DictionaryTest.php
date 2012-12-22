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

    /**
     * @expectedException Dictionary_Exception
     */
    public function testFailedInvokeDictionary()
    {
      $this->assertInstanceOf('Dictionary_Exception', new Dictionary('nonexistent'));
    }

    /**
     * @covers Dictionary::getList
     */
    public function testGetlist() {

      $dictionary = new Dictionary('ign');

      $list = $dictionary->getList('ign');
      $this->assertEquals('array', gettype($list));
      $this->assertGreaterThan(5, sizeOf($list));

      $this->assertEquals('ccedil', key($list));

      $full_list = $dictionary->getList();
      $this->assertEquals('array', gettype($full_list));
      $this->assertEquals(1, sizeOf($full_list));

      $this->assertEquals('ccedil', key($full_list['ign']));
    }

    /**
     * @covers Dictionary::addDictionary
     */
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

    /**
     * @covers Dictionary::inList
     */
    public function testInList() {
      $dictionary = new Dictionary('ign');

      $this->assertTrue($dictionary->inList('ign','ccedil'));
      $this->assertFalse($dictionary->inList('ign','monkeys'));
    }

    /**
     * @covers Dictionary::getWeight
     */
    public function testGetWeight() {
      $dictionary = new Dictionary('ign');

      $this->assertEquals(1, $dictionary->getWeight('ign','ccedil'));
      $this->assertEquals(0, $dictionary->getWeight('ign','monkeys'));
    }
}
