<?php

declare(strict_types=1);

/**
 * Parses an argument into either array or JSON representations
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Parser;

use PinkCrab\WP_Rest_Schema\Argument\Argument;
use PinkCrab\WP_Rest_Schema\Argument\Number_Type;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;
use PinkCrab\WP_Rest_Schema\Parser\Array_Attribute_Parser;
use PinkCrab\WP_Rest_Schema\Parser\Object_Attribute_Parser;

class Argument_Parser {

	/**
	 * The Argument to be parsed
	 *
	 * @var Argument
	 */
	protected $argument;

	public function __construct( Argument $argument ) {
		$this->argument = $argument;
	}

	/**
	 * Static helpers
	 */

	/**
	 * Static constructor with array output with argument key as array keys.
	 *
	 * @param \PinkCrab\WP_Rest_Schema\Argument\Argument $argument
	 * @return array<string, mixed>
	 */
	public static function as_array( Argument $argument ): array {
		return ( new self( $argument ) )->parse_as_indexed_array();
	}

	/**
	 * Static constructor with array output without keys, just a list.
	 *
	 * @param \PinkCrab\WP_Rest_Schema\Argument\Argument $argument
	 * @return mixed[]
	 */
	public static function as_list( Argument $argument ): array {
		return ( new self( $argument ) )->parse_as_list();
	}

	/**
	 * Static constructor with a single argument output.
	 *
	 * @param \PinkCrab\WP_Rest_Schema\Argument\Argument $argument
	 * @return mixed[]
	 */
	public static function as_single( Argument $argument ): array {
		$parsed = ( new self( $argument ) )->parse_as_indexed_array();
		if ( count( $parsed ) < 1 ) {
			return array();
		}
		/** @var array<string, mixed> $first */
		$first = reset( $parsed );
		return $first;
	}

	/**
	 * Lazy static method for rendering schema for a meta data.
	 * Wrapper for self::as_list()
	 *
	 * @param \PinkCrab\WP_Rest_Schema\Argument\Argument $argument
	 * @return mixed[]
	 */
	public static function for_meta_data( Argument $argument ): array {
		return self::as_list( $argument );
	}

	/**
	 * Static constructor for register_rest_route() args.
	 * Accepts multiple Arguments and returns a merged keyed args array.
	 *
	 * @param \PinkCrab\WP_Rest_Schema\Argument\Argument ...$arguments
	 * @return array<string, mixed>
	 */
	public static function for_route( Argument ...$arguments ): array {
		$args = array();
		foreach ( $arguments as $argument ) {
			$args = array_merge( $args, self::as_array( $argument ) );
		}
		return $args;
	}

	/**
	 * Output types.
	 */

	/**
	 * Returns the current argument as an array with the argument key as array key.
	 *
	 * @return array<string, mixed>
	 */
	public function parse_as_indexed_array(): array {
		return array(
			$this->argument->get_key() => array_merge(
				$this->shared_attributes(),
				$this->get_type_attributes()
			),
		);
	}

	/**
	 * Returns the current as an array without a key.
	 *
	 * @return mixed[]
	 */
	public function parse_as_list(): array {
		return array_merge(
			$this->shared_attributes(),
			$this->get_type_attributes()
		);
	}

	/**
	 * Sets all populated shared attributes to an array.
	 *
	 * @param array<string, mixed> $attributes
	 * @return array<string, mixed>
	 */
	public function shared_attributes( array $attributes = array() ): array {
		if ( $this->argument->get_validation() ) {
			$attributes['validate_callback'] = $this->argument->get_validation();
		}

		if ( $this->argument->get_sanitization() ) {
			$attributes['sanitize_callback'] = $this->argument->get_sanitization();
		}

		if ( ! is_null( $this->argument->get_type() ) ) {
			$attributes['type'] = $this->argument->get_type();
		}

		if ( ! is_null( $this->argument->get_required() ) ) {
			$attributes['required'] = $this->argument->get_required();
		}

		if ( '' !== $this->argument->get_description() ) {
			$attributes['description'] = $this->argument->get_description();
		}

		if ( $this->argument->has_default() ) {
			$attributes['default'] = $this->argument->get_default();
		}

		if ( ! is_null( $this->argument->get_format() ) ) {
			$attributes['format'] = $this->argument->get_format();
		}

		if ( is_array( $this->argument->get_expected() ) && ! empty( $this->argument->get_expected() ) ) {
			$attributes['enum'] = $this->argument->get_expected();
		}

		if ( ! empty( $this->argument->get_context() ) ) {
			$attributes['context'] = $this->argument->get_context();
		}

		if ( ! is_null( $this->argument->get_readonly() ) ) {
			$attributes['readonly'] = $this->argument->get_readonly();
		}

		if ( ! is_null( $this->argument->get_title() ) ) {
			$attributes['title'] = $this->argument->get_title();
		}

		if ( ! is_null( $this->argument->get_arg_options() ) ) {
			$attributes['arg_options'] = $this->argument->get_arg_options();
		}

		return $attributes;
	}

	/**
	 *
	 * Per Type Parsers
	 *
	 */

	/**
	 * Returns the current attributes specific attributes.
	 *
	 * @return array<string, mixed>
	 */
	protected function get_type_attributes(): array {
		// Schema-root combinators (One_Of_Type, Any_Of_Type) have no primitive
		// `type` and are dispatched by class rather than by the type switch.
		if ( is_a( $this->argument, \PinkCrab\WP_Rest_Schema\Argument\Combinator_Type::class ) ) {
			return Combinator_Attribute_Parser::parse( $this->argument );
		}

		switch ( $this->argument->get_type() ) {
			case Argument::TYPE_STRING:
				return String_Attribute_Parser::parse( $this->argument );

			case Argument::TYPE_NUMBER:
			case Argument::TYPE_INTEGER:
				return $this->numeric_attributes();

			case Argument::TYPE_ARRAY:
				return Array_Attribute_Parser::parse( $this->argument );

			case Argument::TYPE_OBJECT:
				return Object_Attribute_Parser::parse( $this->argument );

			default:
				return array();
		}
	}



	/**
	 * Populate number and integer args.
	 *
	 * @return array<string, int|float|bool|mixed[]>
	 */
	public function numeric_attributes(): array {

		// Bail if not a String Argument.
		if ( ! is_a( $this->argument, Number_Type::class )
		&& ! is_a( $this->argument, Integer_Type::class )
		) {
			return array();
		}

		/** @var Number_Type|Integer_Type $argument */
		$argument = $this->argument;

		$attributes = array();
		if ( ! is_null( $argument->get_multiple_of() ) ) {
			$attributes['multipleOf'] = $argument->get_multiple_of();
		}

		if ( ! is_null( $argument->get_minimum() ) ) {
			$attributes['minimum'] = $argument->get_minimum();
			if ( ! is_null( $argument->get_exclusive_minimum() ) ) {
				$attributes['exclusiveMinimum'] = $argument->get_exclusive_minimum();
			}
		}

		if ( ! is_null( $argument->get_maximum() ) ) {
			$attributes['maximum'] = $argument->get_maximum();
			if ( ! is_null( $argument->get_exclusive_maximum() ) ) {
				$attributes['exclusiveMaximum'] = $argument->get_exclusive_maximum();
			}
		}

		return $attributes;
	}
}
