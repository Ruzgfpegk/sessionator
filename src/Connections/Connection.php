<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Connections;

/**
 * Interface to define a common basis to all connection types
 */
interface Connection {
	public function setSessionName( string $sessionName ): Connection;
	
	public function setHostName( string $hostName ): Connection;
	
	/**
	 * Sets a singular parameter to a given value
	 *
	 * @param string $paramName Parameter name (see Settings.md)
	 * @param string $paramValue Value to set the parameter to
	 *
	 * @return Connection Returns the Connection-implementing object for chaining
	 */
	public function setSessionParam( string $paramName, string $paramValue ): Connection;
}
