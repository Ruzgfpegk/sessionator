<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Sessions;

/**
 * The RDP object extends the SessionBase object with specificities of RDP sessions
 */
class RDP extends SessionBase {
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
