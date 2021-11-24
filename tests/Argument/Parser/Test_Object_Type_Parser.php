<?php

declare(strict_types=1);

/**
 * Unit Tests for the object type parser
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

namespace PinkCrab\WP_Rest_Schema\Tests\Argument\Parser;

use PinkCrab\WP_Rest_Schema\Argument\Object_Type;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;
use PinkCrab\WP_Rest_Schema\Tests\Argument\Parser\Abstract_Parser_Testcase;

class Test_Object_Type_Parser extends Abstract_Parser_Testcase {

	public function type_class(): string {
		return Object_Type::class;
	}

	public function type_name(): string {
		return 'object';
	}

	/** @testdox It should be possible to parse any properties defined */
	public function test_can_parse_regular_properties(): void {
		$expected = array(
			'arg-name' => array(
				'type'       => 'object',
				'properties' => array(
					'foo' => array(
						'type' => 'string',
						'name' => 'foo',
					),
					'bar' => array(
						'type' => 'boolean',
						'name' => 'bar',
					),
				),
			),
		);

		$model = Object_Type::on( 'arg-name' )
			->string_property( 'foo' )
			->boolean_property( 'bar' );
		dump($model);
dump(Argument_Parser::as_array( $model ));
		$this->assertSame(
			$expected,
			Argument_Parser::as_array( $model )
		);
	}

	public function test(Type $var = null)
	{
		# code...
	}

}
