<?php

declare(strict_types=1);

/**
 * Unit Tests for the object type parser
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\WP_Rest_Schema
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Tests\Argument\Parser;

use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Argument\Object_Type;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;
use PinkCrab\WP_Rest_Schema\Tests\Argument\Parser\Abstract_Parser_Testcase;

class Test_Object_Type_Parser extends Abstract_Parser_Testcase {

	public function type_class(): string {
		return Object_Type::class;
	}

	public function type_name(): string {
		return 'object';
	}

	/** @testdox It should be possible to parse multiple properties defined. */
	public function test_can_parse_regular_properties(): void {
		$expected = array(
			'arg-name' => array(
				'type'       => 'object',
				'properties' => array(
					'foo' => array(
						'type' => 'string',
					),
					'bar' => array(
						'type' => 'boolean',
					),
				),
			),
		);

		$model = Object_Type::on( 'arg-name' )
			->string_property( 'foo' )
			->boolean_property( 'bar' );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox It should be possible to parse a single property without losing the key. */
	public function test_can_parse_single_property(): void {
		$expected = array(
			'arg-name' => array(
				'type'       => 'object',
				'properties' => array(
					'foo' => array(
						'type' => 'string',
					),
				),
			),
		);

		$model = Object_Type::on( 'arg-name' )
			->string_property( 'foo' );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox It should be possible to parse additionalProperties as boolean true. */
	public function test_can_parse_additional_properties_boolean_true(): void {
		$expected = array(
			'arg-name' => array(
				'type'                 => 'object',
				'additionalProperties' => true,
			),
		);

		$model = Object_Type::on( 'arg-name' )
			->additional_properties( true );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox It should be possible to parse additionalProperties as boolean false. */
	public function test_can_parse_additional_properties_boolean_false(): void {
		$expected = array(
			'arg-name' => array(
				'type'                 => 'object',
				'additionalProperties' => false,
			),
		);

		$model = Object_Type::on( 'arg-name' )
			->additional_properties( false );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox It should be possible to parse additionalProperties as a schema definition. */
	public function test_can_parse_additional_properties_schema(): void {
		$expected = array(
			'arg-name' => array(
				'type'                 => 'object',
				'additionalProperties' => array(
					'type' => 'string',
				),
			),
		);

		$model = Object_Type::on( 'arg-name' )
			->additional_properties_schema( String_Type::on( 'extra' ) );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox It should be possible to parse pattern properties. */
	public function test_can_parse_pattern_properties(): void {
		$expected = array(
			'arg-name' => array(
				'type'              => 'object',
				'patternProperties' => array(
					'^\\w+$' => array(
						'type' => 'string',
					),
				),
			),
		);

		$model = Object_Type::on( 'arg-name' )
			->string_pattern_property( '^\\w+$' );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox It should be possible to parse minProperties. */
	public function test_can_parse_min_properties(): void {
		$expected = array(
			'arg-name' => array(
				'type'          => 'object',
				'minProperties' => 2,
			),
		);

		$model = Object_Type::on( 'arg-name' )
			->min_properties( 2 );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox It should be possible to parse maxProperties. */
	public function test_can_parse_max_properties(): void {
		$expected = array(
			'arg-name' => array(
				'type'          => 'object',
				'maxProperties' => 10,
			),
		);

		$model = Object_Type::on( 'arg-name' )
			->max_properties( 10 );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox It should be possible to parse properties with oneOf relationship. */
	public function test_can_parse_one_of_relationship(): void {
		$expected = array(
			'arg-name' => array(
				'type'       => 'object',
				'properties' => array(
					'oneOf' => array(
						'foo' => array(
							'type' => 'string',
						),
						'bar' => array(
							'type' => 'integer',
						),
					),
				),
			),
		);

		$model = Object_Type::on( 'arg-name' )
			->string_property( 'foo' )
			->integer_property( 'bar' )
			->one_of();

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}
}
