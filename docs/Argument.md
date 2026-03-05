# Argument (Base Class)

The `Argument` class is the foundation for all schema types. Every type (`String_Type`, `Integer_Type`, `Number_Type`, `Boolean_Type`, `Null_Type`, `Array_Type`, `Object_Type`) extends this class and inherits all of its methods.

**Namespace:** `PinkCrab\WP_Rest_Schema\Argument\Argument`

---

## Creating an Argument

All argument types support two static constructors: `on()` and `field()` (they are identical).

```php
use PinkCrab\WP_Rest_Schema\Argument\String_Type;

// Both are equivalent
$arg = String_Type::on( 'my_field' );
$arg = String_Type::field( 'my_field' );
```

An optional configuration callback can be passed as the second parameter:

```php
$arg = String_Type::field( 'name', function( String_Type $s ): String_Type {
    return $s->required()->min_length( 1 );
} );
```

> `Boolean_Type` and `Null_Type` extend `Argument` with no additional methods — they simply set their type to `'boolean'` or `'null'` respectively. All methods documented here are available on them.

---

## Shared Methods

These methods are available on **every** argument type.

### `type( string|array $type ): self`

Set the schema type. This is automatically set by each type class (e.g., `String_Type` sets `'string'`). You only need to call this manually if setting a union type.

```php
$arg = String_Type::field( 'value' )
    ->type( array( 'string', 'null' ) );
```

### `union_with_type( string $type ): self`

Add an additional type to create a union type. The original type is preserved.

```php
$arg = String_Type::field( 'value' )
    ->union_with_type( 'null' );
// type becomes ['string', 'null']
```

### `description( string $description ): self`

Set a human-readable description for the argument.

```php
$arg = String_Type::field( 'email' )
    ->description( 'The user email address.' );
```

### `default( mixed $default ): self`

Set a default value for the argument.

```php
$arg = Integer_Type::field( 'page' )
    ->default( 1 );
```

### `required( bool $required = true ): self`

Mark the argument as required. Defaults to `true` when called without a parameter.

```php
$arg = String_Type::field( 'name' )->required();
$arg = String_Type::field( 'name' )->required( false );
```

### `readonly( bool $readonly = true ): self`

Mark the argument as readonly. Defaults to `true` when called without a parameter.

```php
$arg = Integer_Type::field( 'id' )->readonly();
```

### `title( string $title ): self`

Set a title for the argument.

```php
$arg = String_Type::field( 'name' )->title( 'Display Name' );
```

### `format( string $format ): self`

Set the format constraint. Use the provided constants for valid WordPress formats.

```php
$arg = String_Type::field( 'email' )
    ->format( Argument::FORMAT_EMAIL );
```

### `expected( mixed ...$values ): self`

Set the allowed values (maps to `enum` in the schema). Multiple calls are additive.

```php
$arg = String_Type::field( 'status' )
    ->expected( 'publish', 'draft', 'pending' );

// Can also be called multiple times
$arg = String_Type::field( 'status' )
    ->expected( 'publish' )
    ->expected( 'draft', 'pending' );
```

### `context( string ...$contexts ): self`

Set the contexts where this field is visible. Standard WordPress contexts are `'view'`, `'edit'`, and `'embed'`. Multiple calls are additive.

```php
$arg = Integer_Type::field( 'id' )
    ->context( 'view', 'edit', 'embed' );
```

### `name( string $name ): self`

Set a display name for the argument.

```php
$arg = String_Type::field( 'email' )
    ->name( 'Email Address' );
```

### `validation( callable $callback ): self`

Set a custom validation callback. Maps to `validate_callback` in the schema.

```php
$arg = String_Type::field( 'slug' )
    ->validation( function( $value, $request, $key ) {
        return preg_match( '/^[a-z0-9-]+$/', $value );
    } );
```

### `sanitization( callable $callback ): self`

Set a custom sanitization callback. Maps to `sanitize_callback` in the schema.

```php
$arg = String_Type::field( 'title' )
    ->sanitization( 'sanitize_text_field' );
```

### `set_attributes( array $attributes ): self`

Set custom attributes as a key-value array. Useful for extending with non-standard properties.

```php
$arg = String_Type::field( 'name' )
    ->set_attributes( array( 'custom_key' => 'value' ) );
```

---

## Getter Methods

Every setter has a corresponding getter:

| Setter | Getter | Returns |
|--------|--------|---------|
| `type()` | `get_type()` | `string\|array\|null` |
| `description()` | `get_description()` | `string` |
| `default()` | `get_default()` | `mixed` |
| — | `has_default()` | `bool` |
| `required()` | `is_required()` | `bool` |
| `required()` | `get_required()` | `?bool` |
| `readonly()` | `get_readonly()` | `?bool` |
| `title()` | `get_title()` | `?string` |
| `format()` | `get_format()` | `?string` |
| `expected()` | `get_expected()` | `?array` |
| `context()` | `get_context()` | `string[]` |
| `name()` | `get_name()` | `?string` |
| `validation()` | `get_validation()` | `?callable` |
| `sanitization()` | `get_sanitization()` | `?callable` |
| — | `get_key()` | `string` |
| `set_attributes()` | `get_attributes()` | `array` |

---

## Constants

### Type Constants

| Constant | Value |
|----------|-------|
| `Argument::TYPE_STRING` | `'string'` |
| `Argument::TYPE_INTEGER` | `'integer'` |
| `Argument::TYPE_NUMBER` | `'number'` |
| `Argument::TYPE_BOOLEAN` | `'boolean'` |
| `Argument::TYPE_NULL` | `'null'` |
| `Argument::TYPE_ARRAY` | `'array'` |
| `Argument::TYPE_OBJECT` | `'object'` |

### Format Constants

| Constant | Value | Description |
|----------|-------|-------------|
| `Argument::FORMAT_DATE_TIME` | `'date-time'` | ISO 8601 date-time |
| `Argument::FORMAT_EMAIL` | `'email'` | Email address |
| `Argument::FORMAT_IP` | `'ip'` | IPv4 or IPv6 address |
| `Argument::FORMAT_URI` | `'uri'` | URI |
| `Argument::FORMAT_UUID` | `'uuid'` | UUID |
| `Argument::FORMAT_HEX` | `'hex-color'` | Hex colour value |
| `Argument::FORMAT_TEXT_FIELD` | `'text-field'` | Single-line text |
| `Argument::FORMAT_TEXTAREA_FIELD` | `'textarea-field'` | Multi-line text |

---

## Method Chaining

All setters return `self`, so every method can be chained:

```php
$arg = String_Type::field( 'email' )
    ->required()
    ->format( Argument::FORMAT_EMAIL )
    ->description( 'A valid email address.' )
    ->context( 'view', 'edit' )
    ->sanitization( 'sanitize_email' )
    ->validation( 'is_email' );
```
