<?php

namespace Injx\Test;

use PHPUnit\Framework\TestCase;

use Injx\InjxContainer;

class InjxContainerTest extends TestCase {
    protected function setUp() {
        $this->tested = new InjxContainer();        
    }
    public function testGetServiceUnkown() {
        $this->assertEquals(NULL, $this->tested->getService('bar'));
    }
    public function testGetServiceKnown() {
        $service = new class {  };
        $this->tested->setService('foo', $service);
        $this->assertEquals($service, $this->tested->getService('foo'));
    }
}
