<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

/**
 * The Formats\MobaXterm\SessionIcon class defines all the possible icons for sessions in the .mxtsessions format
 * and helps in getting the right one.
 */
class SessionIcon {
	public const ICON_TO_ID = [
		// Fallback icon (empty screen), a rather arbitrary one
		'Default'                  => 64,
		// Defaults icons for session types
		'SSH'                      => 109,
		'Telnet'                   => 98,
		'Rsh'                      => 100,
		'Xdmcp'                    => 88,
		'RDP'                      => 91,
		'VNC'                      => 128,
		'FTP'                      => 130,
		'SFTP'                     => 140,
		'Serial'                   => 131,
		'File'                     => 84,
		'Browser'                  => 313,
		'Mosh'                     => 145,
		'AWS S3'                   => 343,
		'WSL'                      => 151,
		// Defaults icons for shell session types
		'Shell_Bash'               => 204,
		'Shell_Zsh'                => 205,
		'Shell_Cmd'                => 129,
		'Shell_PS1'                => 192,
		'Shell_PS2'                => 193,
		'Shell_Bash_External'      => 152,
		// Custom - Graphical
		'Graphical_RedHat'         => 83,
		'Graphical_WindowsXP'      => 85,
		'Graphical_XWindows'       => 88,
		'Graphical_Debian'         => 89,
		'Graphical_Mac'            => 90,
		'Graphical_Windows10'      => 91,
		'Graphical_RaspberryPi'    => 92,
		'Graphical_Linux'          => 93,
		// Custom - Terminals
		'Terminal_Ubuntu'          => 105,
		'Terminal_Linux'           => 111,
		'Terminal_SUSE'            => 115,
		'Terminal_Debian'          => 149,
		'Terminal_RaspberryPi'     => 150,
		'Terminal_Windows'         => 151,
		'Terminal_Cygwin'          => 153,
		'Terminal_Android'         => 194,
		'Terminal_CentOS'          => 195,
		'Terminal_Docker'          => 196,
		'Terminal_VirtualBox'      => 197,
		'Terminal_Fedora'          => 199,
		'Terminal_RedHat'          => 200,
		'Terminal_FreeBSD'         => 201,
		'Terminal_Git'             => 202,
		'Terminal_MSYS'            => 203,
		'Terminal_Bash'            => 204,
		'Terminal_Zsh'             => 205,
		// Custom - Hardware
		'Hardware_Workstation'     => 114,
		'Hardware_Modem'           => 118,
		'Hardware_Firewall'        => 120,
		'Hardware_Drive_Cloud'     => 121,
		'Hardware_Satellite'       => 125,
		'Hardware_SAN'             => 126,
		'Hardware_Science'         => 127,
		'Hardware_Home_Automation' => 135,
		'Hardware_NAS'             => 142,
		'Hardware_AP'              => 144,
		'Hardware_Satellite_Dish'  => 145,
	];
	
	/**
	 * Returns the icon for the session type
	 *
	 * @param string $sessionType Either the session type (SSH, RDP, ...) or the OS/distribution
	 * @param string $sessionKind $sessionType is the subtype of icon ("Shell", "Graphical", "Terminal" or "Hardware")
	 *
	 * @return int
	 */
	public function getIcon( string $sessionType, string $sessionKind = '' ): int {
		if ( $sessionKind === '' && array_key_exists( $sessionType, self::ICON_TO_ID ) ) {
			return self::ICON_TO_ID[ $sessionType ];
		}
		
		if ( array_key_exists( "{$sessionKind}_$sessionType", self::ICON_TO_ID ) ) {
			return self::ICON_TO_ID["{$sessionKind}_$sessionType"];
		}
		
		return self::ICON_TO_ID['Default'];
	}
}
