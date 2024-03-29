<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator;

use Ruzgfpegk\Sessionator\Connections\Connection;
use Ruzgfpegk\Sessionator\Connections\ConnectionFactory;
use Ruzgfpegk\Sessionator\Formats\FormatFactory;

/**
 * Main class of the library
 */
class Sessionator {
	// Class defaults
	
	/**
	 * First dimension: Folder name
	 * Second dimension: Session name
	 * Value: Object implementing the Connection interface and extending the Common abstract class
	 *
	 * @var array
	 */
	private array $sessionList = [];
	
	/**
	 * Fetches an object for the connection type and links its session list to the caller Sessionator object
	 *
	 * @param $connectionType string The type of connection to create
	 *
	 * @return Connection
	 */
	public function newConnection( string $connectionType ): Connection {
		$connection = ConnectionFactory::create( $connectionType );
		$connection->setSessionList( $this );
		
		return $connection;
	}
	
	/**
	 * Registers the object for the connection in the main Sessionator object
	 * Called by Connections\Common::addToList() through its sessionList property set in Sessionator::newConnection()
	 *
	 * @param $connection Connection
	 *
	 * @return void
	 */
	public function addToList( Connection $connection ): void {
		$this->sessionList[ $connection->getFolderName() ][ $connection->getSessionName() ] = $connection;
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
	 * @param $formatType string The output format for which to download a session file
	 *
	 * @return void
	 */
	public function download( string $formatType ): void {
		$outputFormat = FormatFactory::createOutput( $formatType );
		$outputFormat->downloadAsFile( $this->sessionList );
	}
	
	public function saveAsFile( string $formatType, string $fileName ): void {
		$outputFormat = FormatFactory::createOutput( $formatType );
		$outputFormat->saveAsFile( $this->sessionList, $fileName );
	}
}
