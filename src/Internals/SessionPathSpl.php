<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Internals;

use SplObjectStorage;

use Ruzgfpegk\Sessionator\Sessions\SessionBase;

/**
 * This class is used to store the session list in a SplObjectStorage object (object: session object, info: session name)
 */
class SessionPathSpl implements SessionPath {
	private SplObjectStorage $sessions;
	
	public function __construct() {
		$this->sessions = new SplObjectStorage();
	}
	
	public function getSessions(): array {
		return iterator_to_array( $this->sessions, false );
	}
	
	public function getSession( string $sessionName ): SessionBase {
		if ( $this->hasSession( $sessionName ) ) {
			return $this->sessions->current();
		}
		
		throw new \RuntimeException( "Session $sessionName not found in path $this->path" );
	}
	
	public function addSession( SessionBase $session ): void {
		$this->sessions->attach( $session, $session->getSessionName() );
	}
	
	public function hasSession( string $sessionName ): bool {
		$found = false;
		
		while( $this->sessions->valid() ) {
			if ( $this->sessions->current()->getSessionName() === $sessionName ) {
				$found = true;
				break;
			}
			
			$this->sessions->next();
		}
		
		return $found;
	}
	
	public function countSessions(): int {
		return $this->sessions->count();
	}
}
