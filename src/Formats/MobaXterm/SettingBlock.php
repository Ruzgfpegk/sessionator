<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Sessions\Session;

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
	
	// This is populated dynamically and on-demand in buildFromString() under the extending classes
	protected static array $reversedSettings  = [];
	
	// This is populated by the devs in the extending classes
	public array $booleans = [];
	
	/**
	 * Concatenates all the elements from the configuration subsection
	 *
	 * @return string
	 */
	public function getString(): string {
		// This supposes that no index has been skipped in the declaration
		$final_settings = array_column( $this->settings, 1, 0 );
		
		return implode( '%', $final_settings );
	}
	
	/**
	 * Registers all parameters set by "->setSessionParam( 'paramName', 'paramValue' )"
	 *
	 * @param Session $sessionDetails
	 *
	 * @return void
	 */
	public function applyParams( Session $sessionDetails ): void {
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
	
	protected function reverseSettings(): void {
		// Get the current object name
		$className = get_class( $this );
		
		// This is intended to be called only from the extending classes so that $className matches them
		if ( empty( self::$reversedSettings[ $className ] ) ) {
			// Build and cache the reverse mapping of $this->settings
			foreach ( $this->settings as $setting => $indexAndDefault ) {
				self::$reversedSettings[ $className ][ $indexAndDefault[0] ] = $setting;
			}
		}
	}
	
	protected function reverseMapping( string $sessionSettings ): array {
		$this->reverseSettings();
		
		$settingsFinal = [];
		$className     = get_class( $this );
		$settingsArray = explode( '%', $sessionSettings );
		
		foreach ( self::$reversedSettings[ $className ] as $index => $settingName ) {
			// Only import the settings that differ from their default values
			if ( $settingsArray[ $index ] !== $this->settings[ $settingName ][1] ) {
				$settingsFinal[ $settingName ] = $settingsArray[ $index ];
				
				// Transform the changed settings that are booleans
				if ( in_array( $settingName, $this->booleans, true ) ) {
					if ( $settingsFinal[ $settingName ] === self::ENABLED ) {
						$settingsFinal[ $settingName ] = 'Enabled';
					} else {
						$settingsFinal[ $settingName ] = 'Disabled';
					}
				}
			}
		}
		
		return $settingsFinal;
	}
}
