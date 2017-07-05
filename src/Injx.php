<?php

namespace Injx;

/**
 * Trait for dependency injection support
 *
 * @author syl
 */
trait Injx {
        
    public function injx($caller) {
        if(!method_exists($caller, 'getService')) {
            throw new \InvalidArgumentException('Argument must have a getService method');
        }
        $this->injxCaller = $caller;
        return $this;
    }
    public function setService(string $key, $service) {
        $this->injxServices[$key] = $service;
    }
    
    public function getService(string $key) {
        if(isset($this->injxServices[$key])) {
            return $this->injxServices[$key];
        }
        else if($this->injxCaller) {
            return $this->injxCaller->getService($key);
        }
        else {
            throw new \BadMethodCallException('injx() must be called first');
        }
    }
    
    private $injxServices = [];
    private $injxCaller = NULL;
}
