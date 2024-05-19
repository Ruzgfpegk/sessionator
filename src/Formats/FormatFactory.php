<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats;

use InvalidArgumentException;
use RuntimeException;

/**
 * Factory to return an object implementing the Formats\FormatOutput interface for a given format
 */
class FormatFactory {
	private static array $cache = [];
	
	public static function createOutput( string $outputType ): FormatOutput {
		if ( empty( $outputType ) ) {
			throw new InvalidArgumentException(
				'The first parameter should be a string containing the output format.<br>'
			);
		}
		
		if ( isset( self::$cache[ $outputType ] ) ) {
			return new self::$cache[ $outputType ];
		}
		
		if ( ! file_exists( dirname( __DIR__ ) . '/Formats/' . $outputType . '/Output.php' ) ) {
			throw new RuntimeException( "No class found for format $outputType!<br>" );
		}
		
		$className = 'Ruzgfpegk\\Sessionator\\Formats\\' . $outputType . '\\Output';
		
		self::$cache[ $outputType ] = $className;
		
		return new self::$cache[ $outputType ];
	}
}
