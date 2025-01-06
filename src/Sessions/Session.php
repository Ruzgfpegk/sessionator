<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Sessions;

use Ruzgfpegk\Sessionator\Sessionator;

/**
 * Interface to define a common basis to all session types
 */
interface Session {
	public function setSessionList( Sessionator $sessionList ): void;
	
	public function getPathName(): string;
	public function setPathName( string $pathName ): Session;
	
	/** @deprecated */
	public function getFolderName(): string;
	/** @deprecated */
	public function setFolderName( string $folderName ): Session;
	
	public function getFolderIcon(): string;
	public function setFolderIcon( string $folderIcon ): Session;
	
	public function getSessionName(): string;
	public function setSessionName( string $sessionName ): Session;
	
	public function getSessionIcon(): string;
	public function setSessionIcon( string $sessionIcon ): Session;
	
	public function getSessionComment(): string;
	public function setSessionComment( string $sessionComment ): Session;
	
	public function getHostName(): string;
	public function setHostName( string $hostName ): Session;
	
	public function getUserName(): string;
	
	public function getSessionParam( string $paramName );
	public function setSessionParam( string $paramName, string $paramValue ): Session;
	
	public function getSessionParams(): array;
	
	public function getImportFormat(): string;
	public function setImportFormat( string $importFormat ): Session;
	
	public function addToList(): void;
}
