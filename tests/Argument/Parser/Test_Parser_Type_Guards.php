<?php

declare(strict_types=1);

/**
 * Exercises the "bail if wrong type" defensive guards on each attribute
 * parser. Each parser returns an empty array when handed an argument that
 * doesn't match its expected type.
 *
 * @package PinkCrab\WP_Rest_Schema
 * @since 1.0.0
 */

namespace PinkCrab\WP_Rest_Schema\Tests\Argument\Parser;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;
use PinkCrab\WP_Rest_Schema\Parser\Array_Attribute_Parser;
use PinkCrab\WP_Rest_Schema\Parser\Combinator_Attribute_Parser;
use PinkCrab\WP_Rest_Schema\Parser\Object_Attribute_Parser;
use PinkCrab\WP_Rest_Schema\Parser\String_Attribute_Parser;

class Test_Parser_Type_Guards extends WP_UnitTestCase {

	/** @testdox String_Attribute_Parser returns an empty array when given a non-String argument. */
	public function test_string_parser_guard(): void {
		$this->assertSame( array(), String_Attribute_Parser::parse( Integer_Type::on( 'x' ) ) );
	}

	/** @testdox Array_Attribute_Parser returns an empty array when given a non-Array argument. */
	public function test_array_parser_guard(): void {
		$this->assertSame( array(), Array_Attribute_Parser::parse( String_Type::on( 'x' ) ) );
	}

	/** @testdox Object_Attribute_Parser returns an empty array when given a non-Object argument. */
	public function test_object_parser_guard(): void {
		$this->assertSame( array(), Object_Attribute_Parser::parse( String_Type::on( 'x' ) ) );
	}

	/** @testdox Combinator_Attribute_Parser returns an empty array when given a non-Combinator argument. */
	public function test_combinator_parser_guard(): void {
		$this->assertSame( array(), Combinator_Attribute_Parser::parse( String_Type::on( 'x' ) ) );
	}

	/** @testdox Argument_Parser::numeric_attributes() returns an empty array for non-numeric types. */
	public function test_numeric_attributes_guard(): void {
		$parser = new Argument_Parser( String_Type::on( 'x' ) );
		$this->assertSame( array(), $parser->numeric_attributes() );
	}
}
