<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\Bash;

use InvalidArgumentException;

/**
 * Factory to return an object implementing the Bash\SessionType interface for a given session type
 */
class SessionSettingsFactory {
	private static array $classTemplate_cache = [];
	
	public static function create( string $sessionType ): ?SessionType {
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
		if ( ! file_exists( dirname( __DIR__ ) . '/Bash/' . $sessionType . '.php' ) ) {
			return null;
		}
		
		$className = 'Ruzgfpegk\\Sessionator\\Formats\\Bash\\' . $sessionType;
		
		self::$classTemplate_cache[ $sessionType ] = new $className;
		
		return clone self::$classTemplate_cache[ $sessionType ];
	}
}
