<?php

declare(strict_types=1);

/**
 * Unit Tests for the Boolean Type Argument.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\WP_Rest_Schema
 * @since 0.2.0
 */

namespace PinkCrab\WP_Rest_Schema\Tests\Argument;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Argument\Boolean_Type;

class Test_Boolean_Type extends WP_UnitTestCase {

	/** @testdox When creating a boolean type, the argument type should be preset. */
	public function test_sets_boolean_type(): void {
		$arg = Boolean_Type::on( 'test' );
		$this->assertEquals( 'boolean', $arg->get_type() );
	}

	/** @testdox It should be possible to create a boolean type using the field() alias. */
	public function test_field_alias(): void {
		$arg = Boolean_Type::field( 'test' );
		$this->assertEquals( 'boolean', $arg->get_type() );
		$this->assertEquals( 'test', $arg->get_key() );
	}
}
