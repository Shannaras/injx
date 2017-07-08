<?php

namespace Injx\Test;

use PHPUnit\Framework\TestCase;

use Injx\InjxContainer;

class InjxContainerTest extends TestCase {
    protected function setUp() {
        $this->tested = new InjxContainer();        
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testContainerWithBadParameters() {
        new InjxContainer('foo');        
    }
    public function testContainerWithParameters() {
        $service = new class {  };
        $tested = new InjxContainer([ 'foo' => $service ]);
        $this->assertEquals($service, $tested->getService('foo'));
    }
    public function testGetServiceUnkown() {
        $this->assertEquals(NULL, $this->tested->getService('bar'));
    }
    public function testGetServiceKnown() {
        $service = new class {  };
        $this->tested->setService('foo', $service);
        $this->assertEquals($service, $this->tested->getService('foo'));
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseWithBadService() {
        $this->tested->raise('bar');
    }
    public function testRaise() {
        $this->tested->setService('foo', 'bar');
        $this->assertEquals(0, $this->tested->raise('foo'));
    }
}
