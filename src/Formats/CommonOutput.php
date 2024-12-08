<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats;

use Ruzgfpegk\Sessionator\Internals\SessionList;

/**
 * The Formats\CommonOutput class is the basis upon which all classes for the various outputs (MobaXterm, ...) are built
 */
abstract class CommonOutput {
	protected string $contentType = 'text/plain';
	protected string $lineSeparator = "\r\n";
	
	public function getAsFile( SessionList $sessionList ): string {
		$outputFile = '';
		
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			$outputFile .= $sessionLine . $this->lineSeparator;
		}
		
		return $outputFile;
	}
	
	public function displayAsText( SessionList $sessionList ): void {
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			echo $sessionLine . PHP_EOL;
		}
	}
	
	public function displayAsHtml( SessionList $sessionList ): void {
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			echo $sessionLine . '<br>' . PHP_EOL;
		}
	}
	
	public function downloadAsFile( SessionList $sessionList, string $fileName = 'export' ): void {
		$outputFile = $this->getAsFile( $sessionList );
		
		header( 'Content-type: ' . $this->contentType );
		header( 'Content-Disposition: attachment; filename="' . $fileName . '"' );
		echo $outputFile;
	}
	
	public function saveAsFile( SessionList $sessionList, string $fileName ): void {
		$outputFile = $this->getAsFile( $sessionList );
		
		file_put_contents( $fileName, $outputFile );
	}
}
