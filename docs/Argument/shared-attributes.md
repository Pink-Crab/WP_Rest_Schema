# Shared Argument Attributes

> The following attributes can be used on all argument types except for the Union type.

## Description

You can define a description for the argument, which will be used in the schema.

### description(string $description)

```php
$argument = String_Type::on('my_string')
    ->description('This is a string argument');
```
*Renders as* 
```php
[
    'type' => 'string',
    'description' => 'This is a string argument'
]
```

## Default Value

You can define a default value for the argument, which will be used in the schema.

### default(mixed $default)

```php
$argument = String_Type::on('my_string')
    ->default('Hello World');
```

*Renders as* 
```php
[
    'type' => 'string',
    'default' => 'Hello World'
]
```

## Required

You can define if the argument is required, which will be used in the schema.

### required(bool $required)

```php
$argument = String_Type::on('my_string')
    ->required();
```

*Renders as* 
```php
[
    'type' => 'string',
    'required' => true
]
```

> You can also set as explicitly not required by passing false.

## Validate

You can define a callback to validate the argument, which will be used in the schema.

### validation(callable $validate_callback)

> `function(mixed $value, WP_REST_Request $request, string $param): true|WP_Error`

```php
$argument = String_Type::on('my_string')
    ->validation(function(mixed $value, WP_REST_Request $request, string $param){
        return strlen($value) > 10;
    });
```

*Renders as* 
```php
[
    'type' => 'string',
    'validate_callback' => function(mixed $value, WP_REST_Request $request, string $param){
        return strlen($value) > 10;
    }
]
```

## Sanitize

You can define a callback to sanitize the argument, which will be used in the schema.

### sanitize(callable $sanitize_callback)

```php
$argument = String_Type::on('my_string')
    ->sanitize(function(mixed $value, WP_REST_Request $request, string $param){
        return strtolower($value);
    });
```
*Renders as* 
```php
[
    'type' => 'string',
    'sanitize_callback' => function(mixed $value, WP_REST_Request $request, string $param){
        return strtolower($value);
    }
]
```
