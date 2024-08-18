<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\Bash;

use Ruzgfpegk\Sessionator\Sessions\Session;

/**
 * This class describes the common elements of setting lines for the Bash script output.
 */
abstract class SettingLine {
	public const ENABLED = '1';
	public const DISABLED = '0';
	
	protected array $settings = [];
	
	/**
	 * Concatenates all the elements from the configuration subsection
	 *
	 * @return string
	 */
	public function getString(): string {
		// This supposes that no index has been skipped in the declaration
		$final_settings = array_column( $this->settings, 1, 0 );
		
		return '"' . implode( '" "', $final_settings ) . '"';
	}
	
	/**
	 * Registers all parameters set by "->setSessionParam( 'paramName', 'paramValue' )"
	 *
	 * @param Session $sessionDetails
	 *
	 * @return void
	 */
	public function applyParams( Session $sessionDetails ): void {
		$importFormat = $sessionDetails->getImportFormat();
		
		foreach ( $sessionDetails->getSessionParams() as $sessionParam => $sessionValue ) {
			if ( array_key_exists( $sessionParam, $this->settings ) ) {
				// Compare the value against the default one from the import format vs this format
				if ( $importFormat !== '' && $importFormat !== 'Bash' ) {
					// TODO: Implement this, with a cached default for each import format met
				}
				
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
