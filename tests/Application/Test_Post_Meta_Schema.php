<?php

declare(strict_types=1);

/**
 * Unit Tests for the String Type Argument.
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

namespace PinkCrab\WP_Rest_Schema\Tests\Application;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Argument\Array_Type;
use PinkCrab\WP_Rest_Schema\Tests\HTTP_TestCase;
use PinkCrab\WP_Rest_Schema\Argument\Object_Type;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;

class Test_Post_Meta_Schema extends HTTP_TestCase {

	/** @testdox It should be possible to create a post with a custom meta field. Defined with an array with min, max, type and unique properties. */
	public function test_post_array(): void {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		$user    = wp_set_current_user( $user_id );
		$schema  = Argument_Parser::for_meta_data(
			Array_Type::on( 'my_meta' )
				->string_item()
				->min_items( 2 )
				->max_items( 4 )
				->required()
				->unique_items()
				->context( 'edit', 'view' )
		);
		register_meta(
			'post',
			'my_meta',
			array(
				'single'       => true,
				'type'         => 'array',
				'show_in_rest' => array(
					'schema' => $schema,
				),
			)
		);

		$this->register_routes();

		// Dispatch request,
		$dispatch = function( array $args ): \WP_REST_Response {
			return $this->dispatch_request(
				'POST',
				'/wp/v2/posts',
				array(),
				function( $request ) use ( $args ) {
					$request->set_header( 'content-type', 'application/json' );
					$request->set_body(
						json_encode(
							array(
								'title'   => 'title',
								'content' => 'content',
								'status'  => 'publish',
								'meta'    => array(
									'my_meta' => $args,
								),
							)
						)
					);
					return $request;
				}
			);
		};

		// Test fails if to few items
		$response = $dispatch( array( 'single' ) )->get_data();
		$this->assertEquals( 400, $response['data']['status'] );
		$this->assertEquals( 'rest_too_few_items', $response['code'] );

		// Test fails with too many
		$response = $dispatch( array( 'a', 'b', 'c', 'd', 'e', 'f' ) )->get_data();
		$this->assertEquals( 400, $response['data']['status'] );
		$this->assertEquals( 'rest_too_many_items', $response['code'] );

		// Test must be unique
		$response = $dispatch( array( 'a', 'b', 'a' ) )->get_data();
		$this->assertEquals( 400, $response['data']['status'] );
		$this->assertEquals( 'rest_duplicate_items', $response['code'] );

		// Test must be an array of strings.
		$response = $dispatch( array( 1, 2, 3 ) )->get_data();
		$this->assertEquals( 400, $response['data']['status'] );
		$this->assertEquals( 'rest_invalid_type', $response['code'] );

		// Test can create post
		$response = $dispatch( array( 'a', 'b', 'c' ) );
		$this->assertEquals( 201, $response->get_status() );
		$data = $response->get_data();
		$this->assertArrayHasKey( 'my_meta', $data['meta'] );
		$this->assertContains( 'a', $data['meta']['my_meta'] );
		$this->assertContains( 'b', $data['meta']['my_meta'] );
		$this->assertContains( 'c', $data['meta']['my_meta'] );
	}

	public function test_post_object(): void {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		$user    = wp_set_current_user( $user_id );
		$schema  = Argument_Parser::for_meta_data(
			Object_Type::on( 'object_meta' )
				->boolean_property( 'boolean' )
				->integer_property(
					'integer',
					function( Integer_Type $arg ): Integer_Type {
						return $arg->required()->expected( 1, 2, 4 );
					}
				)
				->string_additional_property( 'optional_string' )
		);
		register_meta(
			'post',
			'object_meta',
			array(
				'single'       => true,
				'type'         => 'object',
				'show_in_rest' => array(
					'schema' => $schema,
				),
			)
		);

		$this->register_routes();

		// Dispatch request,
		$dispatch = function( $args ): \WP_REST_Response {
			return $this->dispatch_request(
				'POST',
				'/wp/v2/posts',
				array(),
				function( $request ) use ( $args ) {
					$request->set_header( 'content-type', 'application/json' );
					$request->set_body(
						json_encode(
							array(
								'title'   => 'title',
								'content' => 'content',
								'status'  => 'publish',
								'meta'    => array(
									'object_meta' => $args,
								),
							)
						)
					);
					return $request;
				}
			);
		};

		// Test that integer is required
		$response = $dispatch( (object) array( 'boolean' => true ) )->get_data();
		$this->assertEquals( 400, $response['data']['status'] );
		$this->assertEquals( 'rest_property_required', $response['code'] );

		// Check type checks with integer type.
		$response = $dispatch(
			(object) array(
				'boolean' => true,
				'integer' => 'fff',
			)
		)->get_data();
		$this->assertEquals( 400, $response['data']['status'] );
		$this->assertEquals( 'rest_invalid_type', $response['code'] );
		$this->assertEquals( 'meta.object_meta[integer] is not of type integer.', $response['message'] );

		// Check expect integer value.
		$response = $dispatch(
			(object) array(
				'boolean' => false,
				'integer' => 9,
			)
		)->get_data();
		$this->assertEquals( 400, $response['data']['status'] );
		$this->assertEquals( 'rest_not_in_enum', $response['code'] );
		$this->assertEquals( 'meta.object_meta[integer] is not one of 1, 2, and 4.', $response['message'] );

		// Test can create post
		$response = $dispatch(
			(object) array(
				'boolean' => true,
				'integer' => 4,
			)
		);
		$this->assertEquals(201, $response->get_status());
		$data = $response->get_data();
		$this->assertArrayHasKey('object_meta', $data['meta']);
		$this->assertEquals(4, $data['meta']['object_meta']['integer']);
		$this->assertEquals(true, $data['meta']['object_meta']['boolean']);
	}

	public function test_post_string()
	{
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		$user    = wp_set_current_user( $user_id );
		$schema  = Argument_Parser::for_meta_data(
			String_Type::on('string_meta')
				->required()
				->default('missing')
				->min_length(2)
				->max_length(12)
				->context('view', 'edit', 'embed')
		);

		register_meta(
			'post',
			'string_meta',
			array(
				'single'       => true,
				'type'         => 'object',
				'show_in_rest' => array(
					'schema' => $schema,
				),
			)
		);

		$this->register_routes();

		// Dispatch request,
		$dispatch = function( $string ): \WP_REST_Response {
			return $this->dispatch_request(
				'POST',
				'/wp/v2/posts',
				array(),
				function( $request ) use ( $string ) {
					$request->set_header( 'content-type', 'application/json' );
					$request->set_body(
						json_encode(
							array(
								'title'   => 'title',
								'content' => 'content',
								'status'  => 'publish',
								'meta'    => array(
									'string_meta' => $string,
								),
							)
						)
					);
					return $request;
				}
			);
		};
		$dispatch('ggg');
		
		$dispatch = function( $string ): \WP_REST_Response {
			return $this->dispatch_request(
				'GET',
				'/wp/v2/posts/4',
				array()
			);
		};

		dump($schema, $dispatch('ggg')->get_data()/* , $dispatch('ggg')->get_data() */);

	}
}
