<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Connections;

/**
 * The VNC object extends the Common object with specificities of VNC connections
 */
class VNC extends Common {
	/**
	 * VNC connections do not have a username, but some formats may need to get a value anyway
	 *
	 * @return string
	 */
	public function getUserName(): string {
		return '';
	}
}
