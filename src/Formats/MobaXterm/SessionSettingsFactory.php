<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use InvalidArgumentException;
use RuntimeException;

/**
 * Factory to return an object implementing the MobaXterm\SessionType interface for a given connection type
 */
class SessionSettingsFactory {
	private static array $cache = [];
	
	public static function create( string $sessionType ): SessionType {
		if ( empty( $sessionType ) ) {
			throw new InvalidArgumentException(
				'The first parameter should be a string containing the session type.<br>'
			);
		}
		
		if ( isset( self::$cache[ $sessionType ] ) ) {
			return new self::$cache[ $sessionType ];
		}
		
		if ( ! file_exists( dirname( __DIR__ ) . '/MobaXterm/' . $sessionType . '.php' ) ) {
			throw new RuntimeException( "No class found for session $sessionType!<br>" );
		}
		
		$className = 'Ruzgfpegk\\Sessionator\\Formats\\MobaXterm\\' . $sessionType;
		
		self::$cache[ $sessionType ] = $className;
		
		return new self::$cache[ $sessionType ];
	}
}
