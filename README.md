# Injx

Lightweight Dependency Injection library for PHP, using traits.

## Get started

Add injx to your project:
```
    composer require labasse/injx
```

Create an `InjxContainer` and add services :

```
    $container = new \Injx\InjxContainer();

    $container -> setService('log', new Logger());
    $container -> setService('db', new Database());
```

To inject dependencies in a class:
- Add `use \Injx\Injx;` in the class:
```
    class Foo {
        use \Injx\Injx;
```
- Inject dependencies ASAP in the instance using the injx method:
```
    $foo = new Foo();
    $foo -> injx( $other_object_with_injx_trait );
    // or
    $foo -> injx( $container );
    // or
    $bar = new Bar( $other_object_with_injx_trait ); // with $this->injx( $arg ); in the constructor
```
That's it, this class is able to: 
- Get and use services:
``` 
    public function doSomething() {
        $logger = $this->getService('log');
        //...
``` 
- Override or make new services available for itself and injected descendants:
```
    $this->setService('route', new Route());
    $child = new Child();
    $child -> injx($this);
```    

## Exceptions
Injx throws few exceptions, here they are:
- `BadFunctionCallException('injx() must be called first')`: means you called getService before injecting using the injx() method
- `InvalidArgumentException('Argument must have a getService method')`: means you passed to injx() an object without `use Injx;`

## License
Injx is an open-source project released under the MIT license. See the LICENSE file for more information.


