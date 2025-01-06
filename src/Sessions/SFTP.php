<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Sessions;

/**
 * The SFTP object extends the SessionBase object with specificities of SFTP sessions
 */
class SFTP extends SessionBase {
	use Traits\UserName;
}
