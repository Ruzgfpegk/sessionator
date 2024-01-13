<?php
declare( strict_types=1 );

// Internal classes also use autoload through composer
require_once __DIR__ . '/../vendor/autoload.php';

// Included to use global MobaXterm format constants
use Ruzgfpegk\Sessionator\Formats\MobaXterm\SettingBlock;

// Included to use MobaXterm SSH format constants
use Ruzgfpegk\Sessionator\Formats\MobaXterm\SSH;

// Included to use SSH constants of the format below
use Ruzgfpegk\Sessionator\Sessionator;

$sessionList = new Sessionator;

$sessionList->newConnection( 'SSH' )
            ->setFolderName( 'Main Directory' ) // Mandatory setting
            ->setSessionName( 'SSH Line 1' ) // Mandatory setting
            ->setSessionIcon( 'Terminal_Debian' ) // Changes icon from default SSH 109 to 149
            ->setSessionComment( 'Comment with # character' ) // Testing the "#" replacement
            ->setHostName( 'localhost' ) // Mandatory setting
            ->setUserName( 'testUserSsh' )
            ->setSessionParam( 'x11Forwarding', SettingBlock::DISABLED ) // Changes index 5 from -1 to 0
            ->setSessionParam( 'fontSize', '12' ) // Changes terminal font size (index 1) from 10 to 12
            ->setSessionParam( 'privateKeyPath', 'C:\pkey.key' ) // Testing the "C:\" replacement
            ->addToList(); // Mandatory end call

$sessionList->newConnection( 'RDP' )
            ->setFolderName( 'Main Directory\RDP' ) // Mandatory setting
            ->setSessionName( 'RDP Line 1' ) // Mandatory setting
            ->setHostName( 'localhost' ) // Mandatory setting
            ->setUserName( 'testUserRdp' )
            ->setSessionParam( 'redirectDrives', SettingBlock::ENABLED ) // Changes index 6 from 0 to -1
            ->setSessionParam( 'fontSize', '14' ) // Changes terminal font size (index 1) from 10 to 14
            ->addToList(); // Mandatory end call

$sessionList->newConnection( 'SSH' )->setFolderName( 'Second Directory' )
            ->setSessionName( 'Second SSH Line 1' )->setHostName( 'localhost' )
            ->addToList();

$sessionList->exportAsText( 'MobaXterm' );
//$sessionList->exportAsHtml( 'MobaXterm' );
//$sessionList->download( 'MobaXterm' );
