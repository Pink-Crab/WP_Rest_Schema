<?php

declare(strict_types=1);

/**
 * Union Type
 *
 * @package PinkCrab\WP_Rest_Schema
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.2.0
 */

namespace PinkCrab\WP_Rest_Schema\Argument;

use PinkCrab\WP_Rest_Schema\Argument\Argument;

class Union_Type extends Argument {

	/**
	 * The types format.
	 *
	 * @var Argument[]
	 */
	protected $types = array();

	/**
	 * The Union type
	 * anyOf or oneOf
	 * @var string $union_type
	 */
	protected $union_type = 'anyOf';

	final public function __construct( string $union_type = 'anyOf' ) {
		parent::__construct( 'union' . uniqid() );
		$this->union_type = $union_type;
		$this->type( Argument::TYPE_ANY );
	}

	/**
	 * Static constructor
	 *
	 * @param Argument ...$types
	 * @return self
	 */
	final public static function one_of( Argument ...$types ): self {
		$instance = new self( 'oneOf' );
		foreach ( $types as $type ) {
			$instance->option( $type );
		}
		return $instance;
	}

	/**
	 * Static constructor
	 *
	 * @param Argument ...$types
	 * @return self
	 */
	final public static function any_of( Argument ...$types ): self {
		$instance = new self( 'anyOf' );
		foreach ( $types as $type ) {
			$instance->option( $type );
		}
		return $instance;
	}

	/**
	 * Adds a type to the union.
	 *
	 * @param Argument $type
	 * @return self
	 */
	public function option( Argument $type ): self {
		$this->types[] = $type;
		return $this;
	}

	/**
	 * Get all types
	 *
	 * @return Argument[]
	 */
	public function get_options(): array {
		return $this->types;
	}

	/**
	 * Get the union type
	 *
	 * @return string
	 */
	public function get_union_type(): string {
		return $this->union_type;
	}

	/**
	 * Get callback to validate value
	 * @throws \Exception You can not call this for a union type.
	 * @return callable(string, \WP_REST_Request, string): bool|null
	 */
	public function get_validation(): ?callable {
		throw new \Exception( 'Union types do not support validation.' );
	}

	/**
	 * Set callback to validate value
	 *
	 * @param callable(string, \WP_REST_Request, string): bool $validation  Callback to validate value
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function validation( callable $validation ): self { //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		throw new \Exception( 'Union types do not support validation.' );
	}

	/**
	 * Get sanitizes the output
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return callable(mixed):mixed|null
	 * bool
	 */
	public function get_sanitization(): ?callable {
		throw new \Exception( 'Union types do not support sanitization.' );
	}

	/**
	 * Set sanitizes the output
	 *
	 * @param callable(mixed): bool $sanitization  Sanitizes the output
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function sanitization( callable $sanitization ): self { //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		throw new \Exception( 'Union types do not support sanitization.' );
	}

	/**
	 * Get the default value
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return string|int|float|bool|null
	 */
	public function get_default() {
		throw new \Exception( 'Union types do not support default.' );
	}

	/**
	 * Checks if the argument has a default assigned.
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return bool
	 */
	public function has_default(): bool {
		throw new \Exception( 'Union types do not support default.' );
	}

	/**
	 * Set the default value
	 *
	 * @param string|int|float|bool $default  The default value
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function default( $default ): self { //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		throw new \Exception( 'Union types do not support default.' );
	}

	/**
	 * Get is this argument required
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return bool
	 */
	public function is_required(): bool {
		throw new \Exception( 'Union types do not support required.' );
	}

	/**
	 * Get the data type of the argument.
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return bool|null
	 */
	public function get_required(): ?bool {
		throw new \Exception( 'Union types do not support required.' );
	}

	/**
	 * Set is this argument required
	 *
	 * @param bool $required  Is this argument required
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function required( bool $required = true ): self { //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		throw new \Exception( 'Union types do not support required.' );
	}

	/**
	 * Get the argument description.
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return string
	 */
	public function get_description(): string {
		throw new \Exception( 'Union types do not support descriptions.' );
	}

	/**
	 * Set the argument description.
	 *
	 * @param string $description  The argument description.
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function description( string $description ): self { //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		throw new \Exception( 'Union types do not support descriptions.' );
	}

	/**
	 * Get optional format to expect value.
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return string|null
	 */
	public function get_format(): ?string {
		throw new \Exception( 'Union types do not support formats.' );
	}

	/**
	 * Set optional format to expect value.
	 *
	 * @param string $format  Optional format to expect value.
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function format( string $format ): self {  //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		throw new \Exception( 'Union types do not support formats.' );
	}

	/**
	 * Get attributes
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return array<string, mixed>
	 */
	public function get_attributes(): array {
		throw new \Exception( 'Union types do not support attributes.' );
	}

	/**
	 * Set attributes
	 *
	 * @param array<string,mixed> $attributes
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function set_attributes( array $attributes ): self { //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		throw new \Exception( 'Union types do not support attributes.' );
	}

	/**
	 * Adds a single attribute
	 *
	 * @param string $key
	 * @param mixed $value
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function add_attribute( string $key, $value ): self { //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed
		throw new \Exception( 'Union types do not support attributes.' );
	}

	/**
	 * Gets an attribute based on its key, allows for a fallback
	 *
	 * @param string $key
	 * @param mixed $fallback
	 * @throws \Exception You can not call this for a union type.
	 * @return mixed
	 */
	public function get_attribute( string $key, $fallback = null ) {  //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed
		throw new \Exception( 'Union types do not support attributes.' );
	}

	/**
	 * Get expected of all accepted values
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return array<string|float|int|bool>|null
	 */
	public function get_expected(): ?array {
		throw new \Exception( 'Union types do not support expected.' );
	}

	/**
	 * Set expected of all accepted values
	 *
	 * @param mixed ...$expected  Accept value for argument.
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function expected( ...$expected ): self {//phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		throw new \Exception( 'Union types do not support expected.' );
	}

	/**
	 * Gets the set min length, returns null if not set.
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return string|null
	 */
	public function get_name(): ?string {
		throw new \Exception( 'Union types do not support names.' );
	}

	/**
	 * Sets the max length of the value
	 *
	 * @param string $name
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function name( string $name ): self {//phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		throw new \Exception( 'Union types do not support names.' );
	}


	/**
	 * Get the arguments context
	 *
	 * @throws \Exception You can not call this for a union type.
	 * @return string[]
	 */
	public function get_context() {
		throw new \Exception( 'Union types do not support context.' );
	}

	/**
	 * Set the arguments context
	 *
	 * @param string ...$context  The arguments context
	 * @throws \Exception You can not call this for a union type.
	 * @return static
	 */
	public function context( ...$context ) {//phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
		throw new \Exception( 'Union types do not support context.' );
	}
}
