<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats;

/**
 * The Formats\CommonOutput class is the basis upon which all classes for the various outputs (MobaXterm, ...) are built
 */
abstract class CommonOutput {
	protected string $contentType = 'text/plain';
	protected string $lineSeparator = "\r\n";
	
	public function getAsFile( array $sessionList ): string {
		$outputFile = '';
		
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			$outputFile .= $sessionLine . $this->lineSeparator;
		}
		
		return $outputFile;
	}
	
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
	
	public function downloadAsFile( array $sessionList, string $fileName = 'export' ): void {
		$outputFile = $this->getAsFile( $sessionList );
		
		header( 'Content-type: ' . $this->contentType );
		header( 'Content-Disposition: attachment; filename="' . $fileName . '"' );
		echo $outputFile;
	}
	
	public function saveAsFile( array $sessionList, string $fileName ): void {
		$outputFile = $this->getAsFile( $sessionList );
		
		file_put_contents( $fileName, $outputFile );
	}
}
