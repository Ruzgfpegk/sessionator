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
		'Terminal_Prompt'          => 97,
		'Terminal_Warning'         => 103,
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
		'Hardware_Storage_Bay'     => 119,
		'Hardware_Firewall'        => 120,
		'Hardware_Drive_Cloud'     => 121,
		'Hardware_Satellite'       => 125,
		'Hardware_SAN'             => 126,
		'Hardware_Science'         => 127,
		'Hardware_Home_Automation' => 135,
		'Hardware_NAS'             => 142,
		'Hardware_AP'              => 144,
		'Hardware_Satellite_Dish'  => 145,
		'Hardware_Emitter'         => 146,
		'Hardware_Satellite_Orbit' => 146,
		// Fallback for unsupported icons, not really accurate.
		'mRemote'                  => 114, // Hardware_Workstation
		'mRemoteNG'                => 114, // Hardware_Workstation
		'Terminal_PuTTY'           => 97,  // Terminal_Prompt
		'Hardware_Router'          => 116,
		'Hardware_Switch'          => 116,
		'Hardware_Telephone'       => 118, // Hardware_Modem
		'Server_AntiVirus'         => 133,
		'Server_Backup'            => 126, // Hardware_SAN
		'Server_Build'             => 147,
		'Server_DataBase'          => 134,
		'Server_Domain_Controller' => 124,
		'Server_ESX'               => 197, // Terminal_VirtualBox
		'Server_Fax'               => 118, // Hardware_Modem
		'Server_File_Server'       => 142, // Hardware_NAS
		'Server_Finance'           => 122,
		'Server_Logging'           => 134,
		'Server_Mail'              => 141, // Paper plane
		'Server_SharePoint'        => 132,
		'Server_Terminal_Server'   => 114, // Hardware_Workstation
		'Server_Test'              => 137,
		'Server_Virtual_Machine'   => 197, // Terminal_VirtualBox
		'Server_Web'               => 130, // FTP
	];
	
	
	private static array $idToIcon = [];
	
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
	
	/**
	 * Returns the icon name for the icon ID
	 *
	 * @param string $iconId The icon ID
	 *
	 * @return string
	 */
	public function getIconName( string $iconId ): string {
		$iconIdInt = (int) $iconId;
		
		if ( empty( self::$idToIcon ) ) {
			// Build and cache the reverse mapping of self::ICON_TO_ID
			self::$idToIcon = array_flip( self::ICON_TO_ID );
		}
		
		return self::$idToIcon[ $iconIdInt ] ?? 'Default';
	}
}
