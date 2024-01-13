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
	
	public function __construct() {
		$this->setDefaults();
	}
	
	private function setDefaults(): void {
		$this->settings = [
			'terminalFont'      => new Setting( 0, 'MobaFont' ),
			'fontSize'          => new Setting( 1, '10' ),
			'fontBold'          => new Setting( 2, self::DISABLED ),
			'terminalUnknown3'  => new Setting( 3, self::DISABLED ), // TODO Find out what this is
			'appendPath'        => new Setting( 4, self::ENABLED ),
			'charset'           => new Setting( 5, self::CHARSETS['UTF-8'] ),
			'foregroundRgb'     => new Setting( 6, '236,236,236' ),
			'backgroundRgb'     => new Setting( 7, '30,30,30' ),
			'cursorRgb'         => new Setting( 8, '180,180,192' ),
			'cursorType'        => new Setting( 9, self::CURSOR_TYPE['Block'] ),
			'backspaceSendsH'   => new Setting( 10, self::ENABLED ),
			'logOutput'         => new Setting( 11, self::DISABLED ),
			'logFolderPath'     => new Setting( 12, '' ),
			'terminalType'      => new Setting( 13, 'xterm' ), // See format .md for full list
			'lockTerminalTitle' => new Setting( 14, self::ENABLED ),
			'terminalUnknown15' => new Setting( 15, self::DISABLED ), // TODO Find out what this is
			'colorsScheme'      => new Setting( 16, '_Std_Colors_0_' ), // See format .md for alt format
			'terminalRows'      => new Setting( 17, '80' ),
			'terminalColumns'   => new Setting( 18, '24' ),
			'fixedDimensions'   => new Setting( 19, self::DISABLED ),
			'syntaxHighlight'   => new Setting( 20, self::SYNTAX_HIGHLIGHT['Standard keywords'] ),
			'boldIsBrighter'    => new Setting( 21, self::ENABLED ),
			'customMacroToggle' => new Setting( 22, self::CUSTOM_MACRO['None'] ),
			'customMacroText'   => new Setting( 23, '' ),
			'pasteDelay'        => new Setting( 24, self::PASTE_DELAY['Auto'] ),
			'fontCharset'       => new Setting( 25, self::FONT_CHARSETS['DEFAULT'] ),
			'fontAntialiasing'  => new Setting( 26, self::ENABLED ),
			'fontLigatures'     => new Setting( 27, self::ENABLED )
		];
	}
}
