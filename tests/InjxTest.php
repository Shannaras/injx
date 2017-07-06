<?php

namespace Injx\Test;

use PHPUnit\Framework\TestCase;

use Injx\Injx;

class InjxTest extends TestCase {
    public function setUp() {
        $this -> tested = new class { use Injx; };
        $this -> mockContainer = new class {
            function getService($key) {
                return $key == 'foo' ? 'bar' : NULL;               
            }
        };
    }
    private function initDescendant() {
        $this -> tested_descendant = $this->tested->injxTo(
            new class { use Injx; }
        );
        $this -> tested -> injxFrom( $this -> mockContainer );
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInjxFromBadContainer() {
        $this -> tested -> injxFrom(new class {});
    }
    public function testInjxToNonContainer() {
        $anObj = new class {};
        $this->assertEquals($anObj, $this -> tested -> injxTo($anObj));
    }
    public function testInjxFromReturnValue() {
        $actual = $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> assertEquals($this -> tested, $actual);
    }
    public function testInjxOkBeforeInjection() {
        $this->assertFalse( $this->tested->injxOk() );
    }
    public function testInjxOkAfterInjection() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this->assertTrue( $this->tested->injxOk() );
    }
    /**
     * @expectedException \BadMethodCallException
     */
    public function testInjxGetServiceWithoutPreviousInjxCall() {
        $this -> tested -> getService('foo');
    }
    public function testGetService() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> assertEquals('bar', $this -> tested -> getService('foo'));
    }
    public function testGetServiceWithDescendants() {
        $this ->initDescendant();
        $actual = $this -> tested_descendant -> getService('foo');
        $this -> assertEquals('bar', $actual);
    }
    public function testSetServiceWithoutPreviousInjxCall() {
        $this -> tested -> setService('foo', 'foobar');
        $this -> assertEquals('foobar', $this -> tested -> getService('foo'));
    }
    public function testSetService() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> tested -> setService('bar', 'foo');
        $this -> assertEquals('foo', $this -> tested -> getService('bar'));
    }
    public function testSetServiceReturnValue() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this->assertEquals(
            $this -> tested,
            $this -> tested -> setService('bar', 'foo')
        );
    }
    public function testSetServiceForOverride() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> tested -> setService('foo', 'foobar');
        $this -> assertEquals('foobar', $this -> tested -> getService('foo'));
    }
    public function testSetServiceWithDescendants() {
        $this ->initDescendant();
        $this -> tested -> setService('foo', 'foobar');
        $this -> assertEquals('foobar', $this -> tested_descendant -> getService('foo'));
    }
    private $tested;
    private $tested_descendant;
    private $mockContainer;
}
