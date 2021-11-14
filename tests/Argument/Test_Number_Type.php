<?php

declare(strict_types=1);

/**
 * Unit Tests for the Number Type Argument.
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
use PinkCrab\WP_Rest_Schema\Argument\Number_Type;

class Test_Number_Type extends WP_UnitTestCase {

	/** @testdox When creating a string type, the argument type should be preset.  */
	public function test_sets_number_type(): void {
		$arg = Number_Type::on( 'test' );
		$this->assertEquals( 'number', $arg->get_type() );
	}

	/** @testdox It should be possible to set and get the minimum for a number argument. */
	public function test_minimum(): void {
		$arg = Number_Type::on( 'test' );
		$arg->minimum( 14.75 );
		$this->assertEquals( 14.75, $arg->get_minimum() );
	}

	/** @testdox It should be possible to set and get the maximum for a number argument. */
	public function test_maximum(): void {
		$arg = Number_Type::on( 'test' );
		$arg->maximum( 45.988 );
		$this->assertEquals( 45.988, $arg->get_maximum() );
	}

	/** @testdox It should be possible to set and get the multiple_of for a number argument. */
	public function test_multiple_of():void {
		$arg = Number_Type::on( 'test' );

		// Null if not set.
		$this->assertNull( $arg->get_multiple_of() );
		$arg->multiple_of( 0.1 );
		$this->assertEquals( 0.1, $arg->get_multiple_of() );

		$arg->multiple_of( 2 );
		$this->assertEquals( 2, $arg->get_multiple_of() );
	}

	/** @testdox It should be possible to set if the minimum value excludes the number shown (1-4 exclusive would allow 2 & 3 only) */
	public function test_minimum_exclusive():void {
		$arg = Number_Type::on( 'test' );
		$this->assertNull( $arg->get_exclusive_minimum() );

		$arg->exclusive_minimum();
		$this->assertTrue( $arg->get_exclusive_minimum() );
		$arg->exclusive_minimum( false );
		$this->assertFalse( $arg->get_exclusive_minimum() );
		$arg->exclusive_minimum( true );
		$this->assertTrue( $arg->get_exclusive_minimum() );
	}

	/** @testdox It should be possible to set if the maximum value excludes the number shown (1-4 exclusive would allow 2 & 3 only) */
	public function test_maximum_exclusive():void {
		$arg = Number_Type::on( 'test' );
		$this->assertNull( $arg->get_exclusive_maximum() );

		$arg->exclusive_maximum();
		$this->assertTrue( $arg->get_exclusive_maximum() );
		$arg->exclusive_maximum( false );
		$this->assertFalse( $arg->get_exclusive_maximum() );
		$arg->exclusive_maximum( true );
		$this->assertTrue( $arg->get_exclusive_maximum() );
	}
}
