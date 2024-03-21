<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats;

/**
 * The Formats\CommonOutput class is the basis upon which all classes for the various outputs (MobaXterm, ...) are built
 */
abstract class CommonOutput {
	public function displayAsText( array $sessionList ): void {
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			echo $sessionLine . PHP_EOL;
		}
	}
	
	public function displayAsHtml( array $sessionList ): void {
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			echo $sessionLine . '<br>' . PHP_EOL;
		}
	}
	
	public function downloadAsFile( array $sessionList, string $lineSeparator = "\r\n" ): void {
		$outputFile = '';
		
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			$outputFile .= $sessionLine . $lineSeparator;
		}
		
		header( 'Content-type: text/plain' );
		header( 'Content-Disposition: attachment; filename="ExportedSession.mxtsessions"' );
		echo $outputFile;
	}
	
	public function saveAsFile( array $sessionList, string $fileName, string $lineSeparator = "\r\n" ): void {
		$outputFile = '';
		
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			$outputFile .= $sessionLine . $lineSeparator;
		}
		
		file_put_contents( $fileName, $outputFile );
	}
}
