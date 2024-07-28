<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

use Ruzgfpegk\Sessionator\Sessions\Session;

/**
 * The Formats\MobaXterm\TerminalSettings class defines the Terminal part of the .mtxsession format
 */
class TerminalSettings extends SettingBlock {
	public const CHARSETS = [ // TODO Complete the list
		'ISO-8859-1'  => '0',
		'ISO-8859-15' => '1',
		'UTF-8'       => '15',
		'CP850'       => '22',
	];
	
	public const CURSOR_TYPE = [
		'Block'              => '0',
		'Underline'          => '1',
		'Line'               => '2',
		'Blinking block'     => '3',
		'Blinking underline' => '4',
		'Blinking line'      => '5'
	];
	
	public const SYNTAX_HIGHLIGHT = [
		'None'              => '0',
		'Standard keywords' => '1',
		'Unix shell script' => '2',
		'Cisco'             => '3',
		'Perl'              => '4',
		'SQL'               => '5'
	];
	
	public const CUSTOM_MACRO = [
		'None'         => '<none>',
		'Custom Macro' => '<custom macro>'
	];
	
	public const PASTE_DELAY = [
		'Auto'  => '0',
		'none'  => '1',
		'10ms'  => '2',
		'20ms'  => '3',
		'30ms'  => '4',
		'40ms'  => '5',
		'50ms'  => '6',
		'60ms'  => '7',
		'70ms'  => '8',
		'80ms'  => '9',
		'90ms'  => '10',
		'100ms' => '11',
		'200ms' => '12',
		'360ms' => '13'
	];
	
	public const FONT_CHARSETS = [
		'ANSI'       => '0',
		'DEFAULT'    => '1',
		'ARABIC'     => '178',
		'GREEK'      => '161',
		'TURKISH'    => '162',
		'VIETNAMESE' => '163',
		'EASTEUROPE' => '238',
		'RUSSIAN'    => '204',
		'BALTIC'     => '186'
	];
	
	public array $booleans = [
		'fontBold',
		'terminalUnknown3',
		'appendPath',
		'backspaceSendsH',
		'logOutput',
		'lockTerminalTitle',
		'terminalUnknown15',
		'fixedDimensions',
		'boldIsBrighter',
		'fontAntialiasing',
		'fontLigatures'
	];
	
	private static array $reversedConstants = [];
	
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'terminalFont'      => [ 0, 'MobaFont' ],
			'fontSize'          => [ 1, '10' ],
			'fontBold'          => [ 2, self::DISABLED ],
			'terminalUnknown3'  => [ 3, self::DISABLED ], // TODO Find out what this is
			'appendPath'        => [ 4, self::ENABLED ],
			'charset'           => [ 5, self::CHARSETS['UTF-8'] ],
			'foregroundRgb'     => [ 6, '236,236,236' ],
			'backgroundRgb'     => [ 7, '30,30,30' ],
			'cursorRgb'         => [ 8, '180,180,192' ],
			'cursorType'        => [ 9, self::CURSOR_TYPE['Block'] ],
			'backspaceSendsH'   => [ 10, self::ENABLED ],
			'logOutput'         => [ 11, self::DISABLED ],
			'logFolderPath'     => [ 12, '' ],
			'terminalType'      => [ 13, 'xterm' ], // See format .md for full list
			'lockTerminalTitle' => [ 14, self::ENABLED ],
			'terminalUnknown15' => [ 15, self::DISABLED ], // TODO Find out what this is
			'colorsScheme'      => [ 16, '_Std_Colors_0_' ], // See format .md for alt format
			'terminalRows'      => [ 17, '80' ],
			'terminalColumns'   => [ 18, '24' ],
			'fixedDimensions'   => [ 19, self::DISABLED ],
			'syntaxHighlight'   => [ 20, self::SYNTAX_HIGHLIGHT['Standard keywords'] ],
			'boldIsBrighter'    => [ 21, self::ENABLED ],
			'customMacroToggle' => [ 22, self::CUSTOM_MACRO['None'] ],
			'customMacroText'   => [ 23, '' ],
			'pasteDelay'        => [ 24, self::PASTE_DELAY['Auto'] ],
			'fontCharset'       => [ 25, self::FONT_CHARSETS['DEFAULT'] ],
			'fontAntialiasing'  => [ 26, self::ENABLED ],
			'fontLigatures'     => [ 27, self::ENABLED ]
		];
	}
	
	private function reverseConstants(): void {
		if ( empty( self::$reversedConstants ) ) {
			// Build and cache the reverse mapping of public constants
			self::$reversedConstants['CHARSETS']         = array_flip( self::CHARSETS );
			self::$reversedConstants['CURSOR_TYPE']      = array_flip( self::CURSOR_TYPE );
			self::$reversedConstants['SYNTAX_HIGHLIGHT'] = array_flip( self::SYNTAX_HIGHLIGHT );
			self::$reversedConstants['CUSTOM_MACRO']     = array_flip( self::CUSTOM_MACRO );
			self::$reversedConstants['PASTE_DELAY']      = array_flip( self::PASTE_DELAY );
			self::$reversedConstants['FONT_CHARSETS']    = array_flip( self::FONT_CHARSETS );
		}
	}
	
	public function decodeFromString( string $sessionSettings ): array {
		// Decode the settings
		$settingsFinal = $this->reverseMapping( $sessionSettings );
		
		// Decode the constants
		$this->reverseConstants();
		$settingsFinal['charset']           = self::$reversedConstants['CHARSETS'][ $settingsFinal['charset'] ];
		$settingsFinal['cursorType']        = self::$reversedConstants['CURSOR_TYPE'][ $settingsFinal['cursorType'] ];
		$settingsFinal['syntaxHighlight']   = self::$reversedConstants['SYNTAX_HIGHLIGHT'][ $settingsFinal['syntaxHighlight'] ];
		$settingsFinal['customMacroToggle'] = self::$reversedConstants['CUSTOM_MACRO'][ $settingsFinal['customMacroToggle'] ];
		$settingsFinal['pasteDelay']        = self::$reversedConstants['PASTE_DELAY'][ $settingsFinal['pasteDelay'] ];
		$settingsFinal['fontCharset']       = self::$reversedConstants['FONT_CHARSETS'][ $settingsFinal['fontCharset'] ];
		
		// Return the standardized array
		return $settingsFinal;
	}
	
	public function applyParams( Session $sessionDetails ): void {
		parent::applyParams( $sessionDetails );
		
		// Transform the charset setting if it's set by the user
		if ( ! is_numeric( $this->settings['charset'][1] )
		     && array_key_exists( $this->settings['charset'][1], self::CHARSETS ) ) {
			$this->settings['charset'][1] = self::CHARSETS[ $this->settings['charset'][1] ];
		}
		
		// Transform the cursorType setting if it's set by the user
		if ( ! is_numeric( $this->settings['cursorType'][1] )
		     && array_key_exists( $this->settings['cursorType'][1], self::CURSOR_TYPE ) ) {
			$this->settings['cursorType'][1] = self::CURSOR_TYPE[ $this->settings['cursorType'][1] ];
		}
		
		// Transform the syntaxHighlight setting if it's set by the user
		if ( ! is_numeric( $this->settings['syntaxHighlight'][1] )
		     && array_key_exists( $this->settings['syntaxHighlight'][1], self::SYNTAX_HIGHLIGHT ) ) {
			$this->settings['syntaxHighlight'][1] = self::SYNTAX_HIGHLIGHT[ $this->settings['syntaxHighlight'][1] ];
		}
		
		// Transform the customMacroToggle setting if it's set by the user
		if ( array_key_exists( $this->settings['customMacroToggle'][1], self::CUSTOM_MACRO )
		     && ! preg_match( '/^<\w+>$/', $this->settings['customMacroToggle'][1] ) ) {
			$this->settings['customMacroToggle'][1] = self::CUSTOM_MACRO[ $this->settings['customMacroToggle'][1] ];
		}
		
		// Transform the pasteDelay setting if it's set by the user
		if ( ! is_numeric( $this->settings['pasteDelay'][1] )
		     && array_key_exists( $this->settings['pasteDelay'][1], self::PASTE_DELAY ) ) {
			$this->settings['pasteDelay'][1] = self::PASTE_DELAY[ $this->settings['pasteDelay'][1] ];
		}
		
		// Transform the fontCharset setting if it's set by the user
		if ( ! is_numeric( $this->settings['fontCharset'][1] )
		     && array_key_exists( $this->settings['fontCharset'][1], self::FONT_CHARSETS ) ) {
			$this->settings['fontCharset'][1] = self::FONT_CHARSETS[ $this->settings['fontCharset'][1] ];
		}
	}
}
