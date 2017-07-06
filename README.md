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
- Add the trait in the class:
```
    class Foo {
        use \Injx\Injx;
```
- Inject dependencies ASAP in the instance using the injxFrom or injxTo (if you're not sure if injxTo uses Injx trait)  method:
```
    $foo = new Foo();
    $foo -> injxFrom( $container );
    // or
    $foo = new Foo();
    $container -> injxTo( $foo );
```
That's it, this class is able to: 
- Get and use services:
``` 
    $logger = $this->getService('log');    
``` 
- Override or make new services available for itself and injected descendants:
```
    $this->setService('route', new Route());
    $child = new Child();
    $this->injxTo( $child );
```    

## Advanced usage

### Inheritance

When you use the Injx trait in a parent class, all subclasses will have the dependency injection support.

### Not sure if the services are available ?

You can check before use it:
```
    if( $this->injxOk() && $this->getService('log') ) {
        $this->getService('log')->....
```

### Make services available in the constructor

You may need some services in the constructor. You have to pass a container as argument to inject services at the begining:
```
    class Bar {
        use \Injx\Injx;

        public function __construct($some_arg, $injx_parent, ...) {
            $injx_parent -> injxTo( $this );
            $this -> getService('log') -> info( ... );
            //...
        }
    }
```

### Shortcuts

You can *abbreviate* some notations :
```
    $child = new Child();
    $this->injxTo( $child );
    // shorten to
    $child = $this->injxTo( new Child() );

    $foo = new Foo();
    $foo -> injxFrom( $container );
    // shorten to
    $foo = (new Foo()) -> injxFrom( $container );

    $container -> setService('log', new Logger());
    $container -> setService('db', new Database());
    // either shorten to
    $container = new \Injx\InjxContainer([
        'log' => new Logger(),
        'db'  => new Database()
    ]);
    // or (not really) shorten to
    $container
        -> setService('log', new Logger());
        -> setService('db', new Database());
```

## Known issues and exceptions
If you're faced with a *Allowed memory size exhausted* issue. Check your injections, you probably wrote a circular reference like this : `$a->injxTo($b); $b->injxTo($c); $c->injxTo($a);`.

Injx throws few exceptions, here they are:
- `BadFunctionCallException('injx() must be called first')`: means you called getService before injecting using the injx() method
- `InvalidArgumentException('Argument must have a getService method')`: means you passed to injx() an object without `use Injx;`

## License
Injx is an open-source project released under the MIT license. See the LICENSE file for more information.


