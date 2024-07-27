<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Connections\Connection;

/**
 * The Formats\MobaXterm\VNC class defines the VNC part of the .mxtsessions format
 */
class VNC extends SettingBlock implements SessionType {
	
	public const PROXY_TYPE = [
		'None'   => '0',
		'Socks4' => '1',
		'Socks5' => '2',
		'Http'   => '3',
		'Telnet' => '4',
		'Local'  => '5'
	];
	
	public array $booleans = [
		'autoScale',
		'viewOnly',
		'displaySettingsBar',
		'useNewVncEngine',
		'useSslTunneling'
	];
	
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'sessionType'              => [ 0, '5' ],
			'remoteHost'               => [ 1, 'localhost' ], // Mandatory
			'remotePort'               => [ 2, '5900' ],
			'autoScale'                => [ 3, self::ENABLED ],
			'viewOnly'                 => [ 4, self::DISABLED ],
			'sshGatewayHostList'       => [ 5, '' ], // When setting, separate hostnames using '__PIPE__'
			'sshGatewayPortList'       => [ 6, '' ], // When setting, separate ports using '__PIPE__'
			'sshGatewayUserList'       => [ 7, '' ], // When setting, separate usernames using '__PIPE__'
			'sshGatewayPrivateKeyList' => [ 8, '' ], // As above, plus separate paths using '__PIPE__'
			'displaySettingsBar'       => [ 9, self::ENABLED ],
			'useNewVncEngine'          => [ 10, self::DISABLED ],
			'useSslTunneling'          => [ 11, self::DISABLED ],
			'useUnixLogin'             => [ 12, '' ],
			'proxyType'                => [ 13, self::PROXY_TYPE['None'] ],
			'proxyHost'                => [ 14, '' ],
			'proxyPort'                => [ 15, '1080' ],
			'proxyLogin'               => [ 16, '' ],
			'vncUnknown17'             => [ 17, '' ], // TODO Find out what this is
		];
	}
	
	public function applyParams( Connection $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Setup each custom parameter of the Connections/VNC class
		if ( $hostName = $sessionDetails->getHostName() ) {
			$this->settings['remoteHost'][1] = $hostName;
		}
		
		// Transform the proxyType setting if it's set by the user
		if ( ! is_numeric( $this->settings['proxyType'][1] ) && array_key_exists( $this->settings['proxyType'][1], self::PROXY_TYPE ) ) {
			$this->settings['proxyType'][1] = self::PROXY_TYPE[ $this->settings['proxyType'][1] ];
		}
	}
	
	public function getString(): string {
		// Do necessary string replacements for the current output before the final export
		// None ATM
		
		// Make the adjustments for specific settings that change other settings behind the scenes
		$sslTunneling = $this->settings['useSslTunneling'][1];
		$proxyType    = $this->settings['proxyType'][1];
		if ( $sslTunneling === self::ENABLED || $proxyType !== self::PROXY_TYPE['None'] ) {
			$this->settings['useNewVncEngine'][1] = self::ENABLED;
		}
		
		return parent::getString();
	}
}
