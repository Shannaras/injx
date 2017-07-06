<?php

namespace Injx;

/**
 * Trait for dependency injection support
 *
 * @author syl
 */
trait Injx {
        
    /**
     * Inject services into the calling object from the container passed as argument.
     * This method or injxTo ({@see \Injx\Injx::injxTo}) must be called before any call to getService.
     * @param mixed $caller Any object using the Injx trait
     * @return The invoking instance for method chaining or other code convenience.
     * @throws \InvalidArgumentException if the argument passed do not use the Injx trait.
     * @see \Injx\Injx::injxTo
     */
    public function injxFrom($caller) {
        if(!\method_exists($caller, 'getService')) {
            throw new \InvalidArgumentException('Argument must have a getService method');
        }
        $this->injxCaller = $caller;
        return $this;
    }
    /**
     * Inject services to the given object if it uses the Injx trait.
     * This method or injxFrom ({@see \Injx\Injx::injxFrom}) must be called before any call to getService.
     * @param mixed $target Any object potentially needing the services
     * @return The argument passed for code convenience.
     * @see \Injx\Injx::injxFrom
     */
    public function injxTo($target) {
        if(\method_exists($target, 'injxTo')) {
            $target->injxFrom($this);
        }
        return $target;
    }
    /**
     * Check if the current instance is injected or not
     * @return bool true if injected (injxTo or injxFrom called), false if not
     */
    public function injxOk() : bool {
        return $this->injxCaller != NULL;
    }
    /**
     * Associates a service to a key and make it visible from all objects injected from this one.
     * If a service is already associated to this key, it is replaced or masked by the new service.
     * @param string $key Key used to retreive the service
     * @param type $service Any object proposing a service
     * @return The invoking instance for method chaining.
     */
    public function setService(string $key, $service) {
        $this->injxServices[$key] = $service;
        return $this;
    }
    
    /**
     * Get a service from its key 
     * @param string $key
     * @return type
     * @throws \BadMethodCallException If injxFrom or injxTo has not been previously called once.
     */
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
