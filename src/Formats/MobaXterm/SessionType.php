<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Sessions\Session;

/**
 * Interface to define a common basis to all MobaXterm session types (that extend SettingBlock)
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
	
	public function decodeFromString( string $sessionSettings ): array;
}
