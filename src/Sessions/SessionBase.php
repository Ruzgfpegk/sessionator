<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Sessions;

use Ruzgfpegk\Sessionator\Sessionator;

/**
 * The Sessions\Common class is the basis upon which all classes for various sessions (SSH, RDP, ...) are built
 */
abstract class SessionBase implements Session {
	/**
	 * @var Sessionator The "manager" object (beware of recursion!)
	 */
	private Sessionator $sessionList;
	
	private string $pathName = '';
	
	private string $folderIcon = '';
	
	private string $sessionName;
	
	private string $sessionIcon = '';
	
	private string $sessionComment = '';
	
	private string $hostName;
	
	private string $importFormat = '';
	
	/**
	 * @var array<string, mixed> Session parameters
	 */
	private array $sessionParams = [];
	
	/**
	 * Saves a link to the caller Sessionator object to save the current session into it later
	 *
	 * @param $sessionList Sessionator The caller Sessionator object
	 *
	 * @return void
	 */
	public function setSessionList( Sessionator $sessionList ): void {
		$this->sessionList = $sessionList;
	}
	
	public function getPathName(): string {
		return $this->pathName;
	}
	
	/** @deprecated */
	public function getFolderName(): string {
		return $this->getPathName();
	}
	
	public function setPathName( string $pathName ): Session {
		$this->pathName = $pathName;
		
		return $this;
	}
	
	/** @deprecated */
	public function setFolderName( string $folderName ): Session {
		$this->setPathName( $folderName );
		
		return $this;
	}
	
	public function getFolderIcon(): string {
		return $this->folderIcon;
	}
	
	public function setFolderIcon( string $folderIcon ): Session {
		$this->folderIcon = $folderIcon;
		
		return $this;
	}
	
	public function getSessionName(): string {
		return $this->sessionName;
	}
	
	public function setSessionName( string $sessionName ): Session {
		$this->sessionName = $sessionName;
		
		return $this;
	}
	
	public function getSessionIcon(): string {
		return $this->sessionIcon;
	}
	
	public function setSessionIcon( string $sessionIcon ): Session {
		$this->sessionIcon = $sessionIcon;
		
		return $this;
	}
	
	public function getSessionComment(): string {
		return $this->sessionComment;
	}
	
	public function setSessionComment( string $sessionComment ): Session {
		$this->sessionComment = $sessionComment;
		
		return $this;
	}
	
	public function getHostName(): string {
		return $this->hostName;
	}
	
	public function setHostName( string $hostName ): Session {
		$this->hostName = $hostName;
		
		return $this;
	}
	
	/**
	 * Some formats need to get a value even if nothing's set, the UserName trait provides a real getter
	 *
	 * @return string
	 */
	public function getUserName(): string {
		return '';
	}
	
	/**
	 * Returns the value of a setting that got added using setSessionParam()
	 *
	 * @param string $paramName Name of the parameter to retrieve
	 *
	 * @return mixed The value of the parameter if found, or an empty string otherwise
	 */
	public function getSessionParam( string $paramName ): mixed {
		return $this->sessionParams[$paramName] ?? '';
	}
	
	/**
	 * Sets a singular parameter to a given value
	 *
	 * @param string $paramName Parameter name (see Settings.md)
	 * @param string $paramValue Value to set the parameter to
	 *
	 * @return Session Returns the Session-implementing object for chaining
	 */
	public function setSessionParam( string $paramName, string $paramValue ): Session {
		$this->sessionParams[ $paramName ] = $paramValue;
		
		return $this;
	}
	
	public function getSessionParams(): array {
		return $this->sessionParams;
	}
	
	public function getImportFormat(): string {
		return $this->importFormat;
	}
	
	public function setImportFormat( string $importFormat ): Session {
		$this->importFormat = $importFormat;
		
		return $this;
	}
	
	public function addToList(): void {
		$this->sessionList->addToList( $this );
		// Once we're done we don't need the sessionList property any more, plus it's dangerous as keeping it risks recursion.
		unset( $this->sessionList );
	}
}
