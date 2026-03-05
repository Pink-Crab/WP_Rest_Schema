# WP Rest Schema - Project Summary

## Overview

A fluent PHP builder API for WordPress REST API JSON schemas. Converts verbose nested array definitions into clean, chainable method calls.

**Package:** `pinkcrab/wp-rest-schema`
**Namespace:** `PinkCrab\WP_Rest_Schema`
**PHP:** >= 7.4.0
**WordPress:** 6.6.*
**Tests:** 230 tests, 526 assertions

---

## Changes Made (v0.2.0)

### Bug Fixes

1. **`FORMAT_URL` renamed to `FORMAT_URI`** - WordPress uses `'uri'` format, not `'url'`. Breaking change.
2. **`additionalProperties` redesigned** - Now matches WP spec: accepts boolean (allow/disallow) or a single `Argument` schema. Old named-property approach removed.
3. **`Object_Attribute_Parser` completed** - Now parses `additionalProperties`, `patternProperties`, `minProperties`, `maxProperties`.
4. **Single-property parsing bug fixed** - Properties always keyed by name, regardless of count.
5. **Dead code removed** - Orphaned `object_attributes()` and `parse_object_properties()` methods deleted from `Argument_Parser`.
6. **`test_post_string` fixed** - Was using `type => object` (copy-paste bug), had no assertions (risky test).

### New Features

1. **`readonly(bool)` method** - Set/get readonly on any argument. Parsed as `'readonly'` in output.
2. **`title(string)` method** - Set/get title on any argument. Parsed as `'title'` in output.
3. **`field()` alias** - Alternative static constructor: `String_Type::field('name')` instead of `String_Type::on('name')`.
4. **Format constants** - Added `FORMAT_TEXT_FIELD` (`'text-field'`) and `FORMAT_TEXTAREA_FIELD` (`'textarea-field'`).
5. **`Argument_Parser::for_route()`** - Accepts multiple Arguments, returns merged keyed args array for `register_rest_route()`.
6. **`Schema` builder class** - Top-level builder for `WP_REST_Controller::get_item_schema()` output.

### Infrastructure

- PHP minimum bumped from 7.2 to 7.4
- WordPress dev dependencies bumped from 6.2 to 6.6

---

## Architecture

### Source Files (`src/`)

```
src/
  Schema.php                          # Top-level schema builder (NEW)
  Argument/
    Argument.php                      # Base class for all types
    String_Type.php                   # String schema (minLength, maxLength, pattern)
    Integer_Type.php                  # Integer schema (minimum, maximum, multipleOf)
    Number_Type.php                   # Number schema (minimum, maximum, multipleOf)
    Boolean_Type.php                  # Boolean schema
    Null_Type.php                     # Null schema
    Array_Type.php                    # Array schema (items, minItems, maxItems, uniqueItems)
    Object_Type.php                   # Object schema (properties, additionalProperties, patternProperties)
    Attribute/
      Children.php                    # Trait: factory for creating child types
      Element_Requirements.php        # Trait: allOf/anyOf/oneOf relationships
      Number_Attributes.php           # Trait: shared numeric properties
  Parser/
    Abstract_Parser.php               # Base parser class
    Argument_Parser.php               # Main parser orchestrator
    String_Attribute_Parser.php       # String-specific parsing
    Array_Attribute_Parser.php        # Array-specific parsing (recursive)
    Object_Attribute_Parser.php       # Object-specific parsing (recursive)
```

### Test Files (`tests/`)

```
tests/
  bootstrap.php
  HTTP_TestCase.php                   # Base class for REST API integration tests
  Argument/
    Test_Argument.php                 # Base argument tests (readonly, title, field, context)
    Test_String_Type.php
    Test_Integer_Type.php
    Test_Number_Type.php
    Test_Boolean_Type.php             # NEW
    Test_Null_Type.php                # NEW
    Test_Array_Type.php
    Test_Object_Type.php
    Parser/
      Abstract_Parser_Testcase.php    # Shared parser tests (14 per type)
      Test_String_Type_Parser.php
      Test_Integer_Type_Parser.php
      Test_Number_Type_Parser.php
      Test_Boolean_Type_Parser.php
      Test_Null_Type_Parser.php       # NEW
      Test_Array_Type_Parser.php
      Test_Object_Type_Parser.php     # EXPANDED (additionalProps, patternProps, min/maxProps, oneOf)
      Test_Children_Trait.php
      Test_For_Route.php              # NEW
  Schema/
    Test_Schema.php                   # NEW
  Application/
    Test_Post_Meta_Schema.php         # Integration tests with WP REST API
```

---

## Usage Examples

### Simple field schema (for register_post_meta)

```php
register_post_meta( 'post', 'fixed_in', array(
    'type'         => 'string',
    'single'       => true,
    'show_in_rest' => array(
        'schema' => Argument_Parser::for_meta_data(
            String_Type::field( 'fixed_in' )
                ->min_length( 10 )
                ->max_length( 42 )
                ->required()
                ->description( 'Required string, 10-42 chars' )
        ),
    ),
) );
```

### Route arguments (for register_rest_route)

```php
register_rest_route( 'my/v1', '/search', array(
    'methods'  => 'GET',
    'callback' => 'handle_search',
    'args'     => Argument_Parser::for_route(
        String_Type::field( 'query' )->required()->min_length( 1 ),
        Integer_Type::field( 'page' )->minimum( 1 )->default( 1 ),
        String_Type::field( 'order' )->expected( 'asc', 'desc' )->default( 'desc' )
    ),
) );
```

### Full schema (for WP_REST_Controller::get_item_schema)

```php
public function get_item_schema() {
    return Schema::on( 'post' )
        ->description( 'A blog post object.' )
        ->integer_property( 'id', function( Integer_Type $id ) {
            return $id->readonly()
                ->description( 'Unique identifier.' )
                ->context( 'view', 'edit', 'embed' );
        } )
        ->string_property( 'title', function( String_Type $t ) {
            return $t->required()
                ->description( 'The post title.' )
                ->context( 'view', 'edit' );
        } )
        ->string_property( 'status', function( String_Type $s ) {
            return $s->expected( 'publish', 'draft', 'pending' )
                ->context( 'view', 'edit' );
        } )
        ->additional_properties( false )
        ->to_array();
}
```

### Complex nested schema

```php
Object_Type::field( 'metadata' )
    ->string_property( 'title', fn( String_Type $t ) => $t->required() )
    ->integer_property( 'score', fn( Integer_Type $i ) => $i->minimum( 0 )->maximum( 100 ) )
    ->array_property( 'tags', fn( Array_Type $a ) => $a->string_item()->unique_items() )
    ->additional_properties( false )
```

---

## Supported Schema Keywords

All keywords from `rest_get_allowed_schema_keywords()` in WP core:

| Keyword | Supported | Method |
|---------|-----------|--------|
| type | Yes | `type()`, auto-set by type classes |
| format | Yes | `format()`, constants: `FORMAT_DATE_TIME`, `FORMAT_EMAIL`, `FORMAT_IP`, `FORMAT_URI`, `FORMAT_UUID`, `FORMAT_HEX`, `FORMAT_TEXT_FIELD`, `FORMAT_TEXTAREA_FIELD` |
| title | Yes | `title()` |
| description | Yes | `description()` |
| default | Yes | `default()` |
| enum | Yes | `expected()` |
| required | Yes | `required()` |
| readonly | Yes | `readonly()` |
| context | Yes | `context()` |
| minimum | Yes | `minimum()` (Number_Type, Integer_Type) |
| maximum | Yes | `maximum()` (Number_Type, Integer_Type) |
| exclusiveMinimum | Yes | `exclusive_minimum()` |
| exclusiveMaximum | Yes | `exclusive_maximum()` |
| multipleOf | Yes | `multiple_of()` |
| minLength | Yes | `min_length()` (String_Type) |
| maxLength | Yes | `max_length()` (String_Type) |
| pattern | Yes | `pattern()` (String_Type) |
| items | Yes | `string_item()`, `integer_item()`, etc. (Array_Type) |
| minItems | Yes | `min_items()` (Array_Type) |
| maxItems | Yes | `max_items()` (Array_Type) |
| uniqueItems | Yes | `unique_items()` (Array_Type) |
| properties | Yes | `string_property()`, `integer_property()`, etc. (Object_Type) |
| additionalProperties | Yes | `additional_properties(bool)`, `additional_properties_schema(Argument)` |
| patternProperties | Yes | `string_pattern_property()`, etc. (Object_Type) |
| minProperties | Yes | `min_properties()` (Object_Type) |
| maxProperties | Yes | `max_properties()` (Object_Type) |
| anyOf | Yes | `any_of()` (Array_Type, Object_Type) |
| oneOf | Yes | `one_of()` (Array_Type, Object_Type) |
| allOf | Yes | `all_of()` (default, Array_Type, Object_Type) |
| validate_callback | Yes | `validation()` |
| sanitize_callback | Yes | `sanitization()` |

---

## Next Steps

- Documentation (full API docs)
- PHPStan level 8 analysis pass
- WPCS sniff pass
- Consider v1.0.0 release
