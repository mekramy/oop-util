
# Getter/Setter

#### Define Getter And Setter Methods

**Note:** You need to add `\MEkramy\OOPUtil\MapGetterSetter` trait to your class

```php
class Person
{
    # Use trait
    use \MEkramy\OOPUtil\MapGetterSetter;

    # class states
    private $fn;
    private $ln;

    # First name getter/setter
    public function setFirstName(string $firstName): void
    {
        $this->fn = $firstName;
    }
    public function getFirstName(): string
    {
        return $this->fn;
    }

    # Last name getter/setter
    public function setLastName(string $lastName): void
    {
        $this->ln = $lastName;
    }
    public function getLastName(): string
    {
        return $this->ln;
    }

    # Full name getter only
    public function getFullName(): string
    {
        return "{$this->fn} {$this->ln}";
    }
}
```

#### Use Property

**Note:** any property convert to camelCase style `getter`/`setter`. so you can call property in any style you like!

```php
$person = new Person();
# All property access is valid
$person->first_name = 'John';
$person->firstName = 'John';
$person->FirstName = 'John';

$person->lastName = 'Doe';

echo $person->full_name;

# This line throw error because full_name has no setter method
$person->full_name = 'John Doe'; # throws InvalidArgumentException

# This line throw error because no getter/setter defined for notExistsProperty
$person->notExistsProperty = 'Oops!';
```

#### Handle Undefined Getter And Setters

You can handle undefined `getter` and `setters` of class.

**Note:** do not use PHP magic `__get`/`__set` methods. you can use `__onGetFailed`/`__onSetFailed` methods instance

```php
class Person
{
    # class body ...

    private $extra = [];

    # Handle undefined getters
    protected function __onGetFailed($name){
        return array_key_exists($name, $this->extra) ? $this->extra[$name] : null;
    }

    # Handle undefined setters
    protected function __onSetFailed($name, $value): void
    {
        # disallow set full_name
        if(!in_array($name, ['full_name', 'fullName', 'FullName'])){
            $this->extra[$name] = $value;
        }
    }
}
```

Now you can use any property for above example

```php
$person = new Person();
$person->birth_date = '1991-1-1';
$person->skills = ['php', 'mysql'];
```

# Class Method Chaining Call

#### Define Chainable Methods

**Note:** You need to add `\MEkramy\OOPUtil\CanChained` trait to your class

**Note:** You can define excluded/included methods list to chaining call by override `__canChain`/`__cantChain` methods

**Note:** in this example both `__canChain`/`__cantChain` method do same work and you can override one of them only.

```php
class MyClass{

    use \MEkramy\OOPUtil\CanChained;

    /**
     * Methods list to include in chaining call
     *
     * @return array
     */
    protected function __canChain(): array
    {
        return ['doFirst', 'doSecond', 'doThird'];
    }

    /**
     * Methods list to exclude in chaining call
     *
     * @return array
     */
    protected function __cantChain(): array
    {
        return ['getResult'];
    }


    public function doFirst(string $input){ ... }

    public function doSecond(string $input){ ... }

    public function doThird(string $input){ ... }

    public function getResult(): string{ ... }
}
```

#### Use Method In Chaining Mode

To make chaining call of class methods you need to call `chaining` method first

```php
$instance = new MyClass();
$instance->chaining()->doFirst('first')->doSecond('second')->doThird('third');

# this line throws error because getResult method not chainable
$instance->chaining()->doFirst('first')->getResult();
```

#### Get Method Return Value

```php
# A: By calling normally without chaining
$instance->getResult();

# B: By calling getInstance method on chaining call
$instance->chaining()->doFirst('first')->getInstance()->getResult();
```

#### Use PHP __call Method

You can use PHP magic `__call` methods in your class and chaining call works fine!

```php
class Dummy
{
    use \MEkramy\OOPUtil\CanChained;

    protected $calls = [];

    public function __call($name, $arguments)
    {
        $this->calls[] = $name;
    }

    public function __cantChain(){
        return ['print'];
    }

    public function print(){
        print_r($this->calls);
    }
}

$dummy = new Dummy();
$dummy->chaining()->A()->B()->C()->D();
$dummy->print(); // Print: ['A', 'B', 'C', 'D']
```
