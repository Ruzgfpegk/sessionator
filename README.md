# Sessionator


## Introduction

This PHP library aims to be used to create session files for :
* [MobaXterm](https://mobaxterm.mobatek.net/) (Windows+Wine, GUI)

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
* `$sessionList->saveAsFile( 'MobaXterm', 'out.mxtsessions' );` : To save as a local file on the PHP environment

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


## Status

* :heavy_check_mark: Works to a usable extent
* :soon: Unstable WIP, or planned
* :no_entry_sign: Unsupported by the target application

| Software  | File format support | SSH                | RDP                | SFTP               | VNC                | Browser            |
|-----------|---------------------|--------------------|--------------------|--------------------|--------------------|--------------------|
| MobaXterm | :heavy_check_mark:  | :heavy_check_mark: | :heavy_check_mark: | :heavy_check_mark: | :heavy_check_mark: | :heavy_check_mark: |


## Possible evolutions

Right now the library is rather basic: you define sessions and export everything.

The roadmap besides planned support could be, in order:

* Add a global "disabled/enabled" constant for the connections (for setSessionParam)
* More documentation for adding to the codebase
* Session file import
* other session managers import and export

## Architecture

The terms "session" and "connection" are used interchangeably, as a session defines a connection.

In the Sessionator class, the newConnection('target') method returns an object that extends the Connections\Common
abstract class with the specificities of "target" connections.

On the returned object, the methods setFolderName('forderName'), setSessionName('sessionName')
and setHostName('hostName') set the basic properties.

Some target connections have additional methods: for instance, SSH has setUserName('userName').

The method setSessionParam('paramName', 'paramValue') can be used to set advanced parameters, stored into the
sessionParams associative array property of the "target connection" object.

The addToList() method adds the current object to the "stack of sessions" in the Sessionator object.

Once all sessions are defined, they can be exported using one of these methods from the Sessionator object :
* exportAsText( 'OutputFormat' ) to export as text, separating lines with the OS line separator
* exportAsHtml( 'OutputFormat' ) to export as HTML, separating lines with "\<br\>" and the OS line separator
* download( 'OutputFormat' ) to save as a file when called from a webserver

All these methods pass the array of sessions to an object "Formats\OutputFormat\Output" by calling its relevant method.

The expected behavior is that, for each element of sessionParam, the custom settings are applied on top of the defaults
defined in the Output object, and the final stream is computed from the result.


## Caveats

* Folders are exported alphabetically.
