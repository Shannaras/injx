<?php

namespace Injx\Test;

use PHPUnit\Framework\TestCase;

use Injx\Injx;

class InjxTest extends TestCase {
    public function setUp() {
        $this -> tested = new class { use Injx; };
        $this -> tested_descendant = (new class { use Injx; }) -> injx( $this->tested );
        $this -> tested_descendant -> injx( $this->tested );
        $this -> mockContainer = new class {
            function getService($key) {
                return $key == 'foo' ? 'bar' : NULL;               
            }
        };
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInjxBadContainer() {
        $this -> tested -> injx(new class {});
    }
    public function testInjxReturnValue() {
        $actual = $this -> tested -> injx( $this -> mockContainer );
        $this -> assertEquals($this -> tested, $actual);
    }
    /**
     * @expectedException \BadMethodCallException
     */
    public function testInjxGetServiceWithoutPreviousInjxCall() {
        $this -> tested -> getService('foo');
    }
    public function testInjxGetService() {
        $this -> tested -> injx( $this -> mockContainer );
        $this -> assertEquals('bar', $this -> tested -> getService('foo'));
    }
    public function testInjxGetServiceWithDescendants() {
        $this -> tested -> injx( $this -> mockContainer );
        $actual = $this -> tested_descendant -> getService('foo');
        $this -> assertEquals('bar', $actual);
    }
    public function testInjxSetServiceWithoutPreviousInjxCall() {
        $this -> tested -> setService('foo', 'foobar');
        $this -> assertEquals('foobar', $this -> tested -> getService('foo'));
    }
    public function testInjxSetService() {
        $this -> tested -> injx( $this -> mockContainer );
        $this -> tested -> setService('bar', 'foo');
        $this -> assertEquals('foo', $this -> tested -> getService('bar'));
    }
    public function testInjxSetServiceForOverride() {
        $this -> tested -> injx( $this -> mockContainer );
        $this -> tested -> setService('foo', 'foobar');
        $this -> assertEquals('foobar', $this -> tested -> getService('foo'));
    }
    public function testInjxSetServiceWithDescendants() {
        $this -> tested -> injx( $this -> mockContainer );
        $this -> tested -> setService('foo', 'foobar');
        $this -> assertEquals('foobar', $this -> tested_descendant -> getService('foo'));
    }
    private $assert_called;
    private $tested;
    private $tested_descendant;
    private $mockContainer;
}
