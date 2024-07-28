<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Sessions;

/**
 * The SSH object extends the Common object with specificities of SSH sessions
 */
class SSH extends Common {
	private string $userName = '';
	
	/**
	 * Setter for the userName property.
	 *
	 * @param $userName string Sets the username for this session
	 *
	 * @return Session
	 */
	public function setUserName( string $userName ): Session {
		$this->userName = $userName;
		
		return $this;
	}
	
	public function getUserName(): string {
		return $this->userName;
	}
}
