# Simple dependency injection container for PHP
*There are many dependency injection containers out there, this one is minimalistic and straight forward. Doesn't have any third-party dependencies.*

[![Build Status](https://travis-ci.org/theorx/sdic.svg?branch=master)](https://travis-ci.org/theorx/sdic)
----

## Author
*Lauri Orgla <theorx@hotmail.com>*

## Documentation


### Dependency registration usage

```php

$container = new \Theorx\SDIC\SDIC();

//Register single dependency
$container->register(DependencyClassInterface::class, function(Theorx\SDIC\SDIC $container) {

    return new DependencyClass($container->get(DependencyClassDependency::class));
});

```

*Register shared dependency ( Singleton wrapper )*

```php

$container->register(DependencyClassInterface::class, function(Theorx\SDIC\SDIC $container) {

    return $container->shared(DependencyClassInterface::class, function() {
        return new DependencyClass();
    });
});

```

*Register array of dependencies*

```php

$container->registerArray([
    DependencyAInterface::class => function(){
        return new DependencyA();
    },
    DependencyBInterface::class => function (){
        return new DependencyB();
    }
]);

```

*You can check whether container contains dependency by name using __has__ method*

```php

if($container->has(DependencyAInterface::class)){
    //has dependency
}

```

*Getting dependencies from the container using __get__ method*

```php

$instance = $container->get(DependencyBInterface::class);

```

### Container extension
*Container extensions are meant to be used for adding dependencies to the container*
*Container extensions must implement `SDICExtension` interface which requires you to implement single method for defining array of dependencies*

### Example extension

```php

/**
 * Class ExampleExtension
 */
class ExampleExtension implements \Theorx\SDIC\Interfaces\SDICExtension {

    /**
     * @return array
     */
    public function registerDependencies() : array {

        return [
            DependencyDInterface::class => function() {

                return new DependencyD();
            },
            DependencyEInterface::class => function() {

                return new DependencyE();
            }
            //...
        ];
    }
}

```


### Loading container extension / extensions
*Method `loadExtension` loads extension to the container and registers new dependencies*

```php

$container->loadExtension(new ExampleExtension);

```

*For loading multiple extensions there is method `loadExtensions` which accepts array of objects which implement `SDICExtension` interface*


----

## SDIC api overview

```

    /**
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     *         Register name => callback,
     *         Callback example:
     *         function ($container) {
     *              return $container->shared(NAME, function($container){
     *                  return {{shared instance}};
     *              });
     *          }
     *
     *         This method is chainable, register()->register()->register()-> etc..
     *
     * @param string   $name
     * @param callable $callback
     *
     * @return SDIC
     */
     
```

* `register`(string $name, callable $callback) : SDIC 

```
    /**
     * Accepts array of dependencies. Example. [name => callback]
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param array $dependencies
     */
```

* `registerArray`(array $dependencies)

```
    /**
     * Used for creating shared instances
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param string   $name
     * @param callable $callback
     *
     * @return mixed
     */
```

* `shared`(string $name, callable $callback)

```
    /**
     * Fetches dependency by name
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param string $name
     *
     * @return mixed
     * @throws DependencyNotFoundException
     */
```

* `get`(string $name)

```
    /**
     * Checks whether the container has given dependency
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param string $name
     *
     * @return bool
     */
```

* `has`(string $name) 

```
    /**
     * Gets shared instances
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     * @return array
     */
```

* `getInstances`() 

```
    /**
     * Gets current registry
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     * @return array
     */
```
* `getRegistry`() 

```
    /**
     * Load container extension which is instance of class that implements SDICExtension
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param SDICExtension $extension
     */
```

* `loadExtension`(SDICExtension $extension) 

```
    /**
     * Loads a set of extensions
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param array $extensions
     */
```

* `loadExtensions`(array $extensions) 

```
    /**
     * Gets list of loaded extensions
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     * @return SDICExtension[]
     */
```

* `getLoadedExtensions`() : array 