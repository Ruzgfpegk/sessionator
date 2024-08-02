<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Sessions;

/**
 * The VNC object extends the SessionBase object with specificities of VNC sessions
 */
class VNC extends SessionBase {
	/**
	 * VNC sessions do not have a username, but some formats may need to get a value anyway
	 *
	 * @return string
	 */
	public function getUserName(): string {
		return '';
	}
}
