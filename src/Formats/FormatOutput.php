<?php
declare( strict_types = 1 );

namespace Ruzgfpegk\Sessionator\Formats;

/**
 * Interface to define a common basis to all output format types
 */
interface FormatOutput {
	/**
	 * Returns in an array the contents of the final file
	 *
	 * @param array $sessionList
	 *
	 * @return array
	 */
	public function getAsText( array $sessionList ): array;
	
	/**
	 * Displays as text the contents of the final file
	 *
	 * @param array $sessionList
	 *
	 * @return void
	 */
	public function displayAsText( array $sessionList ): void;
	
	/**
	 * Displays as HTML the contents of the final file
	 *
	 * @param array $sessionList
	 *
	 * @return void
	 */
	public function displayAsHtml( array $sessionList ): void;
	
	/**
	 * Initiates a download of the final file
	 *
	 * @param array $sessionList The list of sessions to save
	 * @param string $lineSeparator The line separator to use in the output
	 *
	 * @return void
	 */
	public function downloadAsFile( array $sessionList, string $lineSeparator = "\r\n" ): void;
	
	/**
	 * Save the session list as a file
	 *
	 * @param array $sessionList The list of sessions to save
	 * @param string $fileName The name of the file to save to
	 * @param string $lineSeparator The line separator to use in the output
	 *
	 * @return void
	 */
	public function saveAsFile( array $sessionList, string $fileName, string $lineSeparator = "\r\n" ): void;
}
