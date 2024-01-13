# Sessionator

## Introduction

This PHP library can be used to create session files for MobaXterm.

This is still a work in progress and a development version, so things may change especially as new features are added.

The minimum PHP version targeted is currently 7.4 and the mbstring extension is required.

## How to use

First, create an instance of the class:

```php
use Ruzgfpegk\Sessionator\Sessionator;
$sessionList = new Sessionator;
```

Then, for each session, chain everything you need:

* `$sessionList->newConnection( 'type' )` : Set the new connection type (currently supported: SSH and RDP)
* `->setFolderName( 'Folder' )` : The hierarchy where the connection will be stored (MobaXterm uses the \ separator)
* `->setSessionName( 'Session' )` : Choose the name of the connection under the folder
* `->setSessionIcon( 'Icon_Name' )` : (Optional) Override the default icon for the connection type (see Settings.md)
* `->setSessionComment( 'Comment' )` : (Optional) Set a comment to the connection
* `->setHostName( 'target.server.local' )` : You have to connect do something, don't you?
* `->setUserName( 'root' )` : (Optional) The connection username. Use "\<default\>" for the Windows one in MobaXterm.
* `->setSessionParam( 'parameterName', 'parameterValue' )` : (Optional) See Settings.md for the full list of settings
* `->addToList();` : You HAVE to put it at the end to save the built session in the stack

When you've added all the connections you wanted, you can export the file this way:

* `$sessionList->exportAsText( 'MobaXterm' );` : To export to the terminal (beware of the file format!)
* `$sessionList->exportAsHtml( 'MobaXterm' );` : To export to a webpage (again, beware of the file format!) (TODO:
  proper HTML conversion)
* `$sessionList->download( 'MobaXterm' );` : To save as a file (for MobaXterm, a CRLF Windows-1252 file), when called
  from a webserver

Which translates to this working example, in PHP:

```php
use Ruzgfpegk\Sessionator\Sessionator;
use Ruzgfpegk\Sessionator\Formats\MobaXterm\SettingBlock; // Use global MobaXterm format constants

$sessionList = new Sessionator;

$sessionList->newConnection( 'SSH' ) // Supported types: SSH and RDP
            ->setFolderName( 'Main Directory\SSH' ) // Mandatory setting
            ->setSessionName( 'SSH Line 1' ) // Mandatory setting
            ->setSessionIcon( 'Terminal_Debian' ) // Changes icon from default SSH 109 to 149
            ->setSessionComment( 'Comment with # character' ) // Testing the "#" replacement
            ->setHostName( 'localhost' ) // Mandatory setting
            ->setUserName( 'testUserSsh' )
            ->setSessionParam( 'x11Forwarding', SettingBlock::DISABLED ) // Changes index 5 from -1 to 0
            ->setSessionParam( 'fontSize', '12' ) // Changes terminal font size (index 1) from 10 to 12
            ->setSessionParam( 'privateKeyPath', 'C:\pkey.key' ) // Testing the "C:\" replacement
            ->addToList(); // Mandatory end call

$sessionList->download( 'MobaXterm' );
```

## Possible evolutions

Right now the library is rather basic: you define sessions and export everything.

The roadmap could be, in order:

* A few low-importance session settings (marked as TODO in Formats/MobaXterm/Output.php)
* FTP session support
* SFTP session support
* Some refactoring to get a clearer view of every part
* More documentation for adding to the codebase
* mRemoteNG export
* MobaXterm import
* mRemoteNG import
* other session managers import and export

## Caveats

* Folders are exported alphabetically.
* 