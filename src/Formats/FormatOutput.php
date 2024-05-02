<?php
declare( strict_types = 1 );

namespace Ruzgfpegk\Sessionator\Formats;

/**
 * Interface to define a common basis to all output format types
 */
interface FormatOutput {
	/**
	 * Returns in an array the contents of the final file.
	 * If the contents are binary, a good idea would be to exit with an error and point to getAsFile instead.
	 *
	 * @param array $sessionList The list of sessions to save
	 *
	 * @return array
	 */
	public function getAsText( array $sessionList ): array;
	
	/**
	 * Returns in a (probably binary) string the contents of the final file.
	 *
	 * @param array $sessionList The list of sessions to save
	 *
	 * @return string
	 */
	public function getAsFile( array $sessionList ): string;
	
	/**
	 * Displays as text the contents of the final file
	 *
	 * @param array $sessionList The list of sessions to save
	 *
	 * @return void
	 */
	public function displayAsText( array $sessionList ): void;
	
	/**
	 * Displays as HTML the contents of the final file
	 *
	 * @param array $sessionList The list of sessions to save
	 *
	 * @return void
	 */
	public function displayAsHtml( array $sessionList ): void;
	
	/**
	 * Initiates a download of the final file.
	 * The line separator is the $lineSeparator property of the object.
	 *
	 * @param array $sessionList The list of sessions to save
	 *
	 * @return void
	 */
	public function downloadAsFile( array $sessionList ): void;
	
	/**
	 * Save the session list as a file.
	 * The line separator is the $lineSeparator property of the object.
	 *
	 * @param array $sessionList The list of sessions to save
	 * @param string $fileName The name of the file to save to
	 *
	 * @return void
	 */
	public function saveAsFile( array $sessionList, string $fileName ): void;
}
