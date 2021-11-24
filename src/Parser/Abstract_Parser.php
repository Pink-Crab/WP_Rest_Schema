<?php

declare(strict_types=1);

/**
 * Abstract class for parsing type specific attributes.
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Parser;

use PinkCrab\WP_Rest_Schema\Argument\Argument;

abstract class Abstract_Parser {

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
	 * Late static constructor for parsing.
	 *
	 * @param \PinkCrab\WP_Rest_Schema\Argument\Argument $argument
	 * @return array<string, mixed>
	 */
	public static function parse( Argument $argument ): array {
		$class = get_called_class();
		// @phpstan-ignore-next-line
		return ( new $class( $argument ) )->parse_attributes();
	}

	/**
	 * Parses the custom attributes for a type.
	 *
	 * @return array<string, mixed>
	 */
	abstract public function parse_attributes(): array;

}
