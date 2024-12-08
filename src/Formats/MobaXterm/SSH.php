<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Sessions\Session;

/**
 * The Formats\MobaXterm\SSH class defines the SSH part of the .mxtsessions format
 */
class SSH extends SettingBlock implements SessionType {
	
	public const REMOTE_ENVIRONMENTS = [
		'Interactive shell' => '0',
		'LXDE'              => '1'
	];
	
	public const PROXY_TYPE = [
		'None'   => '0',
		'Socks4' => '1',
		'Socks5' => '2',
		'Http'   => '3',
		'Telnet' => '4',
		'Local'  => '5'
	];
	
	public const FILE_BROWSER_PROTOCOL = [
		'disabled'   => '0',
		'SFTP'       => '1',
		'SCP Speed'  => '2',
		'SCP Normal' => '3',
	];
	
	public const SSH_PROTOCOL_VERSION = [
		'auto'             => '0',
		'SSHv2'            => '1',
		'SSHv1 (insecure)' => '2',
	];
	
	// Used when reading the config file into settings (by reverseMapping())
	public array $booleans = [
		'x11Forwarding',
		'compression',
		'stayConnectedAfterCmd',
		'noUserName',
		'fileBrowser',
		'fileBrowserFollowSshPath',
		'sshUnknown18',
		'adaptLocalesOnRemote',
		'fileBrowserScpOverSftp',
		'disconnectIfTrivialLogin',
		'preferKnownServerAlgs',
		'attemptLoginWithSshAgent',
		'allowAgentForwarding'
	];
	
	private static array $reversedConstants = [];
	
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'sessionType'              => [ 0, '0' ],
			'remoteHost'               => [ 1, 'localhost' ], // Mandatory
			'remotePort'               => [ 2, '22' ],
			'userName'                 => [ 3, '' ],
			'sshUnknown4'              => [ 4, '' ], // TODO Find out what this is
			'x11Forwarding'            => [ 5, self::ENABLED ],
			'compression'              => [ 6, self::ENABLED ],
			'commandAtLogin'           => [ 7, '' ], // When setting, change ';' by '__PTVIRG__'
			'sshGatewayHostList'       => [ 8, '' ], // When setting, separate hostnames using '__PIPE__'
			'sshGatewayPortList'       => [ 9, '' ], // When setting, separate ports using '__PIPE__'
			'sshGatewayUserList'       => [ 10, '' ], // When setting, separate usernames using '__PIPE__'
			'stayConnectedAfterCmd'    => [ 11, self::DISABLED ],
			'noUserName'               => [ 12, self::ENABLED ], // DISABLED if userName is set, else ENABLED
			'remoteEnvironment'        => [ 13, self::REMOTE_ENVIRONMENTS['Interactive shell'] ], // TODO list
			'privateKeyPath'           => [ 14, '' ], // When setting, change 'C' by '_CurrentDrive_'
			'sshGatewayPrivateKeyList' => [ 15, '' ], // As above, plus separate paths using '__PIPE__'
			'fileBrowser'              => [ 16, self::ENABLED ], // DISABLED if indexes 24+25 are DISABLED
			'fileBrowserFollowSshPath' => [ 17, self::DISABLED ],
			'sshUnknown18'             => [ 18, self::DISABLED ], // TODO Find out what this is
			'proxyType'                => [ 19, self::PROXY_TYPE['None'] ],
			'proxyHost'                => [ 20, '' ],
			'proxyPort'                => [ 21, '1080' ],
			'proxyLogin'               => [ 22, '' ],
			'adaptLocalesOnRemote'     => [ 23, self::DISABLED ],
			'fileBrowserScpOverSftp'   => [ 24, self::DISABLED ],
			'fileBrowserProtocol'      => [ 25, self::FILE_BROWSER_PROTOCOL['SFTP'] ],
			'localProxyCommand'        => [ 26, '' ], // See .md specs file
			'sshProtocolVersion'       => [ 27, self::SSH_PROTOCOL_VERSION['auto'] ],
			'keyExchangeAlgorithms'    => [ 28, '' ], // See .md specs file
			'hostKeyTypes'             => [ 29, '' ], // See .md specs file
			'ciphers'                  => [ 30, '' ], // See .md specs file
			'disconnectIfTrivialLogin' => [ 31, self::DISABLED ],
			'preferKnownServerAlgs'    => [ 32, self::ENABLED ],
			'attemptLoginWithSshAgent' => [ 33, self::ENABLED ],
			'allowAgentForwarding'     => [ 34, self::DISABLED ],
		];
	}
	
	private function reverseConstants(): void {
		if ( empty( self::$reversedConstants ) ) {
			// Build and cache the reverse mapping of public constants
			self::$reversedConstants['REMOTE_ENVIRONMENTS']   = array_flip( self::REMOTE_ENVIRONMENTS );
			self::$reversedConstants['PROXY_TYPE']            = array_flip( self::PROXY_TYPE );
			self::$reversedConstants['FILE_BROWSER_PROTOCOL'] = array_flip( self::FILE_BROWSER_PROTOCOL );
			self::$reversedConstants['SSH_PROTOCOL_VERSION']  = array_flip( self::SSH_PROTOCOL_VERSION );
		}
	}
	
	public function decodeFromString( string $sessionSettings ): array {
		// Decode the settings
		$settingsFinal = $this->reverseMapping( $sessionSettings );
		
		// Decode the constants
		$this->reverseConstants();
		
		// De-transform the settings
		if ( array_key_exists( 'remoteEnvironment', $settingsFinal ) ) {
			$settingsFinal['remoteEnvironment'] = self::$reversedConstants['REMOTE_ENVIRONMENTS'][ $settingsFinal['remoteEnvironment'] ];
		}
		
		if ( array_key_exists( 'proxyType', $settingsFinal ) ) {
			$settingsFinal['proxyType'] = self::$reversedConstants['PROXY_TYPE'][ $settingsFinal['proxyType'] ];
		}
		
		if ( array_key_exists( 'fileBrowserProtocol', $settingsFinal ) ) {
			$settingsFinal['fileBrowserProtocol'] = self::$reversedConstants['FILE_BROWSER_PROTOCOL'][ $settingsFinal['fileBrowserProtocol'] ];
		}
		
		if ( array_key_exists( 'sshProtocolVersion', $settingsFinal ) ) {
			$settingsFinal['sshProtocolVersion'] = self::$reversedConstants['SSH_PROTOCOL_VERSION'][ $settingsFinal['sshProtocolVersion'] ];
		}
		
		// Return the standardized array
		return $settingsFinal;
	}
	
	public function applyParams( Session $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Setup each custom parameter of the Sessions/SSH class
		if ( $userName = $sessionDetails->getUserName() ) {
			$this->settings['userName'][1] = $userName;
			$this->settings['noUserName'][1] = self::DISABLED;
		}
		
		if ( $hostName = $sessionDetails->getHostName() ) {
			$this->settings['remoteHost'][1] = $hostName;
		}
		
		// Transform the remoteEnvironment setting if it's set by the user
		if ( ! is_numeric( $this->settings['remoteEnvironment'][1] ) && array_key_exists( $this->settings['remoteEnvironment'][1], self::REMOTE_ENVIRONMENTS ) ) {
			$this->settings['remoteEnvironment'][1] = self::REMOTE_ENVIRONMENTS[ $this->settings['remoteEnvironment'][1] ];
		}
		
		// Transform the proxyType setting if it's set by the user
		if ( ! is_numeric( $this->settings['proxyType'][1] ) && array_key_exists( $this->settings['proxyType'][1], self::PROXY_TYPE ) ) {
			$this->settings['proxyType'][1] = self::PROXY_TYPE[ $this->settings['proxyType'][1] ];
		}
		
		// Transform the fileBrowserProtocol setting if it's set by the user
		if ( ! is_numeric( $this->settings['fileBrowserProtocol'][1] ) && array_key_exists( $this->settings['fileBrowserProtocol'][1], self::FILE_BROWSER_PROTOCOL ) ) {
			$this->settings['fileBrowserProtocol'][1] = self::FILE_BROWSER_PROTOCOL[ $this->settings['fileBrowserProtocol'][1] ];
		}
		
		// Transform the sshProtocolVersion setting if it's set by the user
		if ( ! is_numeric( $this->settings['sshProtocolVersion'][1] ) && array_key_exists( $this->settings['sshProtocolVersion'][1], self::SSH_PROTOCOL_VERSION ) ) {
			$this->settings['sshProtocolVersion'][1] = self::SSH_PROTOCOL_VERSION[ $this->settings['sshProtocolVersion'][1] ];
		}
	}
	
	public function getString(): string {
		// Do necessary string replacements for the current output before the final export
		if ( $this->settings['privateKeyPath'][1] !== '' ) {
			$this->settings['privateKeyPath'][1] = str_replace(
				'C:\\', '_CurrentDrive_:\\', $this->settings['privateKeyPath'][1]
			);
		}
		
		if ( $this->settings['sshGatewayPrivateKeyList'][1] !== '' ) {
			$this->settings['sshGatewayPrivateKeyList'][1] = str_replace(
				'C:\\', '_CurrentDrive_:\\', $this->settings['sshGatewayPrivateKeyList'][1]
			);
		}
		
		// Make the adjustments for specific settings that change other settings behind the scenes
		$fileBrowserProtocol = $this->settings['fileBrowserProtocol'][1];
		if ( $fileBrowserProtocol === self::FILE_BROWSER_PROTOCOL['disabled'] ) {
			$this->settings['fileBrowser'][1] = self::DISABLED;
			$this->settings['fileBrowserScpOverSftp'][1] = self::DISABLED;
		} elseif ( $fileBrowserProtocol === self::FILE_BROWSER_PROTOCOL['SFTP'] ) {
			$this->settings['fileBrowser'][1] = self::ENABLED;
			$this->settings['fileBrowserScpOverSftp'][1] = self::DISABLED;
		} elseif ( $fileBrowserProtocol === self::FILE_BROWSER_PROTOCOL['SCP Speed'] ||
		           $fileBrowserProtocol === self::FILE_BROWSER_PROTOCOL['SCP Normal'] ) {
			$this->settings['fileBrowser'][1] = self::ENABLED;
			$this->settings['fileBrowserScpOverSftp'][1] = self::ENABLED;
		}
		
		return parent::getString();
	}
}
