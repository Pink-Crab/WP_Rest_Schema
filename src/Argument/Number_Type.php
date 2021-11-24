<?php

declare(strict_types=1);

/**
 * Number Argument type.
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Argument;

use PinkCrab\WP_Rest_Schema\Argument\Argument;
use PinkCrab\WP_Rest_Schema\Argument\Attribute\Number_Attributes;

class Number_Type extends Argument {

	/**
	 * @method static exclusive_minimum( bool $min ): self
	 * @method static exclusive_maximum( bool $min ): self
	 * @method static exclusive_maximum( float $multiple_of ): self
	 * @method bool|null get_exclusive_maximum(): ?bool
	 * @method bool|null get_exclusive_minimum(): ?bool
	 * @method float|null get_multiple_of(): ?float
	 */
	use Number_Attributes;

	public function __construct( string $key ) {
		parent::__construct( $key );
		$this->type( Argument::TYPE_NUMBER );
	}

	/**
	 * Sets the min length of the value
	 *
	 * @param float $min
	 * @return static
	 */
	public function minimum( float $min ): self {
		return $this->add_attribute( 'minimum', $min );
	}

	/**
	 * Gets the set min length, returns null if not set.
	 *
	 * @return float|null
	 */
	public function get_minimum(): ?float {
		return $this->get_attribute( 'minimum' );
	}

	/**
	 * Sets the max length of the value
	 *
	 * @param float $max
	 * @return static
	 */
	public function maximum( float $max ): self {
		return $this->add_attribute( 'maximum', $max );
	}

	/**
	 * Gets the set max length, returns null if not set.
	 *
	 * @return float|null
	 */
	public function get_maximum(): ?float {
		return $this->get_attribute( 'maximum' );
	}
}
