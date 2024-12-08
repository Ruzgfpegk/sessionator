<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Internals;

use Ruzgfpegk\Sessionator\Sessions\SessionBase;

/**
 * This class is used to store the session list in an associative array (key: session name, value: session object)
 */
class SessionPathArray implements SessionPath {
	/**
	 *  Key: Session name
	 *  Value: Object implementing the Session interface and extending the Common abstract class
	 *
	 * @var array
	 */
	private array $sessions;
	
	public function __construct() {
		$this->sessions = [];
	}
	
	public function getSessions(): array {
		return $this->sessions;
	}
	
	public function getSession( string $sessionName ): SessionBase {
		return $this->sessions[ $sessionName ];
	}
	
	public function addSession( SessionBase $session ): void {
		$this->sessions[ $session->getSessionName() ] = $session;
	}
	
	public function hasSession( string $sessionName ): bool {
		if ( array_key_exists( $sessionName, $this->sessions ) ) {
			return true;
		}
		
		return false;
	}
	
	public function countSessions(): int {
		return count( $this->sessions );
	}
}
