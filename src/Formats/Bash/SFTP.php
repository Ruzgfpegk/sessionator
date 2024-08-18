<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\Bash;

use Ruzgfpegk\Sessionator\Sessions\Session;

/**
 * The Formats\Bash\SFTP class defines how an SFTP setting line is added to the base Bash script
 */
class SFTP extends SettingLine implements SessionType {
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'remoteHost'               => [ 0, 'localhost' ], // Mandatory
			'remotePort'               => [ 1, '22' ],
			'userName'                 => [ 2, '' ],
			'preserveFileDates'        => [ 3, self::ENABLED ],
			'compression'              => [ 4, self::ENABLED ],
			'privateKeyPath'           => [ 5, '' ],
			'sshGatewayHostList'       => [ 6, '' ], // When setting, separate hostnames using '|'
			'sshGatewayPortList'       => [ 7, '' ], // When setting, separate ports using '|'
			'sshGatewayUserList'       => [ 8, '' ], // When setting, separate usernames using '|'
			'sshGatewayPrivateKeyList' => [ 9, '' ], // When setting, separate paths using '|'
		];
	}
	
	public function applyParams( Session $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Setup each custom parameter of the Sessions/SFTP class
		if ( $userName = $sessionDetails->getUserName() ) {
			$this->settings['userName'][1] = $userName;
		}
		
		if ( $hostName = $sessionDetails->getHostName() ) {
			$this->settings['remoteHost'][1] = $hostName;
		}
	}
	
	public function getString(): string {
		// Do necessary string replacements for the current output before the final export
		// None ATM
		
		// Make the adjustments for specific settings that change other settings behind the scenes
		// None ATM
		
		return parent::getString();
	}
}
