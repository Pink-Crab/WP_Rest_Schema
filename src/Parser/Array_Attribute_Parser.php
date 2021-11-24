<?php

declare(strict_types=1);

/**
 * Array Type parser
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Parser;

use PinkCrab\WP_Rest_Schema\Argument\Array_Type;
use PinkCrab\WP_Rest_Schema\Parser\Abstract_Parser;


class Array_Attribute_Parser extends Abstract_Parser {

	/**
	 * Parses the custom attributes for a type.
	 *
	 * @return array<string, int|float|bool|mixed[]>
	 */
	public function parse_attributes(): array {
		// Bail if not a Array Argument.
		if ( ! is_a( $this->argument, Array_Type::class ) ) {
			return array();
		}

		/** @var Array_Type $argument */
		$argument = $this->argument;

		$attributes = array();

		// Map items.
		$items = $this->parse_array_items( $argument );
		if ( ! empty( $items ) ) {
			// Based on relationship type.
			$relationship        = $argument->get_relationship();
			$attributes['items'] = 'allOf' === $relationship
				? $items
				: array( $relationship => $items );
		}

		// Min items
		if ( ! is_null( $argument->get_min_items() ) ) {
			$attributes['minItems'] = $argument->get_min_items();
		}

		// Max items
		if ( ! is_null( $argument->get_max_items() ) ) {
			$attributes['maxItems'] = $argument->get_max_items();
		}

		// Unique items
		if ( ! is_null( $argument->get_unique_items() ) ) {
			$attributes['uniqueItems'] = $argument->get_unique_items();
		}

		return $attributes;
	}

	/**
	 * Parses the arrays items
	 *
	 * @param Array_Type $argument
	 * @return array<int, mixed>
	 */
	protected function parse_array_items( Array_Type $argument ) : array {
		$items = array();

		if ( ! $argument->has_items() ) {
			return $items;
		}

		// If we only have 1 item, return as a simple array.
		if ( $argument->item_count() === 1 ) {
			$items = Argument_Parser::as_list( array_values( $argument->get_items() )[0] ); // @phpstan-ignore-line, already checked if array empty.
		} else {
			foreach ( $argument->get_items() ?? array() as $key => $value ) {
				$items[] = Argument_Parser::as_list( $value );
			}
		}

		return $items;
	}
}
