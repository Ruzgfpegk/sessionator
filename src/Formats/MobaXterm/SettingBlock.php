<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Connections\Connection;

/**
 * This class describes the common elements of MobaXterm "configuration subsections".
 *
 * For instance:
 *  -> SSH sessions will combine classes "SSH" and "TerminalSettings"
 *  -> RDP sessions will combine classes "RDP" and "TerminalSettings"
 *
 * So these three classes have common traits that are inherited from here.
 */
abstract class SettingBlock {
	public const ENABLED = '-1';
	public const DISABLED = '0';
	
	protected array $settings = [];
	
	/**
	 * Concatenates all the elements from the configuration subsection
	 *
	 * @return string
	 */
	public function getString(): string {
		// Sort settings by their index property
		usort( $this->settings, static function ( $a, $b ) {
			return $a->getIndex() - $b->getIndex();
		} );
		
		// Extract all values (defaults if no manual setting)
		$final_settings = array_map( static function ( $setting ) {
			return $setting->getValue();
		}, $this->settings );
		
		return implode( '%', $final_settings );
	}
	
	
	/**
	 * Registers all parameters set by "->setSessionParam( 'paramName', 'paramValue' )"
	 *
	 * @param Connection $sessionDetails
	 *
	 * @return void
	 */
	public function applyParams( Connection $sessionDetails ): void {
		foreach ( $sessionDetails->getSessionParams() as $sessionParam => $sessionValue ) {
			if ( array_key_exists( $sessionParam, $this->settings ) ) {
				$this->settings[ $sessionParam ]->setValue( $sessionValue );
			}
		}
	}
}
