<?php

namespace Injx;

/**
 * Container class to store service in the application root script or class.
 */
class InjxContainer {
    use Injx;
    
    /**
     * Build a container with optional services.
     * This code:
     * ```
     *    $injx = new InjxContainer([
     *      'log' => new Logger(),
     *      'db'  => new Db(...)
     *    ]);
     * ```
     * Is similar to:
     * ```
     *    $injx = new InjxContainer();
     *    $injx -> setService('log', new Logger());
     *    $injx -> setService('db' ,new Db(...));
     *    ]);
     * ```
     * @param type $services Services to use for container initialization.
     * @throws \InvalidArgumentException If argument is not an array
     */
    public function __construct($services=[]) {
        if(!is_array($services)) {
            throw new \InvalidArgumentException('Parameter must be an array');
        }
        $this->injxFrom(new class {
            function getService() { 
                return NULL;
            } 
        });
        foreach($services as $key => $service) {
            $this->setService($key, $service);
        }        
    }    
}
