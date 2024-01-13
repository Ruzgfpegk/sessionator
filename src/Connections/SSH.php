<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Connections;

/**
 * The SSH object extends the Common object with specificities of SSH connections
 */
class SSH extends Common {
	private string $userName = '';
	
	/**
	 * Setter for the userName property.
	 *
	 * @param $userName string Sets the username for this connection
	 *
	 * @return Connection
	 */
	public function setUserName( string $userName ): Connection {
		$this->userName = $userName;
		
		return $this;
	}
	
	public function getUserName(): string {
		return $this->userName;
	}
}
