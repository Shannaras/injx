<?php

namespace Injx;

/**
 * Container class to store service in the application root script or class.
 */
class InjxContainer {
    use Injx;
    
    public function __construct() {
        $this->injxFrom(new class {
            function getService() { 
                return NULL;
            } 
        });
    }    
}
