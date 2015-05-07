<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 10:38 AM
 */

class JSONDataObjectTest extends PHPUnit_Framework_TestCase {
  public function testIntegerProperty() {
    $data = new \RESTKit\JSONDataObject();
    $data->createProperty('test', new \RESTKit\Properties\IntegerProperty(12));
    $data->createProperty('test2', new \RESTKit\Properties\IntegerProperty("12"));

    $this->assertEquals(12, $data->test);
    $this->assertEquals(12, $data->test2);
    $this->assertTrue(is_int($data->test2));

    $data->test = 313;
    $this->assertEquals(313, $data->test);
  }

  public function testBooleanProperty() {
    $data = new \RESTKit\JSONDataObject();
    $data->createProperty('test', new \RESTKit\Properties\BooleanProperty(true)); // True
    $data->createProperty('test2', new \RESTKit\Properties\BooleanProperty("true")); // True
    $data->createProperty('test3', new \RESTKit\Properties\BooleanProperty(1)); // True
    $data->createProperty('test4', new \RESTKit\Properties\BooleanProperty("1")); // True
    $data->createProperty('test5', new \RESTKit\Properties\BooleanProperty(false)); // False
    $data->createProperty('test6', new \RESTKit\Properties\BooleanProperty("false")); // True
    $data->createProperty('test7', new \RESTKit\Properties\BooleanProperty(0)); // False
    $data->createProperty('test8', new \RESTKit\Properties\BooleanProperty("0")); // False

    $this->assertTrue($data->test);
    $this->assertTrue($data->test2);
    $this->assertTrue($data->test3);
    $this->assertTrue($data->test4);
    $this->assertFalse($data->test5);
    $this->assertTrue($data->test6);
    $this->assertFalse($data->test7);
    $this->assertFalse($data->test8);

    $data->test = false;
    $this->assertFalse($data->test);
  }

  public function testStringProperty() {
    $data = new \RESTKit\JSONDataObject();
    $data->createProperty('test',
      new \RESTKit\Properties\StringProperty('Test', 10));

    $this->assertEquals('Test', $data->test);

    $data->test = 'ThisIsALongerString';

    $this->assertEquals('ThisIsALon', $data->test);
  }

  public function testDoubleProperty() {
    $data = new \RESTKit\JSONDataObject();
    $data->createProperty('test', new \RESTKit\Properties\DoubleProperty(12.02));

    $this->assertEquals(12.02, $data->test);

    $data->test = "13.19";

    $this->assertEquals(13.19, $data->test);

    $data->test = 11;

    $this->assertEquals(11.0, $data->test);
  }

  public function testDateTimeProperty() {
    $data = new \RESTKit\JSONDataObject();
    $data->createProperty('test',
      new \RESTKit\Properties\DateTimeProperty("2015-05-06 00:12:36 EST"));
    $this->assertEquals('May', $data->test->format('F'));
    $this->assertEquals('2015', $data->test->format('Y'));
    $this->assertEquals('Wednesday', $data->test->format('l'));
    $this->assertEquals('12', $data->test->format('i'));
    $this->assertEquals('36', $data->test->format('s'));
    $this->assertEquals('am', $data->test->format('a'));
  }

  public function testCustomProperty() {
    $data = new \RESTKit\JSONDataObject();
    $data->createProperty('test',
      new \RESTKit\Properties\ClassProperty(
        'RESTKit\\Request\\HTTPRequest',
        null,
        array(
          'https://www.google.com/',
          'POST'
        ),
        true
      )
    );

    $this->assertEquals('https://www.google.com/', $data->test->getUrl());
    $this->assertEquals('POST', $data->test->getMethod());
  }

  public function testJsonEncode() {
    $data = new \RESTKit\JSONDataObject();

    $data
      ->createProperty('test', new \RESTKit\Properties\IntegerProperty(2))
      ->createProperty('a_string', new \RESTKit\Properties\StringProperty('A Really Long String', 15))
      ->createProperty('cool', new \RESTKit\Properties\BooleanProperty(true))
      ->createProperty('eventDate', new \RESTKit\Properties\DateTimeProperty('2015-06-20 08:00:21 EST'));

    $json = json_encode($data);

    $this->assertSame(
      '{"test":2,"a_string":"A Really Long S","cool":true,"eventDate":"2015-06-20T08:00:21-05:00"}',
      $json
    );
  }
}
