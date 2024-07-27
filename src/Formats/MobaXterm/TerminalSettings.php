<?php
declare( strict_types=1 );

namespace Ruzgfpegk\Sessionator\Formats\MobaXterm;

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
	
	private const BOOLEANS = [
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
}
