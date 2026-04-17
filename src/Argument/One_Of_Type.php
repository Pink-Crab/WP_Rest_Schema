<?php

declare(strict_types=1);

/**
 * One_Of_Type — a schema-root `oneOf` combinator.
 *
 * Expresses "the value must match EXACTLY ONE of these sub-schemas". Emits as
 * `{ 'oneOf': [variantSchema1, variantSchema2, ...] }` with no outer `type`.
 *
 * Use this when variants have distinct type-specific attributes that cannot
 * be expressed with `union_with_type()` alone (e.g. one variant is a string
 * with `minLength`, another is an integer with `minimum`).
 *
 * ```php
 * One_Of_Type::on('thing')
 *     ->variant( String_Type::on('thing')->min_length(3) )
 *     ->variant( Integer_Type::on('thing')->minimum(1) );
 * // Emits: ['thing' => ['oneOf' => [{type:string,minLength:3}, {type:integer,minimum:1}]]]
 * ```
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.3.0
 */

namespace PinkCrab\WP_Rest_Schema\Argument;

class One_Of_Type extends Combinator_Type {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	protected $combinator_key = 'oneOf';
}
