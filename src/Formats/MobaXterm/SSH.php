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
			'sessionType'              => new Setting( 0, '0' ),
			'remoteHost'               => new Setting( 1, 'localhost' ), // Mandatory
			'remotePort'               => new Setting( 2, '22' ),
			'userName'                 => new Setting( 3, '' ),
			'sshUnknown4'              => new Setting( 4, '' ), // TODO Find out what this is
			'x11Forwarding'            => new Setting( 5, self::ENABLED ),
			'compression'              => new Setting( 6, self::ENABLED ),
			'commandAtLogin'           => new Setting( 7, '' ), // When setting, change ';' by '__PTVIRG__'
			'sshGatewayHostList'       => new Setting( 8, '' ), // When setting, separate hostnames using '__PIPE__'
			'sshGatewayPortList'       => new Setting( 9, '' ), // When setting, separate ports using '__PIPE__'
			'sshGatewayUserList'       => new Setting( 10, '' ), // When setting, separate usernames using '__PIPE__'
			'stayConnectedAfterCmd'    => new Setting( 11, self::DISABLED ),
			'noUserName'               => new Setting( 12, self::ENABLED ), // DISABLED if userName is set, else ENABLED
			'remoteEnvironment'        => new Setting( 13, self::REMOTE_ENVIRONMENTS['Interactive shell'] ), // TODO list
			'privateKeyPath'           => new Setting( 14, '' ), // When setting, change 'C' by '_CurrentDrive_'
			'sshGatewayPrivateKeyList' => new Setting( 15, '' ), // As above, plus separate paths using '__PIPE__'
			'fileBrowser'              => new Setting( 16, self::ENABLED ), // DISABLED if indexes 24+25 are DISABLED
			'fileBrowserFollowSshPath' => new Setting( 17, self::DISABLED ),
			'sshUnknown18'             => new Setting( 18, self::DISABLED ), // TODO Find out what this is
			'proxyType'                => new Setting( 19, self::PROXY_TYPE['None'] ),
			'proxyHost'                => new Setting( 20, '' ),
			'proxyPort'                => new Setting( 21, '1080' ),
			'proxyLogin'               => new Setting( 22, '' ),
			'adaptLocalesOnRemote'     => new Setting( 23, self::DISABLED ),
			'fileBrowserScpOverSftp'   => new Setting( 24, self::DISABLED ),
			'fileBrowserProtocol'      => new Setting( 25, self::FILE_BROWSER_PROTOCOL['SFTP'] ),
			'localProxyCommand'        => new Setting( 26, '' ), // See .md specs file
			'sshProtocolVersion'       => new Setting( 27, self::SSH_PROTOCOL_VERSION['auto'] ),
			'keyExchangeAlgorithms'    => new Setting( 28, '' ), // See .md specs file
			'hostKeyTypes'             => new Setting( 29, '' ), // See .md specs file
			'ciphers'                  => new Setting( 30, '' ), // See .md specs file
			'disconnectIfTrivialLogin' => new Setting( 31, self::DISABLED ),
			'preferKnownServerAlgs'    => new Setting( 32, self::ENABLED ),
			'attemptLoginWithSshAgent' => new Setting( 33, self::ENABLED ),
			'allowAgentForwarding'     => new Setting( 34, self::DISABLED )
		];
	}
	
	public function applyParams( Connection $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Setup each custom parameter of the Connections/SSH class
		if ( $userName = $sessionDetails->getUserName() ) {
			$this->settings['userName']->setValue( $userName );
			$this->settings['noUserName']->setValue( self::DISABLED );
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
		$fileBrowserProtocol = $this->settings['fileBrowserProtocol']->getValue();
		if ( $fileBrowserProtocol === self::FILE_BROWSER_PROTOCOL['disabled'] ) {
			$this->settings['fileBrowser']->setValue( self::DISABLED );
			$this->settings['fileBrowserScpOverSftp']->setValue( self::DISABLED );
		} elseif ( $fileBrowserProtocol === self::FILE_BROWSER_PROTOCOL['SFTP'] ) {
			$this->settings['fileBrowser']->setValue( self::ENABLED );
			$this->settings['fileBrowserScpOverSftp']->setValue( self::DISABLED );
		} elseif ( $fileBrowserProtocol === self::FILE_BROWSER_PROTOCOL['SCP Speed'] ||
		           $fileBrowserProtocol === self::FILE_BROWSER_PROTOCOL['SCP Normal'] ) {
			$this->settings['fileBrowser']->setValue( self::ENABLED );
			$this->settings['fileBrowserScpOverSftp']->setValue( self::ENABLED );
		}
		
		return parent::getString();
	}
}
