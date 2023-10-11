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

> `min_length(int $min_length): self`

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

> `max_length(int $max_length): self`

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

> `pattern(string $pattern): self`

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

> Shares all the attributes of the [Number Attributes](./Argument/number-attributes.md)

### Minimum

You can define the minimum of the number.

> `minimum(float $minimum): self`

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

> `maximum(float $maximum): self`

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

> Shares all the attributes of the [Number Attributes](./Argument/number-attributes.md)

### Minimum

You can define the minimum of the integer.

> `minimum(int $minimum): self`

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

> `maximum(int $maximum): self`

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

## Array Argument Type

### Item Relationships

It is possible to set the relationship between the items of the array.

#### All Of
> `all_of(): self`

```php
$argument = Array_Type::new()
    ->all_of()
    ->item(String_Type::new())
    ->item(Number_Type::new());
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'allOf' => [
            [
                'type' => 'string'
            ],
            [
                'type' => 'number'
            ]
        ]
    ]
]
```

#### Any Of
> `any_of(): self`

```php
$argument = Array_Type::new()
    ->any_of()
    ->item(String_Type::new())
    ->item(Number_Type::new());
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'anyOf' => [
            [
                'type' => 'string'
            ],
            [
                'type' => 'number'
            ]
        ]
    ]
]
```

#### One Of
> `one_of(): self`

```php
$argument = Array_Type::new()
    ->one_of()
    ->item(String_Type::new())
    ->item(Number_Type::new());
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'oneOf' => [
            [
                'type' => 'string'
            ],
            [
                'type' => 'number'
            ]
        ]
    ]
]
```


### Items

You can define the items of the array.

> `item(Argument $item): self`

```php

$argument = Array_Type::new()
    ->item(String_Type::new());
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'type' => 'string'
    ]
]
```
### Item Helpers

#### String Item

>`string_item(callable(String_Type):String_Type $callback): self`

```php
$argument = Array_Type::new()
    ->string_item(function($string){
        return $string->min_length(10);
    });
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'type' => 'string',
        'minLength' => 10
    ]
]
```

#### Number Item

>`number_item(callable(Number_Type):Number_Type $callback): self`

```php

$argument = Array_Type::new()
    ->number_item(function($number){
        return $number->minimum(10);
    });
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'type' => 'number',
        'minimum' => 10
    ]
]
```

#### Integer Item

>`integer_item(callable(Integer_Type):Integer_Type $callback): self`

```php

$argument = Array_Type::new()
    ->integer_item(function($integer){
        return $integer->minimum(10);
    });
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'type' => 'integer',
        'minimum' => 10
    ]
]
```

#### Boolean Item

>`boolean_item(callable(Boolean_Type):Boolean_Type $callback): self`

```php

$argument = Array_Type::new()
    ->boolean_item(function($boolean){
        return $boolean;
    });
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'type' => 'boolean'
    ]
]
```

#### Null Item

>`null_item(callable(Null_Type):Null_Type $callback): self`

```php

$argument = Array_Type::new()
    ->null_item();
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'type' => 'null'
    ]
]
```

#### Object Item

>`object_item(callable(Object_Type):Object_Type $callback): self`

```php

$argument = Array_Type::new()
    ->object_item();
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'type' => 'object',
        ]
    ]
]
```

#### Array Item

>`array_item(callable(Array_Type):Array_Type $callback): self`

```php

$argument = Array_Type::new()
    ->array_item();
```

*Renders as* 

```php
[
    'type' => 'array',
    'items' => [
        'type' => 'array',
        ]
    ]
]
```

### Minimum Items

You can define the minimum items of the array.

> `min_items(int $min_items): self`

```php
$argument = Array_Type::new()
    ->min_items(10);
```

*Renders as* 

```php
[
    'type' => 'array',
    'minItems' => 10
]
```

### Maximum Items

You can define the maximum items of the array.

> `max_items(int $max_items): self`

```php

$argument = Array_Type::new()
    ->max_items(10);
```

*Renders as* 

```php
[
    'type' => 'array',
    'maxItems' => 10
]
```

### Unique Items

You can define the unique items of the array.

> `unique_items(bool $unique_items): self`

```php

$argument = Array_Type::new()
    ->unique_items(true);
```

*Renders as* 

```php
[
    'type' => 'array',
    'uniqueItems' => true
]
```

## Object Argument Type

### Properties

You can define the properties of the object using the following helpers.

#### String Property
> `string_property(string $name, ?callable(String_Type):String_Type $callback): self`

```php
$argument = Object_Type::new()
    ->string_property('name', function($string){
        return $string->min_length(10);
    });
```

*Renders as* 

```php
[
    'type' => 'object',
    'properties' => [
        'name' => [
            'type' => 'string',
            'minLength' => 10
        ]
    ]
]
```

#### Number Property
> `number_property(string $name, ?callable(Number_Type):Number_Type $callback): self`

```php

$argument = Object_Type::new()
    ->number_property('name', function($number){
        return $number->minimum(10);
    });
```

*Renders as* 

```php
[
    'type' => 'object',
    'properties' => [
        'name' => [
            'type' => 'number',
            'minimum' => 10
        ]
    ]
]
```

#### Integer Property

> `integer_property(string $name, ?callable(Integer_Type):Integer_Type $callback): self`

```php

$argument = Object_Type::new()
    ->integer_property('name');
```

*Renders as* 

```php
[
    'type' => 'object',
    'properties' => [
        'name' => [
            'type' => 'integer'
        ]
    ]
]
```

#### Boolean Property

> `boolean_property(string $name, ?callable(Boolean_Type):Boolean_Type $callback): self`

```php

$argument = Object_Type::new()
    ->boolean_property('name');
```

*Renders as* 

```php
[
    'type' => 'object',
    'properties' => [
        'name' => [
            'type' => 'boolean'
        ]
    ]
]
```

#### Null Property

> `null_property(string $name, ?callable(Null_Type):Null_Type $callback): self`

```php

$argument = Object_Type::new()
    ->null_property('name', function($null){
        return $null->description('This is a null property');
    });
```

*Renders as* 

```php
[
    'type' => 'object',
    'properties' => [
        'name' => [
            'type' => 'null',
            'description' => 'This is a null property'
        ]
    ]
]
```

#### Object Property

> `object_property(string $name, ?callable(Object_Type):Object_Type $callback): self`

```php

$argument = Object_Type::new()
    ->object_property('name');
```

*Renders as* 

```php
[
    'type' => 'object',
    'properties' => [
        'name' => [
            'type' => 'object'
        ]
    ]
]
```

#### Array Property

> `array_property(string $name, ?callable(Array_Type):Array_Type $callback): self`

```php

$argument = Object_Type::new()
    ->array_property('name', fn($e) => $e->string_item());
```

*Renders as* 

```php
[
    'type' => 'object',
    'properties' => [
        'name' => [
            'type' => 'array',
            'items' => [
                'type' => 'string'
            ]
        ]
    ]
]
```

#### Union Property

> `union_property(string $name, ?callable(Union_Type):Union_Type $callback): self`

```php

$argument = Object_Type::new()
    ->union_property('name', function($union){
        return $union
            ->option(String_Type::new())
            ->option(Number_Type::new());
    });
```

*Renders as* 

```php
[
    'type' => 'object',
    'properties' => [
        'name' => [
            'anyOf' => [
                [
                    'type' => 'string'
                ],
                [
                    'type' => 'number'
                ]
            ]
        ]
    ]
]
```

### Additional Properties

By default and object does allow additional properties, but you can change this behavior.

#### Allow Additional Properties

> `additional_properties(bool): self`

```php

$args = Object_Type::new()
    ->string_property('name')
    ->additional_properties(false);
```

*Renders as* 

```php
[
    'type' => 'object',
    'properties' => [
        'name' => [
            'type' => 'string'
        ]
    ],
    'additionalProperties' => false
]
```
