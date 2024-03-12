<?php
declare( strict_types=1 );

// This is both a testbed and an example.

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

$sessionList->newConnection( 'SSH' )->setFolderName( 'Second Directory\SSH' )
            ->setSessionName( '2nd Dir SSH Line 1' )->setHostName( 'otherhost' )
            ->addToList();

// Parameters can also be given one after another on the object returned by newConnection();
// This is especially useful if you want to specify the object name to the IDE for efficient autocompletion.
/** @var \Ruzgfpegk\Sessionator\Connections\SSH $secondDirSshLine2 */
$secondDirSshLine2 = $sessionList->newConnection( 'SSH' );
$secondDirSshLine2->setFolderName( 'Second Directory\SSH' );
$secondDirSshLine2->setSessionName( '2nd Dir SSH Line 2' );
$secondDirSshLine2->setHostName( 'localhost' );
$secondDirSshLine2->addToList();

$sessionList->exportAsText( 'MobaXterm' );
$sessionList->saveAsFile( 'MobaXterm', 'out.mxtsessions' );
//$sessionList->exportAsHtml( 'MobaXterm' );
//$sessionList->download( 'MobaXterm' );


// The above code outputs the following:

/*
[Bookmarks]
SubRep=
ImgNum=42

[Bookmarks_1]
SubRep=Main Directory
ImgNum=41
SSH Line 1=#149#0%localhost%22%testUserSsh%%0%-1%%%%%0%0%0%_CurrentDrive_:\pkey.key%%-1%0%0%0%%1080%%0%0%1%%0%%%%0%-1%-1%0#MobaFont%12%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%1%-1%-1#0#Comment with __DIEZE__ character#-1

[Bookmarks_2]
SubRep=Main Directory\RDP
ImgNum=41
RDP Line 1=#91#4%localhost%3389%testUserRdp%0%0%-1%0%-1%0%0%-1%%%%%0%0%%-1%%-1%-1%0%-1%0%-1%0%0%0%0#MobaFont%14%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%1%-1%-1#0##-1

[Bookmarks_3]
SubRep=Second Directory
ImgNum=41

[Bookmarks_4]
SubRep=Second Directory\SSH
ImgNum=41
2nd Dir SSH Line 1=#109#0%otherhost%22%%%-1%-1%%%%%0%-1%0%%%-1%0%0%0%%1080%%0%0%1%%0%%%%0%-1%-1%0#MobaFont%10%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%1%-1%-1#0##-1
2nd Dir SSH Line 2=#109#0%localhost%22%%%-1%-1%%%%%0%-1%0%%%-1%0%0%0%%1080%%0%0%1%%0%%%%0%-1%-1%0#MobaFont%10%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%1%-1%-1#0##-1
*/
