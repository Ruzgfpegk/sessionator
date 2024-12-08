<?php
declare( strict_types = 1 );

namespace Ruzgfpegk\Sessionator\Formats;

use Ruzgfpegk\Sessionator\Internals\SessionList;

/**
 * Interface to define a common basis to all output format types
 */
interface FormatOutput {
	/**
	 * Returns in an array the contents of the final file.
	 * If the contents are binary, a good idea would be to exit with an error and point to getAsFile instead.
	 *
	 * @param SessionList $sessionList The list of sessions to save
	 *
	 * @return array
	 */
	public function getAsText( SessionList $sessionList ): array;
	
	/**
	 * Returns in a (probably binary) string the contents of the final file.
	 *
	 * @param SessionList $sessionList The list of sessions to save
	 *
	 * @return string
	 */
	public function getAsFile( SessionList $sessionList ): string;
	
	/**
	 * Displays as text the contents of the final file
	 *
	 * @param SessionList $sessionList The list of sessions to save
	 *
	 * @return void
	 */
	public function displayAsText( SessionList $sessionList ): void;
	
	/**
	 * Displays as HTML the contents of the final file
	 *
	 * @param SessionList $sessionList The list of sessions to save
	 *
	 * @return void
	 */
	public function displayAsHtml( SessionList $sessionList ): void;
	
	/**
	 * Initiates a download of the final file.
	 * The line separator is the $lineSeparator property of the object.
	 *
	 * @param SessionList $sessionList The list of sessions to save
	 *
	 * @return void
	 */
	public function downloadAsFile( SessionList $sessionList ): void;
	
	/**
	 * Save the session list as a file.
	 * The line separator is the $lineSeparator property of the object.
	 *
	 * @param SessionList $sessionList The list of sessions to save
	 * @param string $fileName The name of the file to save to
	 *
	 * @return void
	 */
	public function saveAsFile( SessionList $sessionList, string $fileName ): void;
}
