<?php

declare(strict_types=1);

/**
 * Unit Tests for the Union Type Argument.
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
 * @since 0.2.0
 *
 */

namespace PinkCrab\WP_Rest_Schema\Tests\Argument;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Argument\Union_Type;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;

class Test_Union_Type extends WP_UnitTestCase {

	/**@testdox It should be possible to create a union type with a oneOf relationship */
	public function test_union_type(): void {
		$union = Union_Type::one_of(
			String_Type::on( 'string' ),
			Integer_Type::on( 'integer' ),
		);

		$this->assertEquals( 'oneOf', $union->get_union_type() );
	}

	/**@testdox It should be possible to create a union type with a anyOf relationship */
	public function test_union_type_any_of(): void {
		$union = Union_Type::any_of(
			String_Type::on( 'string' ),
			Integer_Type::on( 'integer' ),
		);

		$this->assertEquals( 'anyOf', $union->get_union_type() );
	}

	/** @testdox It should be possible to add and get the options (Sub types) */
	public function test_union_type_options(): void {
		$union = Union_Type::any_of( String_Type::on( 'string' ) )
			->option( Integer_Type::on( 'integer' ) );

		$this->assertCount( 2, $union->get_options() );
		$this->assertInstanceOf( String_Type::class, $union->get_options()[0] );
		$this->assertInstanceOf( Integer_Type::class, $union->get_options()[1] );
	}

	/**
	 * @testdox Attempting to call methods that are not valid for union type should result in an exception.
	 * @dataProvider unionInvalidMethods
	 * @param string $method
	 * @param array $args
	 * @return void
	 */
	public function test_union_type_invalid_methods( string $method, array $args ): void {
		$this->expectException( \Exception::class );
		$union = Union_Type::any_of( String_Type::on( 'string' ) );
		$union->$method( ...$args );
	}

	/**
	 * Data provider for invalid methods.
	 *
	 * @return array
	 */
	public function unionInvalidMethods(): array {
		return array(
			'get_validation ' => array( 'get_validation', array() ),
			'validation ' => array( 'validation', array( 'is_string' ) ),
			'get_sanitization ' => array( 'get_sanitization', array() ),
			'sanitization ' => array( 'sanitization', array( 'sanitize_text_field' ) ),
			'get_default ' => array( 'get_default', array() ),
			'has_default ' => array( 'has_default', array() ),
			'default ' => array( 'default', array( 'test' ) ),
			'is_required ' => array( 'is_required', array() ),
			'get_required ' => array( 'get_required', array() ),
			'required ' => array( 'required', array( true ) ),
			'get_description ' => array( 'get_description', array() ),
			'description ' => array( 'description', array( 'test' ) ),
			'get_format ' => array( 'get_format', array() ),
			'format ' => array( 'format', array( 'test' ) ),
			'get_attributes ' => array( 'get_attributes', array() ),
			'set_attributes ' => array( 'set_attributes', array( array() ) ),
			'add_attribute ' => array( 'add_attribute', array( 'foo', 'bar' ) ),
			'get_attribute ' => array( 'get_attribute', array( 'foo' ) ),
			'get_expected ' => array( 'get_expected', array() ),
			'expected ' => array( 'expected', array( 'test' ) ),
			'get_name ' => array( 'get_name', array() ),
			'name ' => array( 'name', array( 'test' ) ),
			'get_context ' => array( 'get_context', array() ),
			'context ' => array( 'context', array( 'test' ) ),
		);
	}
}
