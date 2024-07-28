<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Sessions;

/**
 * Interface to define a common basis to all session types
 */
interface Session {
	public function setSessionName( string $sessionName ): Session;
	
	public function setHostName( string $hostName ): Session;
	
	/**
	 * Sets a singular parameter to a given value
	 *
	 * @param string $paramName Parameter name (see Settings.md)
	 * @param string $paramValue Value to set the parameter to
	 *
	 * @return Session Returns the Session-implementing object for chaining
	 */
	public function setSessionParam( string $paramName, string $paramValue ): Session;
}
