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
	
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'sessionType'              => new Setting( 0, '5' ),
			'remoteHost'               => new Setting( 1, 'localhost' ), // Mandatory
			'remotePort'               => new Setting( 2, '5900' ),
			'autoScale'                => new Setting( 3, self::ENABLED ),
			'viewOnly'                 => new Setting( 4, self::DISABLED ),
			'sshGatewayHostList'       => new Setting( 5, '' ), // When setting, separate hostnames using '__PIPE__'
			'sshGatewayPortList'       => new Setting( 6, '' ), // When setting, separate ports using '__PIPE__'
			'sshGatewayUserList'       => new Setting( 7, '' ), // When setting, separate usernames using '__PIPE__'
			'sshGatewayPrivateKeyList' => new Setting( 8, '' ), // As above, plus separate paths using '__PIPE__'
			'displaySettingsBar'       => new Setting( 9, self::ENABLED ),
			'useNewVncEngine'          => new Setting( 10, self::DISABLED ),
			'useSslTunneling'          => new Setting( 11, self::DISABLED ),
			'useUnixLogin'             => new Setting( 12, '' ),
			'proxyType'                => new Setting( 13, self::PROXY_TYPE['None'] ),
			'proxyHost'                => new Setting( 14, '' ),
			'proxyPort'                => new Setting( 15, '1080' ),
			'proxyLogin'               => new Setting( 16, '' ),
			'vncUnknown17'             => new Setting( 17, '' ), // TODO Find out what this is
		];
	}
	
	public function applyParams( Connection $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Setup each custom parameter of the Connections/VNC class
		if ( $hostName = $sessionDetails->getHostName() ) {
			$this->settings['remoteHost']->setValue( $hostName );
		}
	}
	
	public function getString(): string {
		// Do necessary string replacements for the current output before the final export
		// None ATM
		
		// Make the adjustments for specific settings that change other settings behind the scenes
		$sslTunneling = $this->settings['useSslTunneling']->getValue();
		$proxyType    = $this->settings['proxyType']->getValue();
		if ( $sslTunneling === self::ENABLED || $proxyType !== self::PROXY_TYPE['None'] ) {
			$this->settings['useNewVncEngine']->setValue( self::ENABLED );
		}
		
		return parent::getString();
	}
}
