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

use PinkCrab\WP_Rest_Schema\Argument\Object_Type;
use PinkCrab\WP_Rest_Schema\Parser\Abstract_Parser;


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
				$properties[ $key ] = Argument_Parser::as_single( $value );
			}
		}

		return $properties;
	}
}
