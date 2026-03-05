<?php

declare(strict_types=1);

/**
 * Unit Tests for the Null Type Argument.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\WP_Rest_Schema
 * @since 0.2.0
 */

namespace PinkCrab\WP_Rest_Schema\Tests\Argument;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Argument\Null_Type;

class Test_Null_Type extends WP_UnitTestCase {

	/** @testdox When creating a null type, the argument type should be preset. */
	public function test_sets_null_type(): void {
		$arg = Null_Type::on( 'test' );
		$this->assertEquals( 'null', $arg->get_type() );
	}

	/** @testdox It should be possible to create a null type using the field() alias. */
	public function test_field_alias(): void {
		$arg = Null_Type::field( 'test' );
		$this->assertEquals( 'null', $arg->get_type() );
		$this->assertEquals( 'test', $arg->get_key() );
	}
}
