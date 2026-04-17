<?php

declare(strict_types=1);

/**
 * Object Argument type.
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Argument;

use PinkCrab\WP_Rest_Schema\Argument\Argument;
use PinkCrab\WP_Rest_Schema\Argument\Attribute\Children;
use PinkCrab\WP_Rest_Schema\Argument\Attribute\Element_Requirements;

class Object_Type extends Argument {

	use Children;
	use Element_Requirements;

	/**
	 * Properties
	 *
	 * @var array<string, Argument>
	 */
	protected $properties = array();

	/**
	 * Additional properties (optional)
	 * Can be a boolean (allow/disallow) or a single Argument schema.
	 *
	 * @var bool|Argument|null
	 */
	protected $additional_properties;

	/**
	 * Properties based on the pattern.
	 *
	 * @var array<string, Argument>
	 */
	protected $pattern_properties = array();

	public function __construct( string $key ) {
		parent::__construct( $key );
		$this->type( Argument::TYPE_OBJECT );
	}

	/**
	 * Sets the min properties of the value
	 *
	 * @param int $min
	 * @return static
	 */
	public function min_properties( int $min ): self {
		return $this->add_attribute( 'minProperties', $min );
	}

	/**
	 * Gets the set min properties, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_min_properties(): ?int {
		$value = $this->get_attribute( 'minProperties' );
		return is_int( $value ) ? $value : null;
	}

	/**
	 * Sets the max properties of the value
	 *
	 * @param int $max
	 * @return static
	 */
	public function max_properties( int $max ): self {
		return $this->add_attribute( 'maxProperties', $max );
	}

	/**
	 * Gets the set max properties, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_max_properties(): ?int {
		$value = $this->get_attribute( 'maxProperties' );
		return is_int( $value ) ? $value : null;
	}

	/**
	 * Mark property names as required at the parent-object level.
	 *
	 * Emits JSON-Schema draft-04 style `required: ['a','b']` on the parsed
	 * object schema. Matches the convention used by most WP core
	 * `get_item_schema()` implementations. Coexists with per-property
	 * `required: true` booleans.
	 *
	 * @param string ...$names  One or more property names to mark required.
	 * @return self
	 */
	public function required_properties( string ...$names ): self {
		return $this->add_attribute( 'required_properties', $names );
	}

	/**
	 * Get the list of property names marked as required at the object level.
	 *
	 * @return array<int, string>
	 */
	public function get_required_properties(): array {
		$value = $this->get_attribute( 'required_properties' );
		if ( ! is_array( $value ) ) {
			return array();
		}
		$names = array();
		foreach ( $value as $name ) {
			if ( is_string( $name ) ) {
				$names[] = $name;
			}
		}
		return $names;
	}


	/**
	 * Regular Properties.
	 */

	/**
	 * Adds a property
	 *
	 * @param string $name           The property name
	 * @param string $type           The type class name
	 * @param callable|null $config
	 * @return static
	 */
	protected function add_property( string $name, string $type, ?callable $config = null ): self {
		$item                      = $this->create_child( $name, $type );
		$this->properties[ $name ] = is_null( $config ) ? $item : $config( $item );
		return $this;
	}

	/**
	 * Creates a string property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function string_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_STRING, $config );
	}

	/**
	 * Creates a number property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function number_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_NUMBER, $config );
	}

	/**
	 * Creates a integer property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function integer_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_INTEGER, $config );
	}

	/**
	 * Creates a null property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function null_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_NULL, $config );
	}

	/**
	 * Creates a boolean property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function boolean_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_BOOLEAN, $config );
	}

	/**
	 * Creates a array property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function array_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_ARRAY, $config );
	}

	/**
	 * Creates a object property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function object_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_OBJECT, $config );
	}

	/**
	 * Gets all the properties for the object.
	 *
	 * @return array<string, Argument>
	 */
	public function get_properties(): array {
		return $this->properties;
	}

	/**
	 * Checks if any properties defined.
	 *
	 * @return bool
	 */
	public function has_properties(): bool {
		return ! empty( $this->properties );
	}

	/**
	 * Gets the count of properties.
	 *
	 * @return int
	 */
	public function count_properties(): int {
		return count( $this->properties );
	}

	/**
	 * ADDITIONAL PROPERTIES
	 */

	/**
	 * Sets whether additional properties are allowed (boolean).
	 *
	 * @param bool $allowed
	 * @return static
	 */
	public function additional_properties( bool $allowed ): self {
		$this->additional_properties = $allowed;
		return $this;
	}

	/**
	 * Sets a schema definition for additional properties.
	 *
	 * @param Argument $schema
	 * @return static
	 */
	public function additional_properties_schema( Argument $schema ): self {
		$this->additional_properties = $schema;
		return $this;
	}

	/**
	 * Gets the additional properties value.
	 *
	 * @return bool|Argument|null
	 */
	public function get_additional_properties() {
		return $this->additional_properties;
	}

	/**
	 * Checks if additional properties has been set.
	 *
	 * @return bool
	 */
	public function has_additional_properties(): bool {
		return ! is_null( $this->additional_properties );
	}

	/**
	 * PATTERN PROPERTIES
	 */

	/**
	 * Adds a pattern property
	 *
	 * @param string $pattern        The property pattern
	 * @param string $type           The type class name
	 * @param callable|null $config
	 * @return static
	 */
	protected function add_pattern_property( string $pattern, string $type, ?callable $config = null ): self {
		$item                                 = $this->create_child( $pattern, $type );
		$this->pattern_properties[ $pattern ] = is_null( $config ) ? $item : $config( $item );
		return $this;
	}

	/**
	 * Creates a string typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function string_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_STRING, $config );
	}

		/**
	 * Creates a number typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function number_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_NUMBER, $config );
	}

	/**
	 * Creates an integer typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function integer_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_INTEGER, $config );
	}

	/**
	 * Creates a null typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function null_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_NULL, $config );
	}

	/**
	 * Creates a boolean typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function boolean_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_BOOLEAN, $config );
	}

	/**
	 * Creates an array typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function array_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_ARRAY, $config );
	}

	/**
	 * Creates an object typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function object_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_OBJECT, $config );
	}

	/**
	 * Gets all the pattern properties for the object.
	 *
	 * @return array<string, Argument>
	 */
	public function get_pattern_properties(): array {
		return $this->pattern_properties;
	}

	/**
	 * Checks if any pattern properties defined.
	 *
	 * @return bool
	 */
	public function has_pattern_properties(): bool {
		return ! empty( $this->pattern_properties );
	}

	/**
	 * Gets the count of pattern properties.
	 *
	 * @return int
	 */
	public function count_pattern_properties(): int {
		return count( $this->pattern_properties );
	}
}
