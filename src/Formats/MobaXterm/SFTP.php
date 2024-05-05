<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Connections\Connection;

/**
 * The Formats\MobaXterm\SFTP class defines the SFTP part of the .mxtsessions format
 */
class SFTP extends SettingBlock implements SessionType {
	
	public const PROXY_TYPE_SFTP = [
		'No proxy'                             => '0',
		'Socks4 Proxy'                         => '1',
		'Socks4 Proxy with authentication'     => '2',
		'Socks5 Proxy'                         => '3',
		'Socks5 Proxy with authentication'     => '4',
		'Web proxy'                            => '5',
		'Web proxy with basic authentication'  => '6',
		'Web proxy with digest authentication' => '7',
		'Web proxy with NTLM authentication'   => '8',
	];
	
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'sessionType'            => new Setting( 0, '7' ),
			'remoteHost'             => new Setting( 1, 'localhost' ), // Mandatory
			'remotePort'             => new Setting( 2, '22' ),
			'userName'               => new Setting( 3, '' ),
			'utf8Charset'            => new Setting( 4, self::ENABLED ),
			'compression'            => new Setting( 5, self::DISABLED ),
			'remoteStartupFolder'    => new Setting( 6, '' ),
			'asciiMode'              => new Setting( 7, self::DISABLED ),
			'twoStepsAuthentication' => new Setting( 8, self::DISABLED ),
			'privateKeyPath'         => new Setting( 9, '' ), // When setting, change 'C' by '_CurrentDrive_'
			'proxyTypeSftp'          => new Setting( 10, self::PROXY_TYPE_SFTP['No proxy'] ),
			'proxyHost'              => new Setting( 11, '' ),
			'proxyPort'              => new Setting( 12, '1080' ),
			'proxyLogin'             => new Setting( 13, '' ),
			'proxyPassword'          => new Setting( 14, '' ),
			'localStartupFolder'     => new Setting( 15, '' ),
			'preserveFileDates'      => new Setting( 16, self::ENABLED ),
		];
	}
	
	public function applyParams( Connection $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Setup each custom parameter of the Connections/SSH class
		if ( $userName = $sessionDetails->getUserName() ) {
			$this->settings['userName']->setValue( $userName );
		}
		
		if ( $hostName = $sessionDetails->getHostName() ) {
			$this->settings['remoteHost']->setValue( $hostName );
		}
	}
	
	public function getString(): string {
		// Do necessary string replacements for the current output before the final export
		if ( $this->settings['privateKeyPath']->getValue() !== '' ) {
			$this->settings['privateKeyPath']->setValue(
				str_replace( 'C:\\', '_CurrentDrive_:\\', $this->settings['privateKeyPath']->getValue() )
			);
		}
		
		// Make the adjustments for specific settings that change other settings behind the scenes
		// None ATM
		
		return parent::getString();
	}
}
