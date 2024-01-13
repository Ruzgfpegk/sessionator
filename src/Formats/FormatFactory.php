<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats;

use InvalidArgumentException;
use RuntimeException;

/**
 * Factory to return an object implementing the Formats\FormatOutput interface for a given format
 */
class FormatFactory {
	public static function createOutput( string $outputType ): FormatOutput {
		if ( empty( $outputType ) ) {
			throw new InvalidArgumentException(
				'The first parameter should be a string containing the output format.<br>'
			);
		}
		
		if ( ! file_exists( dirname( __DIR__ ) . '/Formats/' . $outputType . '/Output.php' ) ) {
			throw new RuntimeException( "No class found for format $outputType!<br>" );
		}
		
		$outputFullType = 'Ruzgfpegk\\Sessionator\\Formats\\' . $outputType . '\\Output';
		
		return new $outputFullType();
	}
}
