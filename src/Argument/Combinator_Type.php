<?php

declare(strict_types=1);

/**
 * Combinator_Type — abstract base for schema-root combinators.
 *
 * A combinator schema has NO primitive `type`; it expresses a relationship
 * between variant sub-schemas. Concrete subclasses set `$combinator_key` to
 * the JSON Schema keyword that should appear in the parsed output (e.g.
 * `oneOf`, `anyOf`).
 *
 * Variants are added via `variant()` and emitted as a list of full schemas
 * under the combinator key, matching the shape WP's REST validator expects at
 * the schema root (see `rest_find_one_matching_schema` /
 * `rest_find_any_matching_schema`).
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.3.0
 */

namespace PinkCrab\WP_Rest_Schema\Argument;

use PinkCrab\WP_Rest_Schema\Argument\Argument;

abstract class Combinator_Type extends Argument {

	/**
	 * Combinator key emitted in the parsed output.
	 *
	 * Subclasses override this to declare themselves as `oneOf`, `anyOf`, etc.
	 *
	 * @var string
	 */
	protected $combinator_key = '';

	/**
	 * Sub-schemas that the value must satisfy under the combinator's rule.
	 *
	 * @var array<int, Argument>
	 */
	protected $variants = array();

	/**
	 * Add a variant sub-schema.
	 *
	 * @param Argument $variant  A complete Argument schema (any type).
	 * @return self
	 */
	public function variant( Argument $variant ): self {
		$this->variants[] = $variant;
		return $this;
	}

	/**
	 * Get the list of variant sub-schemas.
	 *
	 * @return array<int, Argument>
	 */
	public function get_variants(): array {
		return $this->variants;
	}

	/**
	 * Get the combinator key (e.g. 'oneOf').
	 *
	 * @return string
	 */
	public function get_combinator_key(): string {
		return $this->combinator_key;
	}
}
