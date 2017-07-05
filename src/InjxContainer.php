<?php

namespace Injx;

/**
 * Injx container class
 *
 * @author syl
 */
class InjxContainer {
    use Injx;
    
    public function __construct() {
        $this->injx(new class {
            function getService() { 
                return NULL;
            } 
        });
    }    
}
