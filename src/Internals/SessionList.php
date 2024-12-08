<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Internals;

use RuntimeException;

use Ruzgfpegk\Sessionator\Sessions\SessionBase;

/**
 * This class handles communication with the various session storage types
 */
class SessionList {
	/**
	 * @var string Choices : 'array', 'SPL' or 'Ds'
	 */
	private string $sessionStorageType;
	/**
	 * @var array The pathList property contains paths as keys and SessionPath-derived objects as values
	 */
	public array $pathList;
	
	public function __construct( string $sessionStorageType = 'array' ) {
		$this->sessionStorageType = $sessionStorageType;
		$this->pathList           = [];
	}
	
	public function getSessionStorageType(): string {
		return $this->sessionStorageType;
	}
	
	public function getPathList(): array {
		return array_keys( $this->pathList );
	}
	
	public function getPathSessions( string $pathName ): array {
		return $this->pathList[ $pathName ]->getSessions();
	}
	
	public function pathExists( string $pathName ): bool {
		return array_key_exists( $pathName, $this->pathList );
	}
	
	public function add( SessionBase $session ): void {
		$pathName = $session->getPathName();
		
		if ( ! $this->pathExists( $pathName ) ) {
			switch ( $this->sessionStorageType ) {
				case 'array':
					$this->pathList[ $pathName ] = new SessionPathArray();
					break;
				case 'SPL':
					$this->pathList[ $pathName ] = new SessionPathSpl();
					break;
				case 'Ds':
					$this->pathList[ $pathName ] = new SessionPathDs();
					break;
				default:
					throw new RuntimeException( 'Session storage type' . $this->sessionStorageType . ' not yet implemented' );
			}
		}
		
		$this->pathList[ $pathName ]->addSession( $session );
	}
	
	/**
	 * Sort path names alphabetically (natural sort: numbers going 1, 9, 10, ...)
	 *
	 * @return void
	 */
	public function sort(): void {
		ksort( $this->pathList, SORT_NATURAL );
	}
	
	public function count(): int {
		$total = 0;
		
		foreach ( $this->pathList as $bucket ) {
			$total += $bucket->countSessions();
		}
		
		return $total;
	}
}
