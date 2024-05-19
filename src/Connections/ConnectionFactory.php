<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Connections;

use InvalidArgumentException;
use RuntimeException;

/**
 * Factory to return an object implementing the Connection interface for a given connection type
 */
class ConnectionFactory {
	private static array $cache = [];
	
	public static function create( string $connectionType ): Connection {
		if ( empty( $connectionType ) ) {
			throw new InvalidArgumentException(
				'The first parameter should be a string containing the connection type.<br>'
			);
		}
		
		if ( isset( self::$cache[ $connectionType ] ) ) {
			return new self::$cache[ $connectionType ];
		}
		
		if ( ! file_exists( dirname( __DIR__ ) . '/Connections/' . $connectionType . '.php' ) ) {
			throw new RuntimeException( "No class found for connection $connectionType!<br>" );
		}
		
		$className = 'Ruzgfpegk\\Sessionator\\Connections\\' . $connectionType;
		
		self::$cache[ $connectionType ] = $className;
		
		return new self::$cache[ $connectionType ];
	}
}
