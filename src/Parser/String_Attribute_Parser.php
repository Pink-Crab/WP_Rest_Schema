<?php

declare(strict_types=1);

/**
 * String attribute parser.
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Parser;

use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Parser\Abstract_Parser;

class String_Attribute_Parser extends Abstract_Parser {

	/**
	 * Parses the custom attributes for a type.
	 *
	 * @return array<string, int|float|bool|mixed[]|string>
	 */
	public function parse_attributes(): array {
		// Bail if not a String Argument.
		if ( ! is_a( $this->argument, String_Type::class ) ) {
			return array();
		}

		/** @var String_Type $argument */
		$argument = $this->argument;

		$attributes = array();
		if ( ! is_null( $argument->get_min_length() ) ) {
			$attributes['minLength'] = $argument->get_min_length();
		}
		if ( ! is_null( $argument->get_max_length() ) ) {
			$attributes['maxLength'] = $argument->get_max_length();
		}
		if ( ! is_null( $argument->get_pattern() ) ) {
			$attributes['pattern'] = $argument->get_pattern();
		}
		return $attributes;
	}
}
