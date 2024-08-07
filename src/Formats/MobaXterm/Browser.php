<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Sessions\Session;

/**
 * The Formats\MobaXterm\Browser class defines the Browser part of the .mxtsessions format
 */
class Browser extends SettingBlock implements SessionType {
	
	public const IE_EMULATION = [
		'No emulation'         => '0',
		'Internet Explorer 7'  => '1',
		'Internet Explorer 8'  => '2',
		'Internet Explorer 9'  => '3',
		'Internet Explorer 10' => '4',
		'Internet Explorer 11' => '5',
	];
	
	public const BROWSER_ENGINE = [
		'Internet Explorer' => '0',
		'Google Chrome'     => '1',
		'Mozilla Firefox'   => '2',
		'Microsoft Edge'    => '3',
	];
	
	public const EDGE_PROXY_SETTING = [
		'System proxy settings'        => '0',
		'No proxy (direct connection)' => '1',
		'Autodetect proxy'             => '2',
		'Specify a proxy server'       => '3',
		'Specify a proxy script'       => '4',
	];
	
	public array $booleans = [
		'displayTopBar',
		'displayBackButton',
		'displayForwardButton',
		'displayRefreshButton',
		'displayStopButton',
		'displayHomeButton',
		'displayAddressBar',
		'browserExternalPopups',
		'edgeDisplayTopBar',
		'browserEnableContextMenus',
		'edgeExternalPopups',
		'browserEnableSmartScreen',
		'browserAllowInsecureLocalhost',
		'browserUseEdgeStoredPasswords'
	];
	
	private static array $reversedConstants = [];
	
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'sessionType'                   => [ 0, '11' ],
			'remoteHost'                    => [ 1, 'localhost' ], // Mandatory
			'displayTopBar'                 => [ 2, self::ENABLED ], // Gets copied to index 12 if Engine is Edge
			'displayBackButton'             => [ 3, self::ENABLED ],
			'displayForwardButton'          => [ 4, self::ENABLED ],
			'displayRefreshButton'          => [ 5, self::ENABLED ],
			'displayStopButton'             => [ 6, self::ENABLED ],
			'displayHomeButton'             => [ 7, self::ENABLED ],
			'displayAddressBar'             => [ 8, self::ENABLED ],
			'browserExternalPopups'         => [ 9, self::DISABLED ], // Gets copied to index 14 if Engine is Edge
			'browserIECompatibility'        => [ 10, self::IE_EMULATION['No emulation'] ],
			'browserEngine'                 => [ 11, self::BROWSER_ENGINE['Internet Explorer'] ],
			'edgeDisplayTopBar'             => [ 12, self::ENABLED ],  // Edge only
			'browserEnableContextMenus'     => [ 13, self::ENABLED ],  // Edge only
			'edgeExternalPopups'            => [ 14, self::DISABLED ], // Edge only
			'browserEnableSmartScreen'      => [ 15, self::ENABLED ],  // Edge only
			'browserAllowInsecureLocalhost' => [ 16, self::DISABLED ], // Edge only
			'browserUseEdgeStoredPasswords' => [ 17, self::ENABLED ],  // Edge only
			'browserProxy'                  => [ 18, self::EDGE_PROXY_SETTING['System proxy settings'] ], // Edge only
			'browserProxyServer'            => [ 19, '' ], // Edge only
			'browserProxyScript'            => [ 19, '' ], // Edge only, alias of the previous one, as both share the same space
			'browserProxyPort'              => [ 20, '' ], // Edge only
		];
	}
	
	private function reverseConstants(): void {
		if ( empty( self::$reversedConstants ) ) {
			// Build and cache the reverse mapping of public constants
			self::$reversedConstants['IE_EMULATION']       = array_flip( self::IE_EMULATION );
			self::$reversedConstants['BROWSER_ENGINE']     = array_flip( self::BROWSER_ENGINE );
			self::$reversedConstants['EDGE_PROXY_SETTING'] = array_flip( self::EDGE_PROXY_SETTING );
		}
	}
	
	public function decodeFromString( string $sessionSettings ): array {
		// Decode the settings
		$settingsFinal = $this->reverseMapping( $sessionSettings );
		
		// Decode the constants
		$this->reverseConstants();
		
		// De-transform the settings
		if ( array_key_exists( 'browserIECompatibility', $settingsFinal ) ) {
			$settingsFinal['browserIECompatibility'] = self::$reversedConstants['IE_EMULATION'][ $settingsFinal['browserIECompatibility'] ];
		}
		
		if ( array_key_exists( 'browserEngine', $settingsFinal ) ) {
			$settingsFinal['browserEngine'] = self::$reversedConstants['BROWSER_ENGINE'][ $settingsFinal['browserEngine'] ];
		}
		
		if ( array_key_exists( 'browserProxy', $settingsFinal ) ) {
			$settingsFinal['browserProxy'] = self::$reversedConstants['EDGE_PROXY_SETTING'][ $settingsFinal['browserProxy'] ];
		}
		
		// Return the standardized array
		return $settingsFinal;
	}
	
	public function applyParams( Session $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Setup each custom parameter of the Sessions/Browser class
		if ( $hostName = $sessionDetails->getHostName() ) {
			$this->settings['remoteHost'][1] = $hostName;
		}
		
		// Transform the browserIECompatibility setting if it's set by the user
		if ( ! is_numeric( $this->settings['browserIECompatibility'][1] ) && array_key_exists( $this->settings['browserIECompatibility'][1], self::IE_EMULATION ) ) {
			$this->settings['browserIECompatibility'][1] = self::IE_EMULATION[ $this->settings['browserIECompatibility'][1] ];
		}
		
		// Transform the browserEngine setting if it's set by the user
		if ( ! is_numeric( $this->settings['browserEngine'][1] ) && array_key_exists( $this->settings['browserEngine'][1], self::BROWSER_ENGINE ) ) {
			$this->settings['browserEngine'][1] = self::BROWSER_ENGINE[ $this->settings['browserEngine'][1] ];
		}
		
		// Transform the browserProxy setting if it's set by the user
		if ( ! is_numeric( $this->settings['browserProxy'][1] ) && array_key_exists( $this->settings['browserProxy'][1], self::EDGE_PROXY_SETTING ) ) {
			$this->settings['browserProxy'][1] = self::EDGE_PROXY_SETTING[ $this->settings['browserProxy'][1] ];
		}
	}
	
	public function getString(): string {
		// Do necessary string replacements for the current output before the final export
		// None ATM
		
		// Make the adjustments for specific settings that change other settings behind the scenes
		if ( $this->settings['browserEngine'][1] === self::BROWSER_ENGINE['Microsoft Edge'] ) {
			$this->settings['edgeDisplayTopBar'][1]  = $this->settings['displayTopBar'][1];
			$this->settings['edgeExternalPopups'][1] = $this->settings['browserExternalPopups'][1];
		}
		
		if ( $this->settings['displayTopBar'][1] === self::DISABLED ) {
			$this->settings['displayBackButton'][1]    = self::DISABLED;
			$this->settings['displayForwardButton'][1] = self::DISABLED;
			$this->settings['displayRefreshButton'][1] = self::DISABLED;
			$this->settings['displayStopButton'][1]    = self::DISABLED;
			$this->settings['displayHomeButton'][1]    = self::DISABLED;
			$this->settings['displayAddressBar'][1]    = self::DISABLED;
		}
		
		return parent::getString();
	}
}
