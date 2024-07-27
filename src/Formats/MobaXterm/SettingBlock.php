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
	
	// This is populated by the devs in the extending classes
	public array $booleans = [];
	
	/**
	 * Concatenates all the elements from the configuration subsection
	 *
	 * @return string
	 */
	public function getString(): string {
		$final_settings = [];
		
		// This supposes that no index has been skipped in the declaration
		foreach ( $this->settings as $setting ) {
			$final_settings[ $setting[0] ] = $setting[1];
		}
		
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
				if ( $sessionValue === 'Enabled' ) {
					$sessionValue = self::ENABLED;
				} elseif ( $sessionValue === 'Disabled' ) {
					$sessionValue = self::DISABLED;
				}
				
				$this->settings[ $sessionParam ][1] = $sessionValue;
			}
		}
	}
}
