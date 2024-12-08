<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\Bash;

use Ruzgfpegk\Sessionator\Sessions\Session;

/**
 * The Formats\Bash\SSH class defines how an SSH setting line is added to the base Bash script
 */
class SSH extends SettingLine implements SessionType {
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'remoteHost'               => [ 0, 'localhost' ], // Mandatory
			'remotePort'               => [ 1, '22' ],
			'userName'                 => [ 2, '' ],
			'x11Forwarding'            => [ 3, self::DISABLED ],
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
		
		// Setup each custom parameter of the Sessions/SSH class
		if ( $userName = $sessionDetails->getUserName() ) {
			$this->settings['userName'][1] = $userName;
		}
		
		if ( $hostName = $sessionDetails->getHostName() ) {
			$this->settings['remoteHost'][1] = $hostName;
		}
	}
}
