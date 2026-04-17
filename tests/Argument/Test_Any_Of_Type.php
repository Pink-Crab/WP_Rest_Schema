<?php

declare(strict_types=1);

namespace PinkCrab\WP_Rest_Schema\Tests\Argument;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Argument\Any_Of_Type;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;

class Test_Any_Of_Type extends WP_UnitTestCase {

	/** @testdox Any_Of_Type should emit anyOf at the schema root with variant schemas as sibling entries. */
	public function test_any_of_emits_root_level_combinator(): void {
		$expected = array(
			'thing' => array(
				'anyOf' => array(
					array( 'type' => 'string' ),
					array( 'type' => 'integer' ),
				),
			),
		);

		$model = Any_Of_Type::on( 'thing' )
			->variant( String_Type::on( 'thing' ) )
			->variant( Integer_Type::on( 'thing' ) );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox Variant sub-schemas should preserve their own type-specific attributes in an anyOf combinator. */
	public function test_any_of_variants_preserve_nested_attributes(): void {
		$expected = array(
			'thing' => array(
				'anyOf' => array(
					array(
						'type'      => 'string',
						'maxLength' => 10,
					),
					array(
						'type'    => 'integer',
						'maximum' => 999,
					),
				),
			),
		);

		$model = Any_Of_Type::on( 'thing' )
			->variant( String_Type::on( 'thing' )->max_length( 10 ) )
			->variant( Integer_Type::on( 'thing' )->maximum( 999 ) );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox Any_Of_Type with no variants emits an empty anyOf list. */
	public function test_any_of_with_no_variants_emits_empty_list(): void {
		$expected = array(
			'thing' => array(
				'anyOf' => array(),
			),
		);

		$model = Any_Of_Type::on( 'thing' );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}
}
