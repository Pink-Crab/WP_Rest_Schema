<?php

declare(strict_types=1);

/**
 * Parser for schema-root combinators (One_Of_Type, Any_Of_Type).
 *
 * Emits the combinator key (oneOf/anyOf) at the schema root, with the
 * variant sub-schemas serialised as a list of full schemas.
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.3.0
 */

namespace PinkCrab\WP_Rest_Schema\Parser;

use PinkCrab\WP_Rest_Schema\Argument\Combinator_Type;
use PinkCrab\WP_Rest_Schema\Parser\Abstract_Parser;

class Combinator_Attribute_Parser extends Abstract_Parser {

	/**
	 * Parses a combinator argument into its root-level oneOf/anyOf shape.
	 *
	 * @return array<string, mixed>
	 */
	public function parse_attributes(): array {
		if ( ! is_a( $this->argument, Combinator_Type::class ) ) {
			return array();
		}

		/** @var Combinator_Type $argument */
		$argument = $this->argument;

		$variants = array();
		foreach ( $argument->get_variants() as $variant ) {
			$variants[] = Argument_Parser::as_list( $variant );
		}

		return array(
			$argument->get_combinator_key() => $variants,
		);
	}
}
