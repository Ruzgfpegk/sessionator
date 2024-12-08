<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator;

use RuntimeException;

use Ruzgfpegk\Sessionator\Internals\SessionList;
use Ruzgfpegk\Sessionator\Sessions\Session;
use Ruzgfpegk\Sessionator\Sessions\SessionBase;
use Ruzgfpegk\Sessionator\Sessions\SessionFactory;
use Ruzgfpegk\Sessionator\Formats\FormatFactory;

/**
 * Main class of the library
 */
class Sessionator {
	// Class defaults
	private SessionList $sessionList;
	private string $sessionListType;
	
	/** Runtime setup */
	public function __construct() {
		if ( extension_loaded( 'ds' ) ) {
			$this->sessionList = new SessionList( 'Ds' );
		} else {
			$this->sessionList = new SessionList( 'array' ); // array or SPL should be available
		}
	}
	
	public function getSessionStorageType(): string {
		return $this->sessionList->getSessionStorageType();
	}
	
	/**
	 * Fetches an object for the session type and links its session list to the caller Sessionator object
	 *
	 * @param $sessionType string The type of session to create
	 *
	 * @return Session
	 */
	public function newSession( string $sessionType ): Session {
		$session = SessionFactory::create( $sessionType );
		$session->setSessionList( $this );
		
		return $session;
	}
	
	/**
	 * A temporary alias for newSession(), as newConnection() was its previous name
	 *
	 * @param $sessionType string The type of session to create
	 *
	 * @return Session
	 */
	public function newConnection( string $sessionType ): Session {
		return $this->newSession( $sessionType );
	}
	
	/**
	 * Use an already existing session as a reference to create a new one
	 *
	 * @param string $pathName The path name where the existing session is stored
	 * @param string $sessionName The name of the existing session to clone
	 *
	 * @return Session
	 */
	public function importFromSession( string $pathName, string $sessionName ): Session {
		if ( $this->sessionList->pathExists( $pathName ) && $this->sessionList->pathList[ $pathName ]->hasSession( $sessionName ) ) {
			$clonedSession = clone $this->sessionList->pathList[ $pathName ]->getSession( $sessionName );
			$clonedSession->setSessionName( $sessionName . '_Clone' );
			$clonedSession->setSessionList( $this );
			
			return $clonedSession;
		}
		
		throw new RuntimeException( 'The session ' . $pathName . '\\' . $sessionName . ' does not exist' );
	}
	
	/**
	 * Registers the object for the session in the main Sessionator object
	 * Called by Sessions\SessionBase::addToList() through its sessionList property set in Sessionator::newSession()
	 *
	 * @param $session SessionBase
	 *
	 * @return void
	 */
	public function addToList( SessionBase $session ): void {
		$this->sessionList->add( $session );
	}
	
	/**
	 * Displays in text form (ex: console output) the session file for the specified format (MobaXterm, ...)
	 *
	 * @param $formatType string The output format for which to show a text output
	 *
	 * @return void
	 */
	public function exportAsText( string $formatType ): void {
		$outputFormat = FormatFactory::createOutput( $formatType );
		$outputFormat->displayAsText( $this->sessionList );
	}
	
	/**
	 * Displays in HTML form (ex: web output) the session file for the specified format (MobaXterm, ...)
	 *
	 * @param $formatType string The output format for which to show an HTML output
	 *
	 * @return void
	 */
	public function exportAsHtml( string $formatType ): void {
		$outputFormat = FormatFactory::createOutput( $formatType );
		$outputFormat->displayAsHtml( $this->sessionList );
	}
	
	/**
	 * Download the session list as a file in the specified format
	 *
	 * @param $formatType string The output format for which to download a session file
	 * @param $fileName string The name of the file to save the session list to
	 *
	 * @return void
	 */
	public function download( string $formatType, string $fileName ): void {
		$outputFormat = FormatFactory::createOutput( $formatType );
		
		$outputFormat->downloadAsFile( $this->sessionList, $fileName );
	}
	
	/**
	 * Load sessions from a file in the specified format
	 *
	 * @param string $fileName The name of the file to load the session list from
	 * @param string $formatType The input format for which to load a session file
	 *
	 * @return void
	 */
	public function importFromFile( string $fileName, string $formatType ): void {
		$inputFormat      = FormatFactory::createInput( $formatType );
		$importedSessions = $inputFormat->importFromFile( $fileName );
		
		foreach ( $importedSessions as $importedSession ) {
			$this->addToList( $importedSession );
		}
	}
	
	/**
	 * Save the session list as a file in the specified format
	 *
	 * @param string $formatType The output format for which to save a session file
	 * @param string $fileName The name of the file to save the session list to
	 *
	 * @return void
	 */
	public function saveAsFile( string $formatType, string $fileName ): void {
		$outputFormat = FormatFactory::createOutput( $formatType );
		$outputFormat->saveAsFile( $this->sessionList, $fileName );
	}
}
