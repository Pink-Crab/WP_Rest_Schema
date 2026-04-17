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

		// Regular properties.
		$items = $this->parse_properties( $argument->get_properties() );
		if ( ! empty( $items ) ) {
			$relationship             = $argument->get_relationship();
			$attributes['properties'] = 'allOf' === $relationship
				? $items
				: array( $relationship => $items );
		}

		// Additional properties.
		if ( $argument->has_additional_properties() ) {
			$additional = $argument->get_additional_properties();
			if ( is_bool( $additional ) ) {
				$attributes['additionalProperties'] = $additional;
			} elseif ( $additional instanceof Argument ) {
				$attributes['additionalProperties'] = Argument_Parser::as_list( $additional );
			}
		}

		// Pattern properties.
		$pattern_props = $this->parse_properties( $argument->get_pattern_properties() );
		if ( ! empty( $pattern_props ) ) {
			$attributes['patternProperties'] = $pattern_props;
		}

		// Min/max properties.
		if ( ! is_null( $argument->get_min_properties() ) ) {
			$attributes['minProperties'] = $argument->get_min_properties();
		}

		if ( ! is_null( $argument->get_max_properties() ) ) {
			$attributes['maxProperties'] = $argument->get_max_properties();
		}

		// Parent-level required property names (draft-4 style array).
		$required_names = $argument->get_required_properties();
		if ( ! empty( $required_names ) ) {
			$attributes['required'] = $required_names;
		}

		return $attributes;
	}

	/**
	 * Parses an array of Argument properties into their array representation.
	 *
	 * @param array<string, Argument> $properties
	 * @return array<string, mixed>
	 */
	protected function parse_properties( array $properties ): array {
		if ( empty( $properties ) ) {
			return array();
		}

		$parsed = array();
		foreach ( $properties as $key => $value ) {
			$parsed[ $key ] = Argument_Parser::as_single( $value );
		}

		return $parsed;
	}
}
