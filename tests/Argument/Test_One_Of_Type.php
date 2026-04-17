<?php

declare(strict_types=1);

namespace PinkCrab\WP_Rest_Schema\Tests\Argument;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;
use PinkCrab\WP_Rest_Schema\Argument\One_Of_Type;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;

class Test_One_Of_Type extends WP_UnitTestCase {

	/** @testdox One_Of_Type should emit oneOf at the schema root, containing the variant schemas as sibling entries. */
	public function test_one_of_emits_root_level_combinator(): void {
		$expected = array(
			'thing' => array(
				'oneOf' => array(
					array( 'type' => 'string' ),
					array( 'type' => 'integer' ),
				),
			),
		);

		$model = One_Of_Type::on( 'thing' )
			->variant( String_Type::on( 'thing' ) )
			->variant( Integer_Type::on( 'thing' ) );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox One_Of_Type with zero variants should emit an empty oneOf list (caller's responsibility to add variants). */
	public function test_one_of_with_no_variants_emits_empty_list(): void {
		$expected = array(
			'thing' => array(
				'oneOf' => array(),
			),
		);

		$model = One_Of_Type::on( 'thing' );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	/** @testdox Variant sub-schemas should preserve their own type-specific attributes (minLength, minimum, etc.). */
	public function test_one_of_variants_preserve_nested_attributes(): void {
		$expected = array(
			'thing' => array(
				'oneOf' => array(
					array(
						'type'      => 'string',
						'minLength' => 3,
					),
					array(
						'type'    => 'integer',
						'minimum' => 1,
					),
				),
			),
		);

		$model = One_Of_Type::on( 'thing' )
			->variant( String_Type::on( 'thing' )->min_length( 3 ) )
			->variant( Integer_Type::on( 'thing' )->minimum( 1 ) );

		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}
}
