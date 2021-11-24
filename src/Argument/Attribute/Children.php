<?php

declare(strict_types=1);

/**
 * Helpers for generating child arguments.
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Argument\Attribute;

use PinkCrab\WP_Rest_Schema\Argument\Argument;
use PinkCrab\WP_Rest_Schema\Argument\Null_Type;
use PinkCrab\WP_Rest_Schema\Argument\Array_Type;
use PinkCrab\WP_Rest_Schema\Argument\Number_Type;
use PinkCrab\WP_Rest_Schema\Argument\Object_Type;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;
use PinkCrab\WP_Rest_Schema\Argument\Boolean_Type;
use PinkCrab\WP_Rest_Schema\Argument\Integer_Type;

trait Children {

	/**
	 * Returns an array of all valid argument types.
	 *
	 * @return array<string, class-string<Argument>>
	 */
	protected function type_map(): array {
		return array(
			Argument::TYPE_ARRAY   => Array_Type::class,
			Argument::TYPE_BOOLEAN => Boolean_Type::class,
			Argument::TYPE_INTEGER => Integer_Type::class,
			Argument::TYPE_NUMBER  => Number_Type::class,
			Argument::TYPE_OBJECT  => Object_Type::class,
			Argument::TYPE_STRING  => String_Type::class,
			Argument::TYPE_NULL    => Null_Type::class,
		);
	}

	/**
	 * Creates a child from the current Argument.
	 *
	 * @param string $reference
	 * @param string $type
	 * @return \PinkCrab\WP_Rest_Schema\Argument\Argument
	 * @throws \Exception If applied to a none Argument Class or invalid type.
	 *
	 */
	protected function create_child( string $reference, string $type ): Argument {
		// Can only be called from an Argument parent class.
		if ( ! is_a( $this, Argument::class ) ) {
			throw new \Exception( 'Only classes that extend Argument can create children types', 300 );
		}

		if ( ! in_array( $type, array_keys( $this->type_map() ), true ) ) {
			throw new \Exception( "{$type} is not a valid argument type.", 301 );
		}

		$key   = "{$this->get_key()}_{$reference}";
		$class = $this->type_map()[ $type ];

		return new $class( $key );
	}
}
