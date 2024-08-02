<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use InvalidArgumentException;
use RuntimeException;

/**
 * Factory to return an object implementing the MobaXterm\SessionType interface for a given session type
 */
class SessionSettingsFactory {
	private static array $classTemplate_cache = [];
	
	public static function create( string $sessionType ): SessionType {
		if ( empty( $sessionType ) ) {
			throw new InvalidArgumentException(
				'The first parameter should be a string containing the session type.<br>'
			);
		}
		
		// If there's already a cached template for this session type, we clone it
		if ( isset( self::$classTemplate_cache[ $sessionType ] ) ) {
			return clone self::$classTemplate_cache[ $sessionType ];
		}
		
		// If not, we create it
		if ( ! file_exists( dirname( __DIR__ ) . '/MobaXterm/' . $sessionType . '.php' ) ) {
			throw new RuntimeException( "No class found for session $sessionType!<br>" );
		}
		
		$className = 'Ruzgfpegk\\Sessionator\\Formats\\MobaXterm\\' . $sessionType;
		
		self::$classTemplate_cache[ $sessionType ] = new $className;
		
		return clone self::$classTemplate_cache[ $sessionType ];
	}
}
