<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Sessions;

/**
 * The RDP object extends the SessionBase object with specificities of RDP sessions
 */
class RDP extends SessionBase {
	use Traits\UserName;
}
