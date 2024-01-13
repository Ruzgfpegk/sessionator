<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

/**
 * The Formats\MobaXterm\Setting class gives a way to store individual settings for the .mxtsessions format
 */
class Setting {
	private int $index;
	
	private string $default;
	
	private string $value;
	
	/**
	 * Each setting is made up of its index in its parent config string (SSH, RDP, TerminalSettings, ...),
	 * its default value
	 * and a potential custom value set using ->setSessionParam( 'settingName', 'settingValue' )
	 *
	 * @param $index int
	 * @param $default string
	 * @param $value string
	 */
	public function __construct( int $index, string $default, string $value = '' ) {
		$this->index   = $index;
		$this->default = $default;
		$this->value   = $value;
	}
	
	public function getValue(): string {
		if ( $this->value !== '' ) {
			return $this->value;
		}
		
		return $this->default;
	}
	
	public function setValue( string $value ): void {
		$this->value = $value;
	}
	
	// Used at least by getString() in TerminalSettings to sort settings by index
	public function getIndex(): int {
		return $this->index;
	}
}
