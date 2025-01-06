<?php

namespace Ruzgfpegk\Sessionator\Sessions\Traits;

use Ruzgfpegk\Sessionator\Sessions\Session;

/**
 * Trait to add a username to a session type
 */
trait UserName {
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
