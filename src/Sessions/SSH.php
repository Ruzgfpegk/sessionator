<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Sessions;

/**
 * The SSH object extends the SessionBase object with specificities of SSH sessions
 */
class SSH extends SessionBase {
	use Traits\UserName;
}
