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
	
	public function saveAsFile( array $sessionList, string $fileName ): void {
		foreach ( $this->getAsText( $sessionList ) as $sessionLine ) {
			echo $sessionLine . PHP_EOL;
		}
	}
}
