<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use ReflectionClass;
use ReflectionException;

use Ruzgfpegk\Sessionator\Internals\SessionList;
use Ruzgfpegk\Sessionator\Formats\CommonOutput;
use Ruzgfpegk\Sessionator\Formats\FormatOutput;

/**
 * The Formats\MobaXterm\Output class defines the global .mxtsessions file format for export
 */
class Output extends CommonOutput implements FormatOutput {
	
	/**
	 * @inheritDoc
	 *
	 * @throws ReflectionException
	 */
	public function getAsText( SessionList $sessionList ): array {
		$output      = [];
		$folderCount = 1;
		$folderSeen  = []; // Associative array of already-created folders
		
		// Export header
		$output[] = '[Bookmarks]';
		$output[] = 'SubRep=';
		$output[] = 'ImgNum=42';
		$output[] = '';
		
		$sessionList->sort();
		
		foreach ( $sessionList->getPathList() as $sessionPath ) {
			// For each depth of the folder path, create intermediates if they haven't been already
			$folders = explode( '\\', $sessionPath );
			
			for ( $i = 1, $iMax = count( $folders ); $i <= $iMax; $i++ ) {
				$nameAtCurrentDepth = implode( '\\', array_slice( $folders, 0, $i ) );
				
				if ( ! array_key_exists( $nameAtCurrentDepth, $folderSeen ) ) {
					// Intermediate folder declaration
					$output[] = "[Bookmarks_$folderCount]";
					$output[] = 'SubRep=' . $nameAtCurrentDepth;
					$output[] = 'ImgNum=41';
					
					if ( $i < $iMax ) {
						$output[] = '';
					}
					
					$folderCount++;
					$folderSeen[ $nameAtCurrentDepth ] = true;
				}
			}
			
			// Folder sessions
			foreach ( $sessionList->getPathSessions( $sessionPath ) as $sessionDetails ) {
				// Getting the session name from the object (for non-array session lists)
				$sessionName = $sessionDetails->getSessionName();
				
				// Getting the session type from its class name
				$sessionType = ( new ReflectionClass( $sessionDetails ) )->getShortName();
				
				// Initializing session type settings
				$sessionSettings = SessionSettingsFactory::create( $sessionType );
				
				// Registering custom settings
				$sessionSettings->applyParams( $sessionDetails );
				
				// Initializing terminal settings
				$terminalSettings = SessionSettingsFactory::create( 'TerminalSettings' );
				
				// Registering custom terminal settings
				$terminalSettings->applyParams( $sessionDetails );
				
				// Getting the "display reconnection message" setting TODO
				$sessionReconnection = '';
				
				// Getting the right icon
				$sessionDetailsIcon = $sessionDetails->getSessionIcon();
				$si                 = new SessionIcon;
				if ( $sessionDetailsIcon !== '' ) {
					$sessionIcon = $si->getIcon( $sessionDetailsIcon );
				} else {
					$sessionIcon = $si->getIcon( $sessionType );
				}
				
				// Getting the session settings string
				$sessionSettingsString = $sessionSettings->getString();
				
				// Getting the terminal settings string
				$terminalSettingsString = $terminalSettings->getString();
				
				// Getting the session opening method TODO
				$sessionOpening = 0;
				
				// Getting the comment
				$sessionDetailsComment = $sessionDetails->getSessionComment();
				if ( $sessionDetailsComment !== '' ) {
					$sessionDetailsComment = str_replace( '#', '__DIEZE__', $sessionDetailsComment );
				}
				$sessionComment = $sessionDetailsComment;
				
				// Getting the session tab color TODO
				$sessionTabColor = -1;
				
				$output[] = $sessionName
				            . '=' . $sessionReconnection
				            . '#' . $sessionIcon
				            . '#' . $sessionSettingsString
				            . '#' . $terminalSettingsString
				            . '#' . $sessionOpening
				            . '#' . $sessionComment
				            . '#' . $sessionTabColor;
			}
			
			$output[] = '';
		}
		
		return $output;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getAsFile( SessionList $sessionList ): string {
		$outputFile = '';
		
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			$outputFile .= $sessionLine . $this->lineSeparator;
		}
		
		// .mxtsessions files are CRLF files in Windows-1252 encoding, so a conversion must be made
		return mb_convert_encoding( $outputFile, 'Windows-1252', 'UTF-8' );
	}
	
	/**
	 * @inheritDoc
	 */
	public function downloadAsFile( SessionList $sessionList, string $fileName = 'ExportedSession.mxtsessions' ): void {
		$outputFile = $this->getAsFile( $sessionList );
		
		header( 'Content-type: ' . $this->contentType );
		header( 'Content-Disposition: attachment; filename="' . $fileName . '"' );
		echo $outputFile;
	}
	
	/**
	 * @inheritDoc
	 */
	public function saveAsFile( SessionList $sessionList, string $fileName ): void {
		$outputFile = $this->getAsFile( $sessionList );
		
		file_put_contents( $fileName, $outputFile );
	}
}
