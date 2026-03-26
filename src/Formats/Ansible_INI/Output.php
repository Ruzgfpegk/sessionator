<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\Ansible_INI;

use ReflectionClass;

use Ruzgfpegk\Sessionator\Formats\CommonOutput;
use Ruzgfpegk\Sessionator\Formats\FormatOutput;
use Ruzgfpegk\Sessionator\Internals\SessionList;

/**
 * The Formats\Ansible_INI\Output class defines how to generate an INI inventory file for export
 * As each folder would become a group, different session paths shouldn't contain an identical folder name after common roots
 * Ansible groups can only import either hosts or other groups (using ":children"), so a fake group can be needed when mixing hosts and groups
 *
 * For now only the last "folder" in the path is used as the group name, without a hierarchy
 * Only SSH is supported for now, so it's kept simple.
 *
 * Reference: https://docs.ansible.com/projects/ansible/latest/inventory_guide/intro_inventory.html
 */
class Output extends CommonOutput implements FormatOutput {
	
	/**
	 * @inheritDoc
	 */
	public function getAsText( SessionList $sessionList ): array {
		$output = [];
		
		$groupHierarchy = [];
		
		// In Ansible inventories, each folder of the session path would be a group
		// TODO: Implement subgroups with hierarchies
		// The reverse sort should ensure that subgroups are created before their parent as it's usually done
		//$sessionList->rsort();
		
		// For now, we sort paths the usual way
		$sessionList->sort();
		
		// First, we create a tree of paths to traverse it more easily afterwards
		foreach ( $sessionList->getPathList() as $sessionPath ) {
			$groups   = explode( '\\', $sessionPath );
			$depthMax = count( $groups ) - 1;
			
			$sectionName = str_replace( ' ', '_', $groups[ $depthMax ] );
			
			$output[] = '[' . ( $sectionName ?: 'UNNAMED' ) . ']';
			
			// Group sessions
			foreach ( $sessionList->getPathSessions( $sessionPath ) as $sessionDetails ) {
				// Getting the session type from its class name
				$sessionType = ( new ReflectionClass( $sessionDetails ) )->getShortName();
				
				// Only SSH is supported
				if ( $sessionType !== 'SSH' ) {
					continue;
				}
				
				$userName   = $sessionDetails->getUserName();
				$remotePort = $sessionDetails->getSessionParam( 'remotePort' ) ?: '22';
				$privateKey = $sessionDetails->getSessionParam( 'privateKeyPath' );
				
				$sshCommand = '';
				
				$sshGatewayHostList       = $sessionDetails->getSessionParam( 'sshGatewayHostList' );
				$sshGatewayPortList       = $sessionDetails->getSessionParam( 'sshGatewayPortList' );
				$sshGatewayUserList       = $sessionDetails->getSessionParam( 'sshGatewayUserList' );
				$sshGatewayPrivateKeyList = $sessionDetails->getSessionParam( 'sshGatewayPrivateKeyList' );
				
				if ( $sshGatewayHostList && $sshGatewayPortList && $sshGatewayUserList ) {
					$sshCommand = '-o ProxyCommand="ssh -W %h:%p -q ';
					
					if ( $sshGatewayPrivateKeyList ) {
						$sshCommand .= '-i \"' . $sshGatewayPrivateKeyList . '\" ';
					}
					
					$sshCommand .= $sshGatewayUserList . '@' . $sshGatewayHostList . '"';
				}
				
				$output[] = str_replace( ' ', '-', $sessionDetails->getSessionName() )
				            .                          ' ansible_host='              . $sessionDetails->getHostname()
				            . ( $userName            ? ' ansible_user='              . $userName          : '' )
				            . ( $remotePort !== '22' ? ' ansible_port='              . $remotePort        : '' )
				            . ( $privateKey          ? ' ansible_private_key_file='  . $privateKey        : '' )
				            . ( $sshCommand          ? " ansible_ssh_common_args='"  . $sshCommand . "' " : '' )
				;
			}
			
			$output[] = '';
		}
		
		return $output;
	}
}
