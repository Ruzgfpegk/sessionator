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
	
	public static function createInput( string $formatType ): FormatInput {
		return self::createIo( $formatType, 'Input' );
	}
	
	public static function createOutput( string $formatType ): FormatOutput {
		return self::createIo( $formatType, 'Output' );
	}
	
	/**
	 * @param string $formatName The format name
	 * @param string $ioType The format type ('Input' or 'Output')
	 *
	 * @return mixed
	 */
	public static function createIo( string $formatName, string $ioType = 'Output' ) {
		if ( empty( $formatName ) ) {
			throw new InvalidArgumentException(
				'The first parameter should be a string containing the I/O format.<br>'
			);
		}
		
		if ( empty( $ioType ) ) {
			throw new InvalidArgumentException(
				'The second parameter should be a string containing the I/O type.<br>'
			);
		}
		
		if ( isset( self::$cache[ $formatName ][ $ioType ] ) ) {
			return new self::$cache[ $formatName ][ $ioType ];
		}
		
		if ( ! file_exists( dirname( __DIR__ ) . '/Formats/' . $formatName . '/' . $ioType . '.php' ) ) {
			throw new RuntimeException( "No class found for format $formatName/$ioType!<br>" );
		}
		
		$className = 'Ruzgfpegk\\Sessionator\\Formats\\' . $formatName . '\\' . $ioType;
		
		self::$cache[ $formatName ][ $ioType ] = $className;
		
		return new self::$cache[ $formatName ][ $ioType ];
	}
}
