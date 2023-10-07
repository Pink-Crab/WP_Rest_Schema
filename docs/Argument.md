# Argument

Shares all the attributes of the [Shared Argument Attributes](./shared-attributes.md)

## Boolean Argument Type

> There are no additional attributes for the boolean type.

```php
$args = Boolean_Type::new();
```

*Renders as* 

```php
[
    'type' => 'boolean'
]
```

## Null Argument Type

> There are no additional attributes for the null type.

```php
$args = Null_Type::new();
```

*Renders as* 

```php
[
    'type' => 'null'
]
```

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

## Number Argument Type

> Shares all the attributes of the [Number Attributes](./number-attributes.md)

### Minimum

You can define the minimum of the number.

> `minimum(float $minimum)`

```php
$argument = Number_Type::new()
    ->minimum(3.14);
```

*Renders as* 

```php
[
    'type' => 'number',
    'minimum' => 3.14
]
```

### Maximum

You can define the maximum of the number.

> `maximum(float $maximum)`

```php
$argument = Number_Type::new()
    ->maximum(3.14);
```

*Renders as* 

```php
[
    'type' => 'number',
    'maximum' => 3.14
]
```

## Integer Argument Type

> Shares all the attributes of the [Number Attributes](./number-attributes.md)

### Minimum

You can define the minimum of the integer.

> `minimum(int $minimum)`

```php
$argument = Integer_Type::new()
    ->minimum(10);
```

*Renders as* 

```php
[
    'type' => 'integer',
    'minimum' => 10
]
```

### Maximum

You can define the maximum of the integer.

> `maximum(int $maximum)`

```php
$argument = Integer_Type::new()
    ->maximum(10);
```

*Renders as* 

```php
[
    'type' => 'integer',
    'maximum' => 10
]
```
