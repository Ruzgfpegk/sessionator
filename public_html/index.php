<?php
declare( strict_types=1 );

// This is both a testbed and an example.

// Internal classes also use autoload through composer
require_once __DIR__ . '/../vendor/autoload.php';

// Main class
use Ruzgfpegk\Sessionator\Sessionator;

$sessionList = new Sessionator;

$sessionList->newConnection( 'SSH' )
            ->setFolderName( 'Main Directory' ) // Mandatory setting
            ->setSessionName( 'SSH Line 1' ) // Mandatory setting
            ->setSessionIcon( 'Terminal_Debian' ) // Changes icon from default SSH 109 to 149
            ->setSessionComment( 'Comment with # character, a quote \' and a double-quote "' ) // Testing the "#" replacement
            ->setHostName( 'localhost' ) // Mandatory setting
            ->setUserName( 'testUserSsh' )
            ->setSessionParam( 'x11Forwarding', 'Disabled' ) // Changes index 5 from -1 to 0
            ->setSessionParam( 'fontSize', '12' ) // Changes terminal font size (index 1) from 10 to 12
            ->setSessionParam( 'privateKeyPath', 'C:\pkey.key' ) // Testing the "C:\" replacement
            ->addToList(); // Mandatory end call

$sessionList->newConnection( 'SSH' )
            ->setFolderName( 'Main Directory\Subdir' )
            ->setSessionName( 'Sub Dir SSH Line 1' )
            ->setHostName( 'otherhost' )
            ->addToList();

$sessionList->newConnection( 'SFTP' )
            ->setFolderName( 'File transfer' )
            ->setSessionName( 'SFTP Connection' )
            ->setHostName( 'sftphost' ) // Mandatory setting
            ->setUserName( 'sftpuser' )
            ->setSessionParam( 'privateKeyPath', 'C:\pkey.key' )
            ->addToList();

$sessionList->newConnection( 'RDP' )
            ->setFolderName( 'Graphical sessions' ) // Mandatory setting
            ->setSessionName( 'RDP Connection' ) // Mandatory setting
            ->setHostName( 'localhost' ) // Mandatory setting
            ->setUserName( 'testUserRdp' )
            ->setSessionParam( 'redirectDrives', 'Enabled' ) // Changes index 6 from 0 to -1
            ->setSessionParam( 'fontSize', '14' ) // Changes terminal font size (index 1) from 10 to 14
            ->addToList(); // Mandatory end call

$sessionList->newConnection( 'VNC' )
            ->setFolderName( 'Graphical sessions' )
            ->setSessionName( 'VNC Connection' )
            ->setHostName( 'vnchost' ) // Mandatory setting
            ->addToList();

$sessionList->newConnection( 'Browser' )
            ->setFolderName( 'Browser sessions' )
            ->setSessionName( 'MFing Website through Edge' )
            ->setHostName( 'https://motherfuckingwebsite.com/' ) // Mandatory setting
            ->setSessionParam( 'browserEngine', 'Microsoft Edge' )
            ->addToList();

$sessionList->newConnection( 'Browser' )
            ->setFolderName( 'Browser sessions' )
            ->setSessionName( 'Programming MFer through Chrome' )
            ->setHostName( 'https://programming-motherfucker.com/' ) // Mandatory setting
            ->setSessionParam( 'browserEngine', 'Google Chrome' )
            ->addToList();

$sessionList->newConnection( 'Browser' )
            ->setFolderName( 'Browser sessions' )
            ->setSessionName( 'Perdu through Firefox' )
            ->setHostName( 'https://www.perdu.com/' ) // Mandatory setting
            ->setSessionParam( 'browserEngine', 'Mozilla Firefox' )
            ->addToList();

$sessionList->newConnection( 'Browser' )
            ->setFolderName( 'Browser sessions' )
            ->setSessionName( 'PHP.com through IE' )
            ->setHostName( 'https://www.php.net/' ) // Mandatory setting
            ->setSessionParam( 'browserEngine', 'Internet Explorer' )
            ->addToList();

// Parameters can also be given one after another on the object returned by newConnection();
// This is especially useful if you want to specify the object name to the IDE for efficient autocompletion.
/** @var \Ruzgfpegk\Sessionator\Connections\SSH $secondDirSshLine2 */
$secondDirSshLine2 = $sessionList->newConnection( 'SSH' );
$secondDirSshLine2->setFolderName( 'Main Directory\Subdir' );
$secondDirSshLine2->setSessionName( 'Sub Dir SSH Line 2' );
$secondDirSshLine2->setHostName( 'localhost' );
$secondDirSshLine2->addToList();

// Batch of CLI tests to try each export format:
$sessionList->saveAsFile( 'MobaXterm', 'Sessions-MobaXterm.mxtsessions' );


// For quick web tests:
#$sessionList->exportAsHtml( 'MobaXterm' );
#$sessionList->download( 'MobaXterm', 'Sessions-MobaXterm.mxtsessions' );


// $sessionList->exportAsText( 'MobaXterm' ) outputs the following:
/*
[Bookmarks]
SubRep=
ImgNum=42

[Bookmarks_1]
SubRep=File transfer
ImgNum=41
SFTP Connection=#140#7%sftphost%22%sftpuser%-1%0%%0%0%_CurrentDrive_:\pkey.key%0%%1080%%%%-1#MobaFont%10%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%1%-1%-1#0##-1

[Bookmarks_2]
SubRep=Graphical sessions
ImgNum=41
RDP Connection=#91#4%localhost%3389%testUserRdp%0%0%-1%0%-1%0%0%-1%%%%%0%0%%-1%%-1%-1%0%-1%0%-1%0%0%0%0#MobaFont%14%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%1%-1%-1#0##-1
VNC Connection=#128#5%vnchost%5900%-1%0%%%%%-1%0%0%%0%%1080%%#MobaFont%10%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%1%-1%-1#0##-1

[Bookmarks_3]
SubRep=Main Directory
ImgNum=41
SSH Line 1=#149#0%localhost%22%testUserSsh%%0%-1%%%%%0%0%0%_CurrentDrive_:\pkey.key%%-1%0%0%0%%1080%%0%0%1%%0%%%%0%-1%-1%0#MobaFont%12%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%1%-1%-1#0#Comment with __DIEZE__ character#-1

[Bookmarks_4]
SubRep=Main Directory\Subdir
ImgNum=41
Sub Dir SSH Line 1=#109#0%otherhost%22%%%-1%-1%%%%%0%-1%0%%%-1%0%0%0%%1080%%0%0%1%%0%%%%0%-1%-1%0#MobaFont%10%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%1%-1%-1#0##-1
Sub Dir SSH Line 2=#109#0%localhost%22%%%-1%-1%%%%%0%-1%0%%%-1%0%0%0%%1080%%0%0%1%%0%%%%0%-1%-1%0#MobaFont%10%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%1%-1%-1#0##-1
*/
