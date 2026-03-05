<?php

declare(strict_types=1);

/**
 * Unit Tests for the null type parser.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\WP_Rest_Schema
 * @since 0.2.0
 */

namespace PinkCrab\WP_Rest_Schema\Tests\Argument\Parser;

use WP_UnitTestCase;
use PinkCrab\WP_Rest_Schema\Argument\Null_Type;
use PinkCrab\WP_Rest_Schema\Tests\Argument\Parser\Abstract_Parser_Testcase;

class Test_Null_Type_Parser extends Abstract_Parser_Testcase {

	public function type_class(): string {
		return Null_Type::class;
	}

	public function type_name(): string {
		return 'null';
	}
}
