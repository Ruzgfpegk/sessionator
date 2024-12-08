<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\Bash;

use ReflectionClass;
use ReflectionException;

use Ruzgfpegk\Sessionator\Internals\SessionList;
use Ruzgfpegk\Sessionator\Formats\CommonOutput;
use Ruzgfpegk\Sessionator\Formats\FormatOutput;

/**
 * The Formats\Bash\Output class defines how to generate a self-contained .sh file for export
 */
class Output extends CommonOutput implements FormatOutput {
	
	public function __construct() {
		$this->lineSeparator = "\n";
	}
	
	/**
	 * @inheritDoc
	 *
	 * @throws ReflectionException
	 */
	public function getAsText( SessionList $sessionList ): array {
		$output = [];
		
		$startMarker = "## START_SERVERS ##";
		$endMarker   = "## END_SERVERS ##";
		
		$scriptContent = file( __DIR__ . '/Connector.sh', FILE_IGNORE_NEW_LINES );
		
		$lineNumber = 0;
		$totalLines = count( $scriptContent );
		
		$sessionList->sort();
		
		// Copy all lines until the start marker (including it)
		for ( ; $lineNumber < $totalLines; $lineNumber++ ) {
			if ( strpos( $scriptContent[ $lineNumber ], $startMarker ) === false ) {
				$output[] = $scriptContent[ $lineNumber ];
			} else {
				$output[] = $scriptContent[ $lineNumber ];
				break;
			}
		}
		
		// Export all the servers from the session list : FOLDER NAMES ARE IGNORED HERE!
		foreach ( $sessionList->getPathList() as $sessionPath ) {
			foreach ( $sessionList->getPathSessions( $sessionPath ) as $sessionDetails ) {
				// Getting the session name from the object (for non-array session lists)
				$sessionName = $sessionDetails->getSessionName();
				
				// Getting the session type from its class name
				$sessionType = ( new ReflectionClass( $sessionDetails ) )->getShortName();
				
				// Initializing session type settings
				$sessionSettings = SessionSettingsFactory::create( $sessionType );
				
				// Skip the entry if it's of an unsupported type
				if ( ! $sessionSettings ) {
					continue;
				}
				
				// Registering custom settings
				$sessionSettings->applyParams( $sessionDetails );
				
				// Getting the session settings associative array
				$sessionSettings = $sessionSettings->getString();
				
				// Getting the comment
				$sessionComment = $sessionDetails->getSessionComment();

				$finalLine = 'populate_server_info_' . strtolower($sessionType) . ' '
				             . '"' . $sessionName . '"'
				             . ' ' . $sessionSettings;
				
				if ( $sessionComment !== '' ) {
					$finalLine .= ' # ' . $sessionComment;
				}
				
				$output[] = $finalLine;
			}
		}
		
		// Locate the end marker
		for ( ; $lineNumber < $totalLines; $lineNumber++ ) {
			if ( strpos( $scriptContent[ $lineNumber ], $endMarker ) !== false ) {
				break;
			}
		}
		
		// Copy all lines from the end marker
		for ( ; $lineNumber < $totalLines; $lineNumber++ ) {
			$output[] = $scriptContent[ $lineNumber ];
		}
		
		return $output;
	}
}


