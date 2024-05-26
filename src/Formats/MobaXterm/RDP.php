<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Connections\Connection;

/**
 * The Formats\MobaXterm\RDP class defines the RDP part of the .mxtsessions format
 */
class RDP extends SettingBlock implements SessionType {
	public const RESOLUTION = [
		'Fit to terminal' => '0',
		'Fit to screen'   => '1',
		'640x480'         => '2',
		'800x600'         => '3',
		'1024x768'        => '4',
		'1152x864'        => '5',
		'1280x720'        => '6',
		'1280x968'        => '7',
		'1280x1024'       => '8',
		'1400x1050'       => '9',
		'1600x1200'       => '10',
		'1920x1080'       => '11',
		'1276x936'        => '12',
		'1916x988'        => '13',
		'1920x1200'       => '14',
		'1280x800'        => '15',
		'1360x768'        => '16',
		'1366x768'        => '17',
		'1440x900'        => '18',
		'1536x864'        => '19',
		'1600x900'        => '20',
		'1680x1050'       => '21',
		'2048x1152'       => '22',
		'2560x1080'       => '23',
		'2560x1440'       => '24',
		'3440x1440'       => '25',
		'3840x2160'       => '26'
	];
	
	public const REDIRECT_AUDIO = [
		'No audio'              => '0',
		'Redirect audio'        => '1',
		'Play on remote server' => '2'
	];
	
	public const ZOOM = [
		'no'   => '0',
		'auto' => '1',
		'25%'  => '2',
		'50%'  => '3',
		'75%'  => '4',
		'100%' => '5',
		'125%' => '6',
		'150%' => '7',
		'175%' => '8',
		'200%' => '9',
		'250%' => '10',
		'300%' => '11',
		'360%' => '12'
	];
	
	public const COLOR_DEPTH = [
		'auto'    => '0',
		'8 bits'  => '1',
		'16 bits' => '2',
		'24 bits' => '3',
		'32 bits' => '4'
	];
	
	public const SERVER_AUTHENTICATION = [
		'none'   => '0',
		'force'  => '1',
		'prompt' => '2'
	];
	
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'sessionType'              => [ 0, '4' ],
			'remoteHost'               => [ 1, 'localhost' ], // Mandatory
			'remotePort'               => [ 2, '3389' ],
			'userName'                 => [ 3, '' ],
			'adminConsole'             => [ 4, self::DISABLED ],
			'redirectPorts'            => [ 5, self::DISABLED ],
			'redirectDrives'           => [ 6, self::DISABLED ],
			'redirectPrinters'         => [ 7, self::DISABLED ],
			'rdpUnknown8'              => [ 8, self::ENABLED ], // TODO Find out what this is
			'enhancedGraphics'         => [ 9, self::DISABLED ],
			'resolution'               => [ 10, self::RESOLUTION['Fit to terminal'] ],
			'rdpUnknown11'             => [ 11, self::ENABLED ],
			'remoteCommand'            => [ 12, '' ],
			'sshGatewayHostList'       => [ 13, '' ], // When setting, separate hostnames using '__PIPE__'
			'sshGatewayPortList'       => [ 14, '' ], // When setting, separate ports using '__PIPE__'
			'sshGatewayUserList'       => [ 15, '' ], // When setting, separate usernames using '__PIPE__'
			'redirectAudio'            => [ 16, self::REDIRECT_AUDIO['No audio'] ],
			'nativeAuthentication'     => [ 17, self::DISABLED ],
			'sshGatewayPrivateKeyList' => [ 18, '' ], // As above, plus separate paths using '__PIPE__'
			'redirectClipboard'        => [ 19, self::ENABLED ],
			'rdpGateway'               => [ 20, '' ],
			'forwardKeyboardShortcuts' => [ 21, self::ENABLED ],
			'displaySettingsBar'       => [ 22, self::ENABLED ],
			'rdpUnknown23'             => [ 23, self::DISABLED ], // TODO Find out what this is
			'useCredSsp'               => [ 24, self::ENABLED ],
			'redirectMicrophone'       => [ 25, self::DISABLED ],
			'autoScale'                => [ 26, self::ENABLED ],
			'zoom'                     => [ 27, self::ZOOM['no'] ],
			'colorDepth'               => [ 28, self::COLOR_DEPTH['auto'] ],
			'redirectSmartCards'       => [ 29, self::DISABLED ],
			'serverAuthentication'     => [ 30, self::SERVER_AUTHENTICATION['none'] ],
		];
	}
	
	public function applyParams( Connection $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Setup each custom parameter of the Connections/SSH class
		if ( $userName = $sessionDetails->getUserName() ) {
			$this->settings['userName'][1] = $userName;
		}
		
		if ( $hostName = $sessionDetails->getHostName() ) {
			$this->settings['remoteHost'][1] = $hostName;
		}
	}
}
