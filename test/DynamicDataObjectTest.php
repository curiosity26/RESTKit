<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/8/15
 * Time: 8:30 AM
 */

class DynamicDataObjectTest extends PHPUnit_Framework_TestCase {

  public function testIntegerProperty() {
    $data = new \RESTKit\DynamicDataObject();
    $data->test = 1;
    $this->assertEquals(1, $data->test);

    $data->test = "2";
    $this->assertEquals(2, $data->test);
  }

  public function testBooleanProperty() {
    $data = new \RESTKit\DynamicDataObject();
    $data->test = true;
    $this->assertTrue($data->test);
    $data->test = "true";
    $this->assertTrue($data->test);
    $data->test = 1;
    $this->assertTrue($data->test);
    $data->test = "1";
    $this->assertTrue($data->test);
    $data->test = false;
    $this->assertFalse($data->test);
    $data->test = "false";
    $this->assertTrue($data->test);
    $data->test = 0;
    $this->assertFalse($data->test);
    $data->test = "0";
    $this->assertFalse($data->test);
  }

  public function testStringProperty() {
    $data = new \RESTKit\DynamicDataObject();
    $data->test = "My Name is Bob";
    $this->assertEquals("My Name is Bob", $data->test);
    $data->test = "2015-06-20T10:20:12Z";
    // If the property is already created as a string, it will always be a string
    $this->assertEquals("2015-06-20T10:20:12Z", $data->test);
    // If the property is not created, the creator will test for dates
    $data->test2 = "2015-06-20T10:20:12Z";
    $this->assertInstanceOf('\\DateTime', $data->test2);
    // Check for Fallback
    $data->test3 = "I was born on 2015-06-20T10:20:12Z";
    $this->assertEquals("I was born on 2015-06-20T10:20:12Z", $data->test3);
    $data->test4 = "2015-06-20T10:20:12Z was a great time";
    $this->assertEquals("2015-06-20T10:20:12Z was a great time", $data->test4);
  }

  public function testDoubleProperty() {
    $data = new \RESTKit\DynamicDataObject();
    $data->test = 20.1;
    $this->assertEquals(20.1, $data->test);
    $data->test = 10;
    $this->assertEquals(10.0, $data->test);
  }

  public function testDateTimeProperty() {
    $data = new \RESTKit\DynamicDataObject();
    $data->test = new DateTime("2015-06-20T10:20:12Z");
    $this->assertInstanceOf("DateTime", $data->test);

    $json = json_encode(array("test" => "2015-06-20T10:20:12Z"));
    $this->assertJson($json, json_encode($data));
  }

  public function testClassProperty() {
    $data = new \RESTKit\DynamicDataObject();
    $data->test = array("item 1", "item 2");
    $this->assertInternalType('array', $data->test);
    $this->assertContains("item 1", $data->test);
    $item = new stdClass();
    $item->value1 = "Test";
    $data->test2 = $item;
    $this->assertInstanceOf('stdClass', $data->test2);
    $item2 = new ArrayIterator(array("item3", "item4"));
    $data->test3 = $item2;
    $this->assertInstanceOf("ArrayIterator", $data->test3);
    $this->assertContains("item4", $data->test3);
  }
}
