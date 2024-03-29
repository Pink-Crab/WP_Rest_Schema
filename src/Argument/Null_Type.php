<?php

declare(strict_types=1);

/**
 * Null Argument type.
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Argument;

use PinkCrab\WP_Rest_Schema\Argument\Argument;

class Null_Type extends Argument {

	public function __construct( string $key ) {
		parent::__construct( $key );
		$this->type( Argument::TYPE_NULL );
	}
}
