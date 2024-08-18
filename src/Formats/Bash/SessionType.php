<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\Bash;

use Ruzgfpegk\Sessionator\Sessions\Session;

/**
 * Interface to define a common basis to all command-line session types (that extend SettingLine)
 */
interface SessionType {
	public function getString(): string;
	
	/**
	 * Registers all parameters set by "->setSessionParam( 'paramName', 'paramValue' )"
	 *
	 * @param Session $sessionDetails
	 *
	 * @return void
	 */
	public function applyParams( Session $sessionDetails ): void;
}
