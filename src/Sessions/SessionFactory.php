<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Sessions;

use InvalidArgumentException;
use RuntimeException;

/**
 * Factory to return an object implementing the Session interface for a given session type
 */
class SessionFactory {
	private static array $cache = [];
	
	public static function create( string $sessionType ): Session {
		if ( empty( $sessionType ) ) {
			throw new InvalidArgumentException(
				'The first parameter should be a string containing the session type.<br>'
			);
		}
		
		if ( isset( self::$cache[ $sessionType ] ) ) {
			return new self::$cache[ $sessionType ];
		}
		
		if ( ! file_exists( dirname( __DIR__ ) . '/Sessions/' . $sessionType . '.php' ) ) {
			throw new RuntimeException( "No class found for session $sessionType!<br>" );
		}
		
		$className = 'Ruzgfpegk\\Sessionator\\Sessions\\' . $sessionType;
		
		self::$cache[ $sessionType ] = $className;
		
		return new self::$cache[ $sessionType ];
	}
}
