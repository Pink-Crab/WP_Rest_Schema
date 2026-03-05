<?php

declare(strict_types=1);

/**
 * Unit Tests for the Object Type Argument.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\WP_Rest_Schema
 * @since 0.1.0
 *
 */

namespace PinkCrab\WP_Rest_Schema\Tests\Argument;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Argument\Null_Type;
use PinkCrab\WP_Rest_Schema\Argument\Array_Type;
use PinkCrab\WP_Rest_Schema\Argument\Number_Type;
use PinkCrab\WP_Rest_Schema\Argument\Object_Type;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Argument\Boolean_Type;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;

class Test_Object_Type extends WP_UnitTestCase {

	/** @testdox When creating a string type, the argument type should be preset.  */
	public function test_sets_string_type(): void {
		$arg = Object_Type::on( 'test' );
		$this->assertEquals( 'object', $arg->get_type() );
	}

	/** @testdox It should be possible to set and get the min properties for a string argument. */
	public function test_min_properties(): void {
		$arg = Object_Type::on( 'test' );
		$arg->min_properties( 3 );
		$this->assertEquals( 3, $arg->get_min_properties() );
	}

	/** @testdox It should be possible to set and get the max properties for a string argument. */
	public function test_max_properties(): void {
		$arg = Object_Type::on( 'test' );
		$arg->max_properties( 9 );
		$this->assertEquals( 9, $arg->get_max_properties() );
	}

	/**
	 * Regular Properties.
	 */

	/** @testdox Can set a string property */
	public function test_string_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->string_property(
			'property_name',
			function( String_Type $type ): String_Type {
				$this->assertEquals( 'property_name', $type->get_name() );
				$this->assertInstanceOf( String_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_properties() );
		$this->assertArrayHasKey( 'property_name', $arg->get_properties() );

		$type = $arg->get_properties()['property_name'];
		$this->assertInstanceOf( String_Type::class, $type );
		$this->assertEquals( 'property_name', $type->get_name() );
	}

	/** @testdox Can set a number property */
	public function test_number_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->number_property(
			'property_name',
			function( Number_Type $type ): Number_Type {
				$this->assertEquals( 'property_name', $type->get_name() );
				$this->assertInstanceOf( Number_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_properties() );
		$this->assertArrayHasKey( 'property_name', $arg->get_properties() );
		$type = $arg->get_properties()['property_name'];
		$this->assertInstanceOf( Number_Type::class, $type );
		$this->assertEquals( 'property_name', $type->get_name() );
	}

	/** @testdox Can set a integer property */
	public function test_integer_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->integer_property(
			'property_name',
			function( Integer_Type $type ): Integer_Type {
				$this->assertEquals( 'property_name', $type->get_name() );
				$this->assertInstanceOf( Integer_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_properties() );
		$this->assertArrayHasKey( 'property_name', $arg->get_properties() );
		$type = $arg->get_properties()['property_name'];
		$this->assertInstanceOf( Integer_Type::class, $type );
		$this->assertEquals( 'property_name', $type->get_name() );
	}

	/** @testdox Can set a null property */
	public function test_null_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->null_property(
			'property_name',
			function( Null_Type $type ): Null_Type {
				$this->assertEquals( 'property_name', $type->get_name() );
				$this->assertInstanceOf( Null_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_properties() );
		$this->assertArrayHasKey( 'property_name', $arg->get_properties() );
		$type = $arg->get_properties()['property_name'];
		$this->assertInstanceOf( Null_Type::class, $type );
		$this->assertEquals( 'property_name', $type->get_name() );
	}

	/** @testdox Can set a boolean property */
	public function test_boolean_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->boolean_property(
			'property_name',
			function( Boolean_Type $type ): Boolean_Type {
				$this->assertEquals( 'property_name', $type->get_name() );
				$this->assertInstanceOf( Boolean_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_properties() );
		$this->assertArrayHasKey( 'property_name', $arg->get_properties() );
		$type = $arg->get_properties()['property_name'];
		$this->assertInstanceOf( Boolean_Type::class, $type );
		$this->assertEquals( 'property_name', $type->get_name() );
	}

	/** @testdox Can set a array property */
	public function test_array_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->array_property(
			'property_name',
			function( Array_Type $type ): Array_Type {
				$this->assertEquals( 'property_name', $type->get_name() );
				$this->assertInstanceOf( Array_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_properties() );
		$this->assertArrayHasKey( 'property_name', $arg->get_properties() );
		$type = $arg->get_properties()['property_name'];
		$this->assertInstanceOf( Array_Type::class, $type );
		$this->assertEquals( 'property_name', $type->get_name() );
	}

	/** @testdox Can set a object property */
	public function test_object_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->object_property(
			'property_name',
			function( Object_Type $type ): Object_Type {
				$this->assertEquals( 'property_name', $type->get_name() );
				$this->assertInstanceOf( Object_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_properties() );
		$this->assertArrayHasKey( 'property_name', $arg->get_properties() );
		$type = $arg->get_properties()['property_name'];
		$this->assertInstanceOf( Object_Type::class, $type );
		$this->assertEquals( 'property_name', $type->get_name() );
	}

	/**
	 * Additional Properties.
	 */

	/** @testdox Can set additional properties as boolean true (allow) */
	public function test_additional_properties_allowed(): void {
		$arg = Object_Type::on( 'test' );
		$arg->additional_properties( true );

		$this->assertTrue( $arg->has_additional_properties() );
		$this->assertTrue( $arg->get_additional_properties() );
	}

	/** @testdox Can set additional properties as boolean false (disallow) */
	public function test_additional_properties_disallowed(): void {
		$arg = Object_Type::on( 'test' );
		$arg->additional_properties( false );

		$this->assertTrue( $arg->has_additional_properties() );
		$this->assertFalse( $arg->get_additional_properties() );
	}

	/** @testdox Can set additional properties as a schema */
	public function test_additional_properties_schema(): void {
		$arg    = Object_Type::on( 'test' );
		$schema = String_Type::on( 'extra' );
		$arg->additional_properties_schema( $schema );

		$this->assertTrue( $arg->has_additional_properties() );
		$this->assertInstanceOf( String_Type::class, $arg->get_additional_properties() );
	}

	/** @testdox Additional properties defaults to null (not set) */
	public function test_additional_properties_default_null(): void {
		$arg = Object_Type::on( 'test' );

		$this->assertFalse( $arg->has_additional_properties() );
		$this->assertNull( $arg->get_additional_properties() );
	}

	/**
	 * Pattern Properties.
	 */

		/** @testdox Can set a string pattern property */
	public function test_string_pattern_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->string_pattern_property(
			'^\\w+$',
			function( String_Type $type ): String_Type {
				$this->assertEquals( '^\\w+$', $type->get_name() );
				$this->assertInstanceOf( String_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_pattern_properties() );
		$this->assertArrayHasKey( '^\\w+$', $arg->get_pattern_properties() );

		$type = $arg->get_pattern_properties()['^\\w+$'];
		$this->assertInstanceOf( String_Type::class, $type );
		$this->assertEquals( '^\\w+$', $type->get_name() );
	}

	/** @testdox Can set a number pattern property */
	public function test_number_pattern_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->number_pattern_property(
			'^\\w+$',
			function( Number_Type $type ): Number_Type {
				$this->assertEquals( '^\\w+$', $type->get_name() );
				$this->assertInstanceOf( Number_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_pattern_properties() );
		$this->assertArrayHasKey( '^\\w+$', $arg->get_pattern_properties() );
		$type = $arg->get_pattern_properties()['^\\w+$'];
		$this->assertInstanceOf( Number_Type::class, $type );
		$this->assertEquals( '^\\w+$', $type->get_name() );
	}

	/** @testdox Can set a integer pattern property */
	public function test_integer_pattern_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->integer_pattern_property(
			'^\\w+$',
			function( Integer_Type $type ): Integer_Type {
				$this->assertEquals( '^\\w+$', $type->get_name() );
				$this->assertInstanceOf( Integer_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_pattern_properties() );
		$this->assertArrayHasKey( '^\\w+$', $arg->get_pattern_properties() );
		$type = $arg->get_pattern_properties()['^\\w+$'];
		$this->assertInstanceOf( Integer_Type::class, $type );
		$this->assertEquals( '^\\w+$', $type->get_name() );
	}

	/** @testdox Can set a null pattern property */
	public function test_null_pattern_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->null_pattern_property(
			'^\\w+$',
			function( Null_Type $type ): Null_Type {
				$this->assertEquals( '^\\w+$', $type->get_name() );
				$this->assertInstanceOf( Null_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_pattern_properties() );
		$this->assertArrayHasKey( '^\\w+$', $arg->get_pattern_properties() );
		$type = $arg->get_pattern_properties()['^\\w+$'];
		$this->assertInstanceOf( Null_Type::class, $type );
		$this->assertEquals( '^\\w+$', $type->get_name() );
	}

	/** @testdox Can set a boolean pattern property */
	public function test_boolean_pattern_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->boolean_pattern_property(
			'^\\w+$',
			function( Boolean_Type $type ): Boolean_Type {
				$this->assertEquals( '^\\w+$', $type->get_name() );
				$this->assertInstanceOf( Boolean_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_pattern_properties() );
		$this->assertArrayHasKey( '^\\w+$', $arg->get_pattern_properties() );
		$type = $arg->get_pattern_properties()['^\\w+$'];
		$this->assertInstanceOf( Boolean_Type::class, $type );
		$this->assertEquals( '^\\w+$', $type->get_name() );
	}

	/** @testdox Can set a array pattern property */
	public function test_array_pattern_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->array_pattern_property(
			'^\\w+$',
			function( Array_Type $type ): Array_Type {
				$this->assertEquals( '^\\w+$', $type->get_name() );
				$this->assertInstanceOf( Array_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_pattern_properties() );
		$this->assertArrayHasKey( '^\\w+$', $arg->get_pattern_properties() );
		$type = $arg->get_pattern_properties()['^\\w+$'];
		$this->assertInstanceOf( Array_Type::class, $type );
		$this->assertEquals( '^\\w+$', $type->get_name() );
	}

	/** @testdox Can set a object pattern property */
	public function test_object_pattern_property(): void {
		$arg = Object_Type::on( 'test' );
		$arg->object_pattern_property(
			'^\\w+$',
			function( Object_Type $type ): Object_Type {
				$this->assertEquals( '^\\w+$', $type->get_name() );
				$this->assertInstanceOf( Object_Type::class, $type );
				return $type;
			}
		);

		$this->assertCount( 1, $arg->get_pattern_properties() );
		$this->assertArrayHasKey( '^\\w+$', $arg->get_pattern_properties() );
		$type = $arg->get_pattern_properties()['^\\w+$'];
		$this->assertInstanceOf( Object_Type::class, $type );
		$this->assertEquals( '^\\w+$', $type->get_name() );
	}
}
