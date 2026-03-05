<?php

declare(strict_types=1);

/**
 * Unit Tests for the Argument_Parser::for_route() helper.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\WP_Rest_Schema
 * @since 0.2.0
 */

namespace PinkCrab\WP_Rest_Schema\Tests\Argument\Parser;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;

class Test_For_Route extends WP_UnitTestCase {

	/** @testdox It should be possible to generate args for register_rest_route from multiple arguments. */
	public function test_for_route_multiple_args(): void {
		$args = Argument_Parser::for_route(
			String_Type::on( 'search' )->required()->description( 'Search term' ),
			Integer_Type::on( 'page' )->minimum( 1 )->default( 1 )
		);

		$this->assertArrayHasKey( 'search', $args );
		$this->assertArrayHasKey( 'page', $args );

		$this->assertEquals( 'string', $args['search']['type'] );
		$this->assertTrue( $args['search']['required'] );
		$this->assertEquals( 'Search term', $args['search']['description'] );

		$this->assertEquals( 'integer', $args['page']['type'] );
		$this->assertEquals( 1, $args['page']['minimum'] );
		$this->assertEquals( 1, $args['page']['default'] );
	}

	/** @testdox It should be possible to use for_route with a single argument. */
	public function test_for_route_single_arg(): void {
		$args = Argument_Parser::for_route(
			String_Type::on( 'filter' )->expected( 'active', 'inactive' )
		);

		$this->assertArrayHasKey( 'filter', $args );
		$this->assertEquals( array( 'active', 'inactive' ), $args['filter']['enum'] );
	}

	/** @testdox for_route with no arguments should return empty array. */
	public function test_for_route_no_args(): void {
		$args = Argument_Parser::for_route();

		$this->assertEmpty( $args );
	}
}
