<?php

declare(strict_types=1);

/**
 * A route argument definition.
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.1.0
 */

namespace PinkCrab\WP_Rest_Schema\Argument;

use TypeError;

class Argument {


	/**
	 * All valid value types.
	 */
	/** @var string */
	public const TYPE_STRING = 'string';
	/** @var string */
	public const TYPE_BOOLEAN = 'boolean';
	/** @var string */
	public const TYPE_INTEGER = 'integer';
	/** @var string */
	public const TYPE_NUMBER = 'number';
	/** @var string */
	public const TYPE_ARRAY = 'array';
	/** @var string */
	public const TYPE_OBJECT = 'object';
	/** @var string */
	public const TYPE_NULL = 'null';

	/**
	 * All valid format types.
	 */
	/** @var string */
	public const FORMAT_DATE_TIME = 'date-time';
	/** @var string */
	public const FORMAT_EMAIL = 'email';
	/** @var string */
	public const FORMAT_IP = 'ip';
	/** @var string */
	public const FORMAT_URI = 'uri';
	/** @var string */
	public const FORMAT_UUID = 'uuid';
	/** @var string */
	public const FORMAT_HEX = 'hex-color';
	/** @var string */
	public const FORMAT_TEXT_FIELD = 'text-field';
	/** @var string */
	public const FORMAT_TEXTAREA_FIELD = 'textarea-field';


	/**
	 * The argument key
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Callback to validate value
	 *
	 * @var null|(callable(string $param, \WP_REST_Request<array<string, mixed>> $request, string $key):bool)
	 */
	protected $validation;

	/**
	 * Sanitizes the output
	 *
	 * @var null|callable(mixed $value):bool
	 */
	protected $sanitization;

	/**
	 * Is this argument required
	 *
	 * @var bool|null
	 */
	protected $required;

	/**
	 * The data type of the argument.
	 *
	 * @var string|array<int, string>|null
	 */
	protected $type;

	/**
	 * The argument description.
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * An array of all the attributes for the Argument.
	 *
	 * @var array<string, mixed>
	 */
	protected $attributes = array();

	/**
	 * The default value
	 *
	 * @var string|int|float|bool|array<mixed>|object|null
	 */
	protected $default;

	/**
	 * Whether a default has been explicitly set.
	 *
	 * Tracks the set-ness separately from the value so that null can be
	 * emitted as a default (valid when type includes 'null').
	 *
	 * @var bool
	 */
	protected $has_default_set = false;

	/**
	 * Raw arg_options pass-through for WP REST controllers.
	 *
	 * Emitted verbatim in the parsed output under the `arg_options` key so
	 * controllers can override `sanitize_callback` / `validate_callback` per
	 * property. See `WP_REST_Controller::add_additional_fields_schema()`.
	 *
	 * @var array<string, mixed>|null
	 */
	protected $arg_options;

	/**
	 * Optional format to expect value.
	 *
	 * @var string|null
	 */
	protected $format;

	/**
	 * Enum of all accepted values.
	 *
	 * WP compares enum entries via `in_array(..., $enum, true)` so any
	 * JSON-comparable value is valid — including `null`, arrays, and objects
	 * for schemas whose `type` accommodates them.
	 *
	 * @var array<int, mixed>|null
	 */
	protected $expected;

	/**
	 * The arguments context
	 *
	 * @var string[]
	 */
	protected $context = array();

	/**
	 * Whether the property is readonly.
	 *
	 * @var bool|null
	 */
	protected $readonly;

	/**
	 * The schema title.
	 *
	 * @var string|null
	 */
	protected $title;


	public function __construct( string $key ) {
		$this->key = $key;
	}

	/**
	 * Static constructor.
	 *
	 * @param string $key
	 * @param callable $config
	 * @return static
	 */
	final public static function on( string $key, ?callable $config = null ): self {
		$class    = get_called_class();
		$argument = new $class( $key );
		return $config
			? $config( $argument )
			: $argument;
	}

	/**
	 * Alias for on().
	 *
	 * @param string $key
	 * @param callable|null $config
	 * @return static
	 */
	final public static function field( string $key, ?callable $config = null ): self {
		return static::on( $key, $config );
	}

	/**
	 * Get the argument key
	 *
	 * @return string
	 */
	public function get_key(): string {
		return $this->key;
	}

	/**
	 * Get callback to validate value
	 *
	 * @return callable(string, \WP_REST_Request<array<string, mixed>>, string): bool|null
	 */
	public function get_validation(): ?callable {
		return $this->validation;
	}

	/**
	 * Set callback to validate value
	 *
	 * @param callable(string, \WP_REST_Request<array<string, mixed>>, string): bool $validation  Callback to validate value
	 *
	 * @return static
	 */
	public function validation( callable $validation ): self {
		$this->validation = $validation;
		return $this;
	}

	/**
	 * Get sanitizes the output
	 *
	 * @return callable(mixed):mixed|null
	 * bool
	 */
	public function get_sanitization(): ?callable {
		return $this->sanitization;
	}

	/**
	 * Set sanitizes the output
	 *
	 * @param callable(mixed): bool $sanitization  Sanitizes the output
	 *
	 * @return static
	 */
	public function sanitization( callable $sanitization ): self {
		$this->sanitization = $sanitization;
		return $this;
	}

	/**
	 * Get the default value
	 *
	 * @return string|int|float|bool|array<mixed>|object|null
	 */
	public function get_default() {
		return $this->default;
	}

	/**
	 * Checks if the argument has a default assigned.
	 *
	 * @return bool
	 */
	public function has_default(): bool {
		return $this->has_default_set;
	}

	/**
	 * Set the default value
	 *
	 * @param string|int|float|bool|array<mixed>|object|null $default_value  The default value
	 * @return static
	 */
	public function default( $default_value ): self {
		$this->default         = $default_value;
		$this->has_default_set = true;
		return $this;
	}

	/**
	 * Get is this argument required
	 *
	 * @return bool
	 */
	public function is_required(): bool {
		return $this->required ?? false;
	}

	/**
	 * Get the data type of the argument.
	 *
	 * @return bool|null
	 */
	public function get_required(): ?bool {
		return $this->required;
	}

	/**
	 * Set is this argument required
	 *
	 * @param bool $required  Is this argument required
	 * @return static
	 */
	public function required( bool $required = true ): self {
		$this->required = $required;
		return $this;
	}

	/**
	 * Get the data type of the argument.
	 *
	 * @return string|string[]|null
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Set the data type of the argument.
	 *
	 * @param string|string[]|mixed $type  The data type of the argument.
	 *
	 * @return static
	 */
	public function type( $type ): self {
		if ( ! is_string( $type ) && ! is_array( $type ) ) {
			throw new TypeError( 'Only single types or array of types are allowed with arguments.' );
		}

		/** @var string|array<int, string> $type */
		$this->type = $type;
		return $this;
	}

	/**
	 * Adds an extra type to use as a union type with.
	 * Please note doesn't add attributes for additional types.
	 *
	 * @param string $type
	 * @return self
	 */
	public function union_with_type( string $type ): self {
		// Cast the current types to array, if not already.
		if ( ! is_array( $this->type ) ) {
			// If the current type value is null, set an empty array, else create as an array with the value.
			$this->type = is_null( $this->type ) ? array() : array( $this->type );
		}
		$this->type[] = $type;
		$this->type   = \array_unique( $this->type );
		return $this;
	}

	/**
	 * Get the argument description.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * Set the argument description.
	 *
	 * @param string $description  The argument description.
	 * @return static
	 */
	public function description( string $description ): self {
		$this->description = $description;
		return $this;
	}

	/**
	 * Get optional format to expect value.
	 *
	 * @return string|null
	 */
	public function get_format(): ?string {
		return $this->format;
	}

	/**
	 * Set optional format to expect value.
	 *
	 * @param string $format  Optional format to expect value.
	 * @return static
	 */
	public function format( string $format ): self {
		$this->format = $format;
		return $this;
	}

	/**
	 * Get attributes
	 *
	 * @return array<string, mixed>
	 */
	public function get_attributes(): array {
		return $this->attributes;
	}

	/**
	 * Set attributes
	 *
	 * @param array<string,mixed> $attributes
	 *
	 * @return static
	 */
	public function set_attributes( array $attributes ): self {
		$this->attributes = $attributes;
		return $this;
	}

	/**
	 * Adds a single attribute
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return static
	 */
	public function add_attribute( string $key, $value ): self {
		$this->attributes[ $key ] = $value;
		return $this;
	}

	/**
	 * Gets an attribute based on its key, allows for a fallback
	 *
	 * @param string $key
	 * @param mixed $fallback
	 * @return mixed
	 */
	public function get_attribute( string $key, $fallback = null ) {
		return \array_key_exists( $key, $this->attributes )
			? $this->attributes[ $key ]
			: $fallback;
	}

	/**
	 * Get expected of all accepted values
	 *
	 * @return array<int, mixed>|null
	 */
	public function get_expected(): ?array {
		return $this->expected;
	}

	/**
	 * Set expected of all accepted values.
	 *
	 * Any JSON-comparable value is accepted (null, arrays, objects included)
	 * since WP compares enum entries with `in_array(..., $enum, true)`.
	 *
	 * @param mixed ...$expected  Accepted values for the argument.
	 * @return static
	 */
	public function expected( ...$expected ): self {
		$merged         = is_array( $this->expected )
			? array_merge( $this->expected, $expected )
			: $expected;
		$this->expected = array_values( $merged );
		return $this;
	}

	/**
	 * Gets the set min length, returns null if not set.
	 *
	 * @return string|null
	 */
	public function get_name(): ?string {
		$value = $this->get_attribute( 'name' );
		return is_string( $value ) ? $value : null;
	}

	/**
	 * No-op setter kept for backwards compatibility.
	 *
	 * `name` is not in `rest_get_allowed_schema_keywords()` and was leaking
	 * from the internal child-indexing machinery into the parsed output.
	 * Calling this method now triggers an `E_USER_DEPRECATED` notice and
	 * does nothing.
	 *
	 * @deprecated 1.0.0 Removed from schema output. Will be removed in a
	 *                   future release.
	 *
	 * @param string $name  Ignored.
	 * @return static
	 */
	public function name( string $name ): self { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
		trigger_error(
			'Argument::name() is deprecated and no longer affects schema output. It will be removed in a future release.',
			E_USER_DEPRECATED
		);
		return $this;
	}


	/**
	 * Get the arguments context
	 *
	 * @return string[]
	 */
	public function get_context() {
		return $this->context;
	}

	/**
	 * Set the arguments context
	 *
	 * @param string ...$context  The arguments context
	 * @return static
	 */
	public function context( ...$context ) {
		$this->context = array_merge( $this->context, $context );
		return $this;
	}

	/**
	 * Get whether the property is readonly.
	 *
	 * @return bool|null
	 */
	public function get_readonly(): ?bool {
		return $this->readonly;
	}

	/**
	 * Set whether the property is readonly.
	 *
	 * @param bool $readonly_value
	 * @return static
	 */
	public function readonly( bool $readonly_value = true ): self {
		$this->readonly = $readonly_value;
		return $this;
	}

	/**
	 * Get the schema title.
	 *
	 * @return string|null
	 */
	public function get_title(): ?string {
		return $this->title;
	}

	/**
	 * Set the schema title.
	 *
	 * @param string $title
	 * @return static
	 */
	public function title( string $title ): self {
		$this->title = $title;
		return $this;
	}

	/**
	 * Get the raw arg_options pass-through array.
	 *
	 * @return array<string, mixed>|null
	 */
	public function get_arg_options(): ?array {
		return $this->arg_options;
	}

	/**
	 * Set arg_options for WP REST controller pass-through.
	 *
	 * The array is emitted verbatim under the `arg_options` key in the parsed
	 * schema, allowing controllers to override `sanitize_callback` /
	 * `validate_callback` or any other WP arg option per-property.
	 *
	 * @param array<string, mixed> $options  Raw arg_options array to attach.
	 * @return static
	 */
	public function arg_options( array $options ): self {
		$this->arg_options = $options;
		return $this;
	}
}
