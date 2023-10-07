# Argument

Shares all the attributes of the [Shared Argument Attributes](./shared-attributes.md)

## Boolean Argument Type

> There are no additional attributes for the boolean type.


## String Argument Type

### Min Length

You can define the minimum length of the string.

> `min_length(int $min_length)`

```php
$argument = String_Type::new()
    ->min_length(10);
```

*Renders as* 
```php
[
    'type' => 'string',
    'minLength' => 10
]
```

### Max Length

You can define the maximum length of the string.

> `max_length(int $max_length)`

```php
$argument = String_Type::new()
    ->max_length(10);
```

*Renders as* 
```php
[
    'type' => 'string',
    'maxLength' => 10
]
```

### Pattern

You can define a regex pattern for the string.

> `pattern(string $pattern)`

```php
$argument = String_Type::new()
    ->pattern('/[a-z]{1,10}/');
```

*Renders as* 
```php
[
    'type' => 'string',
    'pattern' => '/[a-z]{1,10}/'
]
```
