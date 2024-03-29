#Gaufrette Test Suite

You want to create your own adapter, you can use this test suite to test your adapter behavior.

##Installation

```bash
composer require --dev gaufrette/test-suite dev-master
```

##Usage

You have to create a factory which will instantiate your adapter. This factory should implements `Gaufrette\TestSuite\Adapter\AdapterFactory`

```php
namespace MyNamespace;

use Gaufrette\TestSuite\Adapter\AdapterFactory;

class MyFactory implements AdapterFactory
{
    function create()
    {
        return new LocalAdapter('../my-path');
    }

    function destroy()
    {
        //If you have to do something after tests
    }
}
```

Then, just launch the `test-suite` command with the class of your factory as argument.

```bash
./bin/test-suite run MyNamespace/AdapterFactory
```

##Result

```txt
File content: OKAY

File mime type: OKAY

File size: ERROR

 Some errors

File metadata: UNSUPPORTED
```
