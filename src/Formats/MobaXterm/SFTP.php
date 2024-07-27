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
	
	public array $booleans = [
		'utf8Charset',
		'compression',
		'asciiMode',
		'twoStepsAuthentication',
		'preserveFileDates'
	];
	
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'sessionType'            => [ 0, '7' ],
			'remoteHost'             => [ 1, 'localhost' ], // Mandatory
			'remotePort'             => [ 2, '22' ],
			'userName'               => [ 3, '' ],
			'utf8Charset'            => [ 4, self::ENABLED ],
			'compression'            => [ 5, self::DISABLED ],
			'remoteStartupFolder'    => [ 6, '' ],
			'asciiMode'              => [ 7, self::DISABLED ],
			'twoStepsAuthentication' => [ 8, self::DISABLED ],
			'privateKeyPath'         => [ 9, '' ], // When setting, change 'C' by '_CurrentDrive_'
			'proxyTypeSftp'          => [ 10, self::PROXY_TYPE_SFTP['No proxy'] ],
			'proxyHost'              => [ 11, '' ],
			'proxyPort'              => [ 12, '1080' ],
			'proxyLogin'             => [ 13, '' ],
			'proxyPassword'          => [ 14, '' ],
			'localStartupFolder'     => [ 15, '' ],
			'preserveFileDates'      => [ 16, self::ENABLED ],
		];
	}
	
	public function applyParams( Connection $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Setup each custom parameter of the Connections/SFTP class
		if ( $userName = $sessionDetails->getUserName() ) {
			$this->settings['userName'][1] = $userName;
		}
		
		if ( $hostName = $sessionDetails->getHostName() ) {
			$this->settings['remoteHost'][1] = $hostName;
		}
		
		// Transform the proxyTypeSftp setting if it's set by the user
		if ( ! is_numeric( $this->settings['proxyTypeSftp'][1] ) && array_key_exists( $this->settings['proxyTypeSftp'][1], self::PROXY_TYPE ) ) {
			$this->settings['proxyTypeSftp'][1] = self::PROXY_TYPE_SFTP[ $this->settings['proxyTypeSftp'][1] ];
		}
	}
	
	public function getString(): string {
		// Do necessary string replacements for the current output before the final export
		if ( $this->settings['privateKeyPath'][1] !== '' ) {
			$this->settings['privateKeyPath'][1] = str_replace(
				'C:\\', '_CurrentDrive_:\\', $this->settings['privateKeyPath'][1]
			);
		}
		
		// Make the adjustments for specific settings that change other settings behind the scenes
		// None ATM
		
		return parent::getString();
	}
}
