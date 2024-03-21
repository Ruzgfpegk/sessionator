<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use ReflectionClass;
use ReflectionException;

use Ruzgfpegk\Sessionator\Formats\CommonOutput;
use Ruzgfpegk\Sessionator\Formats\FormatOutput;

/**
 * The Formats\MobaXterm\Output class defines the global .mxtsessions file format
 */
class Output extends CommonOutput implements FormatOutput {
	/**
	 * Returns in an array the contents of the final .mxtsessions file
	 *
	 * @param array $sessionList
	 *
	 * @return array
	 * @throws ReflectionException
	 */
	public function getAsText( array $sessionList ): array {
		$output      = [];
		$folderCount = 1;
		$folderSeen  = []; // Associative array of already-created folders
		
		// Export header
		$output[] = '[Bookmarks]';
		$output[] = 'SubRep=';
		$output[] = 'ImgNum=42';
		$output[] = '';
		
		// Sort folder names alphabetically (natural sort: numbers going 1, 9, 10, ...)
		ksort( $sessionList, SORT_NATURAL );
		
		foreach ( $sessionList as $sessionFolder => $sessionNames ) {
			// For each depth of the folder path, create intermediates if they haven't been already
			$folders = explode( '\\', $sessionFolder );
			
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
			foreach ( $sessionNames as $sessionName => $sessionDetails ) {
				// Getting the session type from its class name
				$sessionType = ( new ReflectionClass( $sessionDetails ) )->getShortName();
				
				// Initializing session type settings
				$sessionSettings = SessionSettingsFactory::create( $sessionType );
				
				// Registering custom settings
				$sessionSettings->applyParams( $sessionDetails );
				
				// Initializing terminal settings
				$terminalSettings = new TerminalSettings();
				
				// Registering custom settings
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
				$sessionTabColor = - 1;
				
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
	public function downloadAsFile( array $sessionList, string $lineSeparator = "\r\n"): void {
		$outputFile = '';
		
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			$outputFile .= $this->convertEncoding( $sessionLine ) . $lineSeparator;
		}
		
		header( 'Content-type: text/plain' );
		header( 'Content-Disposition: attachment; filename="ExportedSession.mxtsessions"' );
		echo $outputFile;
	}
	
	/**
	 * @inheritDoc
	 */
	public function saveAsFile( array $sessionList, string $fileName, string $lineSeparator = "\r\n"): void {
		$outputFile = '';
		
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			$outputFile .= $this->convertEncoding( $sessionLine ) . $lineSeparator;
		}
		
		file_put_contents( $fileName, $outputFile );
	}
	
	/**
	 * .mxtsessions files are CRLF files in Windows-1252 encoding, so a conversion must be made
	 *
	 * @param string $string The whole contents of the .mxtsessions file as one string
	 *
	 * @return string The input string converted to the correct encoding
	 */
	public function convertEncoding( string $string ): string {
		return mb_convert_encoding( $string, 'Windows-1252', 'UTF-8' );
	}
}
