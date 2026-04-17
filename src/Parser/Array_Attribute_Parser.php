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
	 * Parses the array's items.
	 *
	 * WP treats `items` as a single schema applied to every element. JSON
	 * Schema tuple-form (`items: [schemaA, schemaB]`) is not honoured.
	 *
	 * - If no combinator is set (default `allOf`) and multiple item schemas
	 *   have been added, the LAST item wins and `items` is emitted as a
	 *   single schema.
	 * - If a combinator (`oneOf` / `anyOf`) is set, all added item schemas
	 *   are emitted as a list under that key — producing valid WP schema
	 *   (`items: { oneOf: [schemaA, schemaB] }`).
	 *
	 * @param Array_Type $argument
	 * @return array<int|string, mixed>
	 */
	protected function parse_array_items( Array_Type $argument ): array {
		if ( ! $argument->has_items() ) {
			return array();
		}

		/** @var array<int, \PinkCrab\WP_Rest_Schema\Argument\Argument> $raw_items */
		$raw_items    = array_values( $argument->get_items() ?? array() );
		$relationship = $argument->get_relationship();

		// Combinator set (oneOf / anyOf) — keep the full list so it can be
		// wrapped under the combinator key by the caller.
		if ( 'allOf' !== $relationship ) {
			$items = array();
			foreach ( $raw_items as $item ) {
				$items[] = Argument_Parser::as_list( $item );
			}
			return $items;
		}

		// No combinator — WP only honours a single items schema. Last wins.
		$last_item = end( $raw_items );
		if ( false === $last_item ) {
			return array();
		}
		return Argument_Parser::as_list( $last_item );
	}
}
