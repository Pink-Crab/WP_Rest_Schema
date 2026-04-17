<?php

declare(strict_types=1);

/**
 * Canary: parsed output keys must be a subset of WP's allowed schema
 * keywords plus the module's sanctioned extras.
 *
 * Guards against silent leaks of internal properties into the emitted
 * schema (e.g. the `name` leak fixed in 1.0.0).
 *
 * @package PinkCrab\WP_Rest_Schema
 * @since 1.0.0
 */

namespace PinkCrab\WP_Rest_Schema\Tests\Argument;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Schema;
use PinkCrab\WP_Rest_Schema\Argument\Any_Of_Type;
use PinkCrab\WP_Rest_Schema\Argument\Array_Type;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;
use PinkCrab\WP_Rest_Schema\Argument\One_Of_Type;
use PinkCrab\WP_Rest_Schema\Argument\Object_Type;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;

class Test_Output_Keys extends WP_UnitTestCase {

	/**
	 * WP-honoured schema keywords plus the module's sanctioned extras.
	 *
	 * @return array<int, string>
	 */
	protected function allowed_keys(): array {
		return array(
			// rest_get_allowed_schema_keywords() (WP core).
			'title',
			'description',
			'default',
			'type',
			'format',
			'enum',
			'items',
			'properties',
			'additionalProperties',
			'patternProperties',
			'minProperties',
			'maxProperties',
			'minimum',
			'maximum',
			'exclusiveMinimum',
			'exclusiveMaximum',
			'multipleOf',
			'minLength',
			'maxLength',
			'pattern',
			'minItems',
			'maxItems',
			'uniqueItems',
			'anyOf',
			'oneOf',
			// Module / route-arg extras we intentionally emit.
			'required',
			'readonly',
			'context',
			'arg_options',
			'$schema',
			'validate_callback',
			'sanitize_callback',
		);
	}

	/**
	 * Recursively collect all schema-level string keys, skipping the
	 * user-defined children of `properties` / `patternProperties` (those
	 * are the caller's field names, not schema keywords).
	 *
	 * @param array<mixed> $input
	 * @param array<int, string> $keys
	 */
	protected function collect_keys( array $input, array &$keys ): void {
		$skip_children = array( 'properties', 'patternProperties' );
		foreach ( $input as $key => $value ) {
			if ( is_string( $key ) ) {
				$keys[] = $key;
			}
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( is_string( $key ) && in_array( $key, $skip_children, true ) ) {
				foreach ( $value as $sub ) {
					if ( is_array( $sub ) ) {
						$this->collect_keys( $sub, $keys );
					}
				}
				continue;
			}
			$this->collect_keys( $value, $keys );
		}
	}

	/** @testdox A complex Schema's output must contain only WP-allowed or module-sanctioned keys. */
	public function test_complex_schema_output_keys_are_all_allowed(): void {
		$schema = Schema::on( 'post' )
			->description( 'A post.' )
			->integer_property( 'id', fn( $p ) => $p->readonly( true )->context( 'view', 'edit', 'embed' ) )
			->string_property( 'title', fn( $p ) => $p->required( true )->min_length( 1 )->max_length( 255 )->context( 'view', 'edit' ) )
			->string_property( 'status', fn( $p ) => $p->expected( 'publish', 'draft', 'pending' )->default( 'draft' ) )
			->array_property( 'tags', fn( $p ) => $p->string_item()->min_items( 0 )->max_items( 10 )->unique_items( true ) )
			->object_property(
				'meta',
				fn( $o ) => $o
					->string_property( 'slug' )
					->integer_property( 'views', fn( $p ) => $p->minimum( 0 ) )
					->required_properties( 'slug' )
			)
			->required_properties( 'id', 'title' )
			->to_array();

		$keys = array();
		$this->collect_keys( $schema, $keys );

		$disallowed = array_diff( array_unique( $keys ), $this->allowed_keys() );
		$this->assertSame(
			array(),
			array_values( $disallowed ),
			'Schema output contains keys that are neither WP-honoured nor module-sanctioned: ' . implode( ', ', $disallowed )
		);
	}

	/** @testdox A root-level One_Of_Type with mixed-type variants must contain only allowed keys. */
	public function test_one_of_output_keys_are_all_allowed(): void {
		$model  = One_Of_Type::on( 'thing' )
			->variant( String_Type::on( 'thing' )->min_length( 3 ) )
			->variant( Integer_Type::on( 'thing' )->minimum( 1 ) );
		$parsed = Argument_Parser::as_array( $model );
		$inner  = reset( $parsed );

		$keys = array();
		$this->collect_keys( (array) $inner, $keys );

		$disallowed = array_diff( array_unique( $keys ), $this->allowed_keys() );
		$this->assertSame( array(), array_values( $disallowed ) );
	}

	/** @testdox An Array_Type with items constraints and arg_options must contain only allowed keys. */
	public function test_array_with_arg_options_output_keys_are_all_allowed(): void {
		$model = Array_Type::on( 'tags' )
			->string_item()
			->min_items( 1 )
			->max_items( 5 )
			->unique_items( true )
			->arg_options( array( 'sanitize_callback' => 'wp_parse_list' ) );

		$parsed = Argument_Parser::as_array( $model );
		$inner  = reset( $parsed );

		$keys = array();
		$this->collect_keys( (array) $inner, $keys );

		$disallowed = array_diff( array_unique( $keys ), $this->allowed_keys() );
		$this->assertSame( array(), array_values( $disallowed ) );
	}
}
