<?php
/**
 * Plugin Name: PinkCrab WP Rest Schema
 * Plugin URI:
 *
 * Description: Adds schema support to the WP Rest API.
 * Version: 0.1.0
 */

use PhpParser\Node\UnionType;
use PinkCrab\WP_Rest_Schema\Argument\Array_Type;
use PinkCrab\WP_Rest_Schema\Argument\Union_Type;
use PinkCrab\WP_Rest_Schema\Argument\Object_Type;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Argument\Boolean_Type;

return;
add_action(
	'init',
	function() {
		require_once __DIR__ . '/vendor/autoload.php';

		// $union = Union_Type::any_of(
		// 	String_Type::on( 'string' )
		// 			->min_length( 4 )
		// 			->max_length( 42 )
		// 			->format( String_Type::FORMAT_EMAIL ),
		// 	Boolean_Type::on( 'boolean' )
		// 		->default( true )->validation(
		// 			function( $param, $request, $key ) {
		// 				return is_bool( $param );
		// 			}
		// 		)
		// );
		// $up    = new PinkCrab\WP_Rest_Schema\Parser\Argument_Parser( $union );
		// dump( $union, $up->parse_as_indexed_array(), $union->get_key() );

		// $anyOf = array(
		// 	'anyOf' => array(
		// 		array(
		// 			'type'   => 'string',
		// 			'format' => 'email',
		// 		),
		// 		array(
		// 			'type'      => 'boolean',
		// 			'minLength' => 4,
		// 			'format'    => 'uuid',
		// 		),
		// 	),
		// );

		// dump(
		// 	array(
		// 		'manual' => rest_validate_value_from_schema(
		// 			false,
		// 			$anyOf,
		// 		),
		// 		'def'    => rest_validate_value_from_schema(
		// 			false,
		// 			$up->parse_as_list(),
		// 		),
		// 	)
		// );
		// $string = PinkCrab\WP_Rest_Schema\Argument\String_Type::on( 'string' )
		// 	->min_length( 4 )
		// 	->max_length( 42 );

		// $parser = new PinkCrab\WP_Rest_Schema\Parser\Argument_Parser( $string );
		// dump( $string, $parser->as_single( $string ), $parser->for_meta_data( $string ) );

		$object = Object_Type::on( 'objectyu' )
		->boolean_additional_property( 'rbar' )
		->string_property( 'rfoo' )
		->string_additional_property(
			'rbaz',
			function( String_Type $e ): String_Type {
				return $e
				->min_length( 14 )
				->max_length( 42 )
				->format( String_Type::FORMAT_EMAIL );
			}
		)
		// ->union_property(
		// 	'unions',
		// 	function( $e ) {
		// 		$e->option(
		// 			Boolean_Type::on( 'boolean' )
		// 					->default( true )->validation(
		// 						function( $param, $request, $key ) {
		// 							return is_bool( $param );
		// 						}
		// 					),
		// 		);
		// 		$e->option(
		// 			String_Type::on( 'string' )
		// 					->min_length( 4 )
		// 					->max_length( 42 )
		// 					->format( String_Type::FORMAT_EMAIL )
		// 		);
		// 		$e->option(
		// 			Array_Type::on( 'array' )
		// 					->any_of()
		// 					->item(
		// 						String_Type::on( 'string' )
		// 								->min_length( 4 )
		// 								->max_length( 42 )
		// 								->format( String_Type::FORMAT_EMAIL )
		// 					)
		// 					->integer_item(
		// 						fn( $e) => $e->minimum( 4 )
		// 						->maximum( 42 )
		// 					)
		// 		);
		// 		return $e;
		// 	}
		// )
		->string_property( 'foo', fn( String_Type $e) => $e->required( false ) )->string_property( 'bar' );
		$foo         = new \stdClass();
		$foo->unions = true;
		$foo->rbaz   = 'test';
		// $foo->unions = 'me@g.com';
		$e = array(
			'type'       => 'object',
			'properties' => array(
				'foo'    => array(
					'type' => 'string',
					'name' => 'foo',
				),
				'bar'    => array(
					'type' => 'string',
					'name' => 'bar',
				),
				'unions' => array(
					'anyOf' => array(
						array(
							'type' => 'boolean',
						),
						array(
							'type'   => 'string',
							'format' => 'email',
						),
					),
					'name'  => 'unions',
				),
			),
		);
		// dump( $e, rest_validate_value_from_schema( $foo, $e ) );

		// dump( $foo, $object, ( new PinkCrab\WP_Rest_Schema\Parser\Argument_Parser( $object ) )->parse_as_list() );
		// dump( $object, PinkCrab\WP_Rest_Schema\Parser\Argument_Parser::as_array( $object ) );
		dump(
			$foo,
			( new PinkCrab\WP_Rest_Schema\Parser\Argument_Parser( $object ) )->for_meta_data( $object ),
		);
		dump(
			rest_validate_value_from_schema(
				$foo,
				( new PinkCrab\WP_Rest_Schema\Parser\Argument_Parser( $object ) )->parse_as_list()
			)
		);
	}
);
