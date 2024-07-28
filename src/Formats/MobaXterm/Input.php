<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Sessions\Session;
use Ruzgfpegk\Sessionator\Sessions\SessionFactory;
use Ruzgfpegk\Sessionator\Formats\CommonInput;
use Ruzgfpegk\Sessionator\Formats\FormatInput;

/**
 * The Formats\MobaXterm\Input class defines the global .mxtsessions file format for import
 */
class Input extends CommonInput implements FormatInput {
	private const SESSION_TYPES = [
		'0'  => 'SSH',
		'4'  => 'RDP',
		'5'  => 'VNC',
		'7'  => 'SFTP',
		'11' => 'Browser',
	];
	
	/**
	 * @param string $fileName
	 *
	 * @return array List of "Session" objects
	 */
	public function importFromFile( string $fileName ): array {
		$importedSessionsList = [];
		
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
			
			foreach ( $mobaFileSection as $sessionName => $sessionSettingsString ) {
				$session = $this->decodeIndividualSession( $sessionSettingsString );
				// Finish setting up the elements with the info only this function has
				$session->setFolderName( $folderName );
				$session->setSessionName( $sessionName );
				// TODO one day: set folder image function
				$importedSessionsList[] = $session;
			}
		}
		
		return $importedSessionsList;
	}
	
	private function decodeIndividualSession( string $sessionSettingsString ): Session {
		// Split the string into parts using "#" as separator
		[
			$reconnectionSetting, // TODO
			$sessionIcon,
			$sessionTypeSettings,
			$sessionTerminalSettings,
			$sessionTabSetting, // TODO
			$sessionComment,
			$sessionTabColor // TODO
		] = explode( '#', $sessionSettingsString );
		
		// Prepare the global "Session" part
		$firstSeparatorPos = strpos( $sessionTypeSettings, '%' );
		$sessionTypeNum    = substr( $sessionTypeSettings, 0, $firstSeparatorPos );
		$sessionType       = self::SESSION_TYPES[ $sessionTypeNum ];
		$session         = SessionFactory::create( $sessionType );
		
		// Set up the "Session Type" part
		$decodedSessionTypeSettings = SessionSettingsFactory::create( $sessionType )->decodeFromString( $sessionTypeSettings );
		foreach ( $decodedSessionTypeSettings as $decodedSetting => $value ) {
			$session->setSessionParam( $decodedSetting, $value );
		}
		
		// Set up the "Terminal" part
		$decodedSettings = ( new TerminalSettings() )->decodeFromString( $sessionTerminalSettings );
		foreach ( $decodedSettings as $decodedSetting => $value ) {
			$session->setSessionParam( $decodedSetting, $value );
		}
		
		// Set up the basic info (folder and session name are set up by the importFromFile caller function)
		$session->setSessionIcon( ( new SessionIcon() )->getIconName( $sessionIcon ) );
		$session->setSessionComment( $sessionComment );
		$session->setHostName( $decodedSessionTypeSettings['remoteHost'] );
		# TODO
		
		return $session;
	}
}
