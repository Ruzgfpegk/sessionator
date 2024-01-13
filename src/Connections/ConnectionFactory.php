<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Connections;

use InvalidArgumentException;
use RuntimeException;

/**
 * Factory to return an object implementing the Connection interface for a given connection type
 */
class ConnectionFactory {
	public static function create( string $connectionType ): Connection {
		if ( empty( $connectionType ) ) {
			throw new InvalidArgumentException(
				'The first parameter should be a string containing the connection type.<br>'
			);
		}
		
		if ( ! file_exists( dirname( __DIR__ ) . '/Connections/' . $connectionType . '.php' ) ) {
			throw new RuntimeException( "No class found for connection $connectionType!<br>" );
		}
		
		$connectionFullType = 'Ruzgfpegk\\Sessionator\\Connections\\' . $connectionType;
		
		return new $connectionFullType();
	}
}
