<?php

declare(strict_types=1);

/**
 * Top-level schema builder for WP_REST_Controller::get_item_schema() output.
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.2.0
 */

namespace PinkCrab\WP_Rest_Schema;

use PinkCrab\WP_Rest_Schema\Argument\Argument;
use PinkCrab\WP_Rest_Schema\Argument\Object_Type;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;

class Schema {

	/** @var string */
	public const JSON_SCHEMA_DRAFT_04 = 'http://json-schema.org/draft-04/schema#';

	/**
	 * The schema title (resource name).
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * The schema description.
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * The internal Object_Type for building properties.
	 *
	 * @var Object_Type
	 */
	protected $object;

	public function __construct( string $title ) {
		$this->title  = $title;
		$this->object = new Object_Type( $title );
	}

	/**
	 * Static constructor.
	 *
	 * @param string $title
	 * @return static
	 */
	public static function on( string $title ): self {
		return new static( $title ); // @phpstan-ignore new.static
	}

	/**
	 * Alias for on().
	 *
	 * @param string $title
	 * @return static
	 */
	public static function field( string $title ): self {
		return static::on( $title );
	}

	/**
	 * Set the schema description.
	 *
	 * @param string $description
	 * @return static
	 */
	public function description( string $description ): self {
		$this->description = $description;
		return $this;
	}

	/**
	 * Get the schema description.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * Get the schema title.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return $this->title;
	}

	/**
	 * Get the internal Object_Type for direct manipulation.
	 *
	 * @return Object_Type
	 */
	public function get_object(): Object_Type {
		return $this->object;
	}

	/**
	 * Add a string property to the schema.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function string_property( string $name, ?callable $config = null ): self {
		$this->object->string_property( $name, $config );
		return $this;
	}

	/**
	 * Add a number property to the schema.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function number_property( string $name, ?callable $config = null ): self {
		$this->object->number_property( $name, $config );
		return $this;
	}

	/**
	 * Add an integer property to the schema.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function integer_property( string $name, ?callable $config = null ): self {
		$this->object->integer_property( $name, $config );
		return $this;
	}

	/**
	 * Add a boolean property to the schema.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function boolean_property( string $name, ?callable $config = null ): self {
		$this->object->boolean_property( $name, $config );
		return $this;
	}

	/**
	 * Add a null property to the schema.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function null_property( string $name, ?callable $config = null ): self {
		$this->object->null_property( $name, $config );
		return $this;
	}

	/**
	 * Add an array property to the schema.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function array_property( string $name, ?callable $config = null ): self {
		$this->object->array_property( $name, $config );
		return $this;
	}

	/**
	 * Add an object property to the schema.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function object_property( string $name, ?callable $config = null ): self {
		$this->object->object_property( $name, $config );
		return $this;
	}

	/**
	 * Mark property names as required at the parent-object level.
	 *
	 * Forwards to `Object_Type::required_properties()`. Emits a draft-4
	 * style `required: ['a','b']` array in the parsed schema.
	 *
	 * @param string ...$names
	 * @return static
	 */
	public function required_properties( string ...$names ): self {
		$this->object->required_properties( ...$names );
		return $this;
	}

	/**
	 * Set additional properties (boolean or schema).
	 *
	 * @param bool $allowed
	 * @return static
	 */
	public function additional_properties( bool $allowed ): self {
		$this->object->additional_properties( $allowed );
		return $this;
	}

	/**
	 * Set additional properties schema.
	 *
	 * @param Argument $schema
	 * @return static
	 */
	public function additional_properties_schema( Argument $schema ): self {
		$this->object->additional_properties_schema( $schema );
		return $this;
	}

	/**
	 * Converts the schema to a WordPress get_item_schema() compatible array.
	 *
	 * @return array<string, mixed>
	 */
	public function to_array(): array {
		$schema = array(
			'$schema' => self::JSON_SCHEMA_DRAFT_04,
			'title'   => $this->title,
			'type'    => 'object',
		);

		if ( '' !== $this->description ) {
			$schema['description'] = $this->description;
		}

		// Parse the object properties.
		$parsed = Argument_Parser::as_list( $this->object );

		// Extract properties from parsed output (skip type since we set it above).
		if ( isset( $parsed['properties'] ) ) {
			$schema['properties'] = $parsed['properties'];
		}

		if ( isset( $parsed['additionalProperties'] ) ) {
			$schema['additionalProperties'] = $parsed['additionalProperties'];
		}

		if ( isset( $parsed['patternProperties'] ) ) {
			$schema['patternProperties'] = $parsed['patternProperties'];
		}

		if ( isset( $parsed['minProperties'] ) ) {
			$schema['minProperties'] = $parsed['minProperties'];
		}

		if ( isset( $parsed['maxProperties'] ) ) {
			$schema['maxProperties'] = $parsed['maxProperties'];
		}

		return $schema;
	}

	/**
	 * Build the `context` collection param descriptor.
	 *
	 * Mirrors `WP_REST_Controller::get_context_param()`: derives the `enum` of
	 * allowed contexts from the union of `context` values set on the schema's
	 * properties, unique and reverse-sorted. Returns the standard param shape
	 * (type/description/sanitize_callback/validate_callback) merged with any
	 * caller-provided overrides.
	 *
	 * @param array<string, mixed> $args  Caller overrides merged on top.
	 * @return array<string, mixed>
	 */
	public function get_context_param( array $args = array() ): array {
		$param_details = array(
			'description'       => 'Scope under which the request is made; determines fields present in response.',
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$contexts = array();
		foreach ( $this->object->get_properties() as $property ) {
			$property_contexts = $property->get_context();
			if ( ! empty( $property_contexts ) ) {
				$contexts = array_merge( $contexts, $property_contexts );
			}
		}

		if ( ! empty( $contexts ) ) {
			$enum = array_values( array_unique( $contexts ) );
			rsort( $enum );
			$param_details['enum'] = $enum;
		}

		return array_merge( $param_details, $args );
	}
}
