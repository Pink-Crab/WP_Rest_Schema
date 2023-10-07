# Number Attributes

Selection of attributes for the number argument type.

## Exclusive Minimum

You can define the exclusive minimum of the number.

### exclusive_minimum(bool $exclusive_minimum)

```php

$argument = Number_Type::new()
    ->minimum(10)
    ->exclusive_minimum(true);
```

*Renders as* 

```php
[
    'type' => 'number',
    'minimum' => 10,
    'exclusiveMinimum' => true
]
```

## Exclusive Maximum

You can define the exclusive maximum of the number.

### exclusive_maximum(bool $exclusive_maximum)

```php

$argument = Number_Type::new()
    ->maximum(10)
    ->exclusive_maximum(true);
```

*Renders as* 

```php
[
    'type' => 'number',
    'maximum' => 10,
    'exclusiveMaximum' => true
]
```

## Multiple Of

You can define the multiple of the number.

### multiple_of(float $multiple_of)

```php

$argument = Number_Type::new()
    ->multiple_of(10);
```

*Renders as* 

```php
[
    'type' => 'number',
    'multipleOf' => 10
]
```
