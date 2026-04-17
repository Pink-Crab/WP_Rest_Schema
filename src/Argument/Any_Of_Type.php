<?php

declare(strict_types=1);

/**
 * Any_Of_Type — a schema-root `anyOf` combinator.
 *
 * Expresses "the value must match AT LEAST ONE of these sub-schemas". Emits
 * as `{ 'anyOf': [variantSchema1, variantSchema2, ...] }` with no outer
 * `type`.
 *
 * Differs from `One_Of_Type` in that WP's `rest_find_any_matching_schema`
 * returns the first passing variant, whereas `rest_find_one_matching_schema`
 * requires exactly one match.
 *
 * ```php
 * Any_Of_Type::on('thing')
 *     ->variant( String_Type::on('thing') )
 *     ->variant( Integer_Type::on('thing') );
 * // Emits: ['thing' => ['anyOf' => [{type:string}, {type:integer}]]]
 * ```
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.3.0
 */

namespace PinkCrab\WP_Rest_Schema\Argument;

class Any_Of_Type extends Combinator_Type {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	protected $combinator_key = 'anyOf';
}
