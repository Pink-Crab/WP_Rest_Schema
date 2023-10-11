<?php

declare(strict_types=1);

/**
 * Object type parser
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Parser;

use PinkCrab\WP_Rest_Schema\Argument\Argument;
use PinkCrab\WP_Rest_Schema\Argument\Union_Type;
use PinkCrab\WP_Rest_Schema\Argument\Object_Type;
use PinkCrab\WP_Rest_Schema\Parser\Abstract_Parser;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;


class Object_Attribute_Parser extends Abstract_Parser {

	/**
	 * Parses the custom attributes for a type.
	 *
	 * @return array<string, int|float|bool|mixed[]>
	 */
	public function parse_attributes(): array {
		// Bail if not a Object Argument.
		if ( ! is_a( $this->argument, Object_Type::class ) ) {
			return array();
		}

		/** @var Object_Type $argument */
		$argument = $this->argument;

		$attributes = array();

		// Regular properties
		$items = $this->parse_properties( $argument );
		if ( ! empty( $items ) ) {
			// Based on relationship type.
			$relationship             = $argument->get_relationship();
			$attributes['properties'] = 'allOf' === $relationship
				? $items
				: array( $relationship => $items );
		}

		// Additional properties.
		if ( $argument->has_additional_properties() ) {
			$attributes['additionalProperties'] = array_reduce(
				$argument->get_additional_properties(),
				function( array $carry, Argument $argument ): array {
					$props = Argument_Parser::as_single( $argument );
					// Recursively merge with existing.
					foreach ( $props as $key => $value ) {
						if ( ! isset( $carry[ $key ] ) ) {
							$carry[ $key ][] = $value;
						} else {
							$carry[ $key ] = array_merge( $carry[ $key ], array( $value ) );
						}
					}
					return $carry;
				},
				array()
			);
		} else {
			$attributes['additionalProperties'] = false;
		}

		return $attributes;
	}

	/**
	 * Parses the arrays items
	 *
	 * @param Object_Type $argument
	 * @return array<int, mixed>
	 */
	protected function parse_properties( Object_Type $argument ) : array {
		$properties = array();

		// $properties =

		if ( empty( $argument->get_properties() ) ) {
			return $properties;
		}

		// If we only have 1 item, return as a simple array.
		if ( count( $argument->get_properties() ) === 1 ) {
			$properties = Argument_Parser::as_single( array_values( $argument->get_properties() )[0] );
		} else {
			foreach ( $argument->get_properties() as $key => $value ) {
				$properties[ $key ] = is_a( $value, Union_Type::class )
					? $this->parse_union_property( $key, $value )
					: Argument_Parser::as_single( $value );
			}
		}

		return $properties;
	}

	/**
	 * Parses an union property
	 *
	 * @param string $property
	 * @param Union_Type $argument
	 * @return array<string, mixed>
	 */
	protected function parse_union_property( string $property, Union_Type $argument ) : array {
		// If we only have a single options, parse as a single.
		if ( count( $argument->get_options() ) === 1 ) {
			return Argument_Parser::as_single( array_values( $argument->get_options() )[0] );
		}

		return array(
			'name'  => $property,
			'anyOf' => array_map(
				function( $option ): array {
					return Argument_Parser::as_single( $option );
				},
				$argument->get_options()
			),
		);
	}
}
