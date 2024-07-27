<?php
declare( strict_types = 1 );

namespace Ruzgfpegk\Sessionator\Formats;

/**
 * Interface to define a common basis to all input format types
 */
interface FormatInput {
	/**
	 * Returns in an array the session list from the imported file.
	 *
	 * @param string $fileName The file to import
	 *
	 * @return array
	 */
	public function importFromFile( string $fileName ): array;
}
