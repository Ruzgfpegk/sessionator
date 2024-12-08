<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Internals;

use Ruzgfpegk\Sessionator\Sessions\SessionBase;

/**
 * Interface to define the minimum requirements for a session storage unit based on a path
 */
interface SessionPath {
	public function __construct();
	
	public function getSessions(): array;
	
	// Used by Sessionator::importFromSession()
	public function getSession( string $sessionName ): SessionBase;
	
	public function addSession( SessionBase $session ): void;
	
	// Used by Sessionator::importFromSession()
	public function hasSession( string $sessionName ): bool;
	
	public function countSessions(): int;
}
