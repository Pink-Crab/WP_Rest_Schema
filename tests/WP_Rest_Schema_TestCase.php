<?php

declare(strict_types=1);

/**
 * Testcase which allows the checking of rest schema.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\WP_Rest_Schema
 */

namespace PinkCrab\WP_Rest_Schema\Tests;

use WP_Error;
use WP_UnitTestCase;
use WP_REST_Response;

class WP_Rest_Schema_TestCase extends WP_UnitTestCase {

	/**
	 * Assert that schema is valid.
	 *
	 * @pram mixed $value
	 * @param array $schema
	 * @param ?string $message
	 * @return void
	 */
	public function assertSchemaValid( $value, array $schema, ?string $message = null ): void {
		$result = rest_validate_value_from_schema( $value, $schema, );

		// If error, compile message.
		if ( is_wp_error( $result ) ) {
			$message  = $message ?? $result->get_error_message();
		}

		$this->assertTrue( $result, $message ?? '' );
	}

	/**
	 * Assert that schema is invalid.
	 * 
	 * @pram mixed $value
	 * @param array $schema
	 * @param ?string $message
	 * @return void
	 */
	public function assertSchemaInvalid( $value, array $schema, ?string $message = null ): void {
		$result = rest_validate_value_from_schema( $value, $schema, );

		// If error, compile message.
		if ( is_wp_error( $result ) ) {
			$message  = $message ?? $result->get_error_message();
		}

		$this->assertWPError( $result, $message ?? '' );
	}
}
