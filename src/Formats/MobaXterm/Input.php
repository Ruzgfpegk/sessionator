<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Connections\Connection;
use Ruzgfpegk\Sessionator\Connections\ConnectionFactory;
use Ruzgfpegk\Sessionator\Formats\CommonInput;
use Ruzgfpegk\Sessionator\Formats\FormatInput;

/**
 * The Formats\MobaXterm\Input class defines the global .mxtsessions file format for import
 */
class Input extends CommonInput implements FormatInput {
	private const CONNECTION_TYPES = [
		'0'  => 'SSH',
		'4'  => 'RDP',
		'5'  => 'VNC',
		'7'  => 'SFTP',
		'11' => 'Browser',
	];
	
	/**
	 * @param string $fileName
	 *
	 * @return array List of "Connection" objects
	 */
	public function importFromFile( string $fileName ): array {
		$importedConnectionsList = [];
		
		// Read the file
		$readFile = file_get_contents( $fileName );
		if ( ! $readFile ) {
			exit( 'Error: the file ' . $fileName . ' doesn\'t exist.' . PHP_EOL );
		}
		
		// Convert its codepage back
		$readFile = mb_convert_encoding( $readFile, 'UTF-8', 'Windows-1252' );
		
		// Read as .ini
		$mobaFile = parse_ini_string( $readFile, true );
		if ( ! $mobaFile ) {
			exit( 'Error: the file ' . $fileName . ' is not a valid .ini file.' . PHP_EOL );
		}
		
		// Decode the file
		foreach ( $mobaFile as $mobaFileSection ) {
			$folderName  = $mobaFileSection['SubRep'];
			$folderImage = $mobaFileSection['ImgNum'];
			unset( $mobaFileSection['SubRep'], $mobaFileSection['ImgNum'] );
			
			foreach ( $mobaFileSection as $connectionName => $connectionSettingsString ) {
				$connection = $this->decodeIndividualConnection( $connectionSettingsString );
				// Finish setting up the elements with the info only this function has
				$connection->setFolderName( $folderName );
				$connection->setSessionName( $connectionName );
				// TODO one day: set folder image function
				$importedConnectionsList[] = $connection;
			}
		}
		
		return $importedConnectionsList;
	}
	
	private function decodeIndividualConnection( string $connectionSettingsString ): Connection {
		// Split the string into parts using "#" as separator
		[
			$reconnectionSetting, // TODO
			$sessionIcon,
			$sessionTypeSettings,
			$sessionTerminalSettings,
			$sessionTabSetting, // TODO
			$sessionComment,
			$sessionTabColor // TODO
		] = explode( '#', $connectionSettingsString );
		
		// Prepare the "Connection" part
		$firstSeparatorPos = strpos( $sessionTypeSettings, '%' );
		$sessionTypeNum    = substr( $sessionTypeSettings, 0, $firstSeparatorPos );
		$sessionType       = self::CONNECTION_TYPES[ $sessionTypeNum ];
		$connection        = ConnectionFactory::create( $sessionType );
		
		// Set up the "Session Type" part
		$decodedSessionTypeSettings = SessionSettingsFactory::create( $sessionType )->decodeFromString( $sessionTypeSettings );
		foreach ( $decodedSessionTypeSettings as $decodedSetting => $value ) {
			$connection->setSessionParam( $decodedSetting, $value );
		}
		
		// Set up the "Terminal" part
		$decodedSettings = ( new TerminalSettings() )->decodeFromString( $sessionTerminalSettings );
		foreach ( $decodedSettings as $decodedSetting => $value ) {
			$connection->setSessionParam( $decodedSetting, $value );
		}
		
		// Set up the basic info (folder and session name are set up by the importFromFile caller function)
		$connection->setSessionIcon( ( new SessionIcon() )->getIconName( $sessionIcon ) );
		$connection->setSessionComment( $sessionComment );
		$connection->setHostName( $decodedSessionTypeSettings['remoteHost'] );
		# TODO
		
		return $connection;
	}
}
