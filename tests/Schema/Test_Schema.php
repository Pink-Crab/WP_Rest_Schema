<?php

declare(strict_types=1);

/**
 * Unit Tests for the Schema builder.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\WP_Rest_Schema
 * @since 0.2.0
 */

namespace PinkCrab\WP_Rest_Schema\Tests\Schema;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Schema;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;
use PinkCrab\WP_Rest_Schema\Argument\Object_Type;

class Test_Schema extends WP_UnitTestCase {

	/** @testdox It should be possible to create a basic schema with just a title. */
	public function test_basic_schema(): void {
		$schema = Schema::on( 'post' )->to_array();

		$this->assertArrayHasKey( '$schema', $schema );
		$this->assertEquals( 'http://json-schema.org/draft-04/schema#', $schema['$schema'] );
		$this->assertEquals( 'post', $schema['title'] );
		$this->assertEquals( 'object', $schema['type'] );
	}

	/** @testdox It should be possible to set a description on the schema. */
	public function test_schema_with_description(): void {
		$schema = Schema::on( 'post' )
			->description( 'A blog post.' )
			->to_array();

		$this->assertEquals( 'A blog post.', $schema['description'] );
	}

	/** @testdox Description should not be included if empty. */
	public function test_schema_without_description(): void {
		$schema = Schema::on( 'post' )->to_array();

		$this->assertArrayNotHasKey( 'description', $schema );
	}

	/** @testdox It should be possible to add properties to the schema. */
	public function test_schema_with_properties(): void {
		$schema = Schema::on( 'post' )
			->integer_property(
				'id',
				function( Integer_Type $id ): Integer_Type {
					return $id->readonly()->description( 'Unique identifier.' )->context( 'view', 'edit', 'embed' );
				}
			)
			->string_property( 'title' )
			->to_array();

		$this->assertArrayHasKey( 'properties', $schema );
		$this->assertArrayHasKey( 'id', $schema['properties'] );
		$this->assertArrayHasKey( 'title', $schema['properties'] );

		$this->assertEquals( 'integer', $schema['properties']['id']['type'] );
		$this->assertTrue( $schema['properties']['id']['readonly'] );
		$this->assertEquals( 'Unique identifier.', $schema['properties']['id']['description'] );
	}

	/** @testdox It should be possible to use the field() alias. */
	public function test_field_alias(): void {
		$schema = Schema::field( 'post' )->to_array();

		$this->assertEquals( 'post', $schema['title'] );
	}

	/** @testdox It should be possible to get the title. */
	public function test_get_title(): void {
		$schema = Schema::on( 'post' );

		$this->assertEquals( 'post', $schema->get_title() );
	}

	/** @testdox It should be possible to get the description. */
	public function test_get_description(): void {
		$schema = Schema::on( 'post' )->description( 'A post.' );

		$this->assertEquals( 'A post.', $schema->get_description() );
	}

	/** @testdox It should be possible to access the internal Object_Type. */
	public function test_get_object(): void {
		$schema = Schema::on( 'post' );

		$this->assertInstanceOf( Object_Type::class, $schema->get_object() );
	}

	/** @testdox It should be possible to set additional properties as boolean. */
	public function test_additional_properties_boolean(): void {
		$schema = Schema::on( 'post' )
			->additional_properties( false )
			->to_array();

		$this->assertArrayHasKey( 'additionalProperties', $schema );
		$this->assertFalse( $schema['additionalProperties'] );
	}

	/** @testdox It should be possible to set additional properties as a schema. */
	public function test_additional_properties_schema(): void {
		$schema = Schema::on( 'post' )
			->additional_properties_schema( String_Type::on( 'extra' ) )
			->to_array();

		$this->assertArrayHasKey( 'additionalProperties', $schema );
		$this->assertEquals( 'string', $schema['additionalProperties']['type'] );
	}

	/** @testdox It should produce a complete schema matching WP get_item_schema() format. */
	public function test_complete_schema(): void {
		$schema = Schema::on( 'post' )
			->description( 'A blog post object.' )
			->integer_property(
				'id',
				function( Integer_Type $id ): Integer_Type {
					return $id->readonly()
						->description( 'Unique identifier.' )
						->context( 'view', 'edit', 'embed' );
				}
			)
			->string_property(
				'title',
				function( String_Type $t ): String_Type {
					return $t->required()
						->description( 'The post title.' )
						->context( 'view', 'edit' );
				}
			)
			->string_property(
				'status',
				function( String_Type $s ): String_Type {
					return $s->expected( 'publish', 'draft', 'pending' )
						->context( 'view', 'edit' );
				}
			)
			->additional_properties( false )
			->to_array();

		$this->assertEquals( 'http://json-schema.org/draft-04/schema#', $schema['$schema'] );
		$this->assertEquals( 'post', $schema['title'] );
		$this->assertEquals( 'object', $schema['type'] );
		$this->assertEquals( 'A blog post object.', $schema['description'] );
		$this->assertFalse( $schema['additionalProperties'] );

		$this->assertCount( 3, $schema['properties'] );
		$this->assertTrue( $schema['properties']['id']['readonly'] );
		$this->assertTrue( $schema['properties']['title']['required'] );
		$this->assertEquals( array( 'publish', 'draft', 'pending' ), $schema['properties']['status']['enum'] );
	}
}
