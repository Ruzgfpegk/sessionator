<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Internals;

use DS\Set;

use Ruzgfpegk\Sessionator\Sessions\SessionBase;

/**
 * This class is used to store the session list in a Ds\Set object
 */
class SessionPathDs implements SessionPath {
	private Set $sessions;
	
	public function __construct() {
		$this->sessions = new Set();
	}
	
	public function getSessions(): array {
		return $this->sessions->toArray();
	}
	
	public function getSession( string $sessionName ): SessionBase {
		return $this->sessions->filter( function( $session ) use ( $sessionName ) {
			return $session->getSessionName() === $sessionName;
		} )->first();
	}
	
	public function addSession( SessionBase $session ): void {
		$this->sessions->add( $session );
	}
	
	public function hasSession( string $sessionName ): bool {
		$found = false;
		
		foreach ( $this->sessions as $session ) {
			if ( $session->getSessionName() === $sessionName ) {
				$found = true;
				break;
			}
		}
		
		return $found;
	}
	
	public function countSessions(): int {
		return $this->sessions->count();
	}
}
