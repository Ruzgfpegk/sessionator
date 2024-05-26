<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Connections\Connection;

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
	
	public function applyParams( Connection $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Setup each custom parameter of the Connections/SSH class
		if ( $userName = $sessionDetails->getUserName() ) {
			$this->settings['userName'][1] = $userName;
			$this->settings['noUserName'][1] = self::DISABLED;
		}
		
		if ( $hostName = $sessionDetails->getHostName() ) {
			$this->settings['remoteHost'][1] = $hostName;
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
