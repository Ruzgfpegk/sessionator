# Sessionator


## Introduction

This PHP library aims to be used to create session files for :
* [MobaXterm](https://mobaxterm.mobatek.net/) (Windows+Wine, GUI)
* A self-contained Bash script (Linux, CLI, SSH/SFTP only at first)

This is still a work in progress and a development version, so things may change especially as new features are added.

The minimum PHP version targeted is currently 8.1.

If the Ds extension is available, DS\Set will be used for the storage of objects instead of arrays.

Required PHP extension for MobaXterm output:
* mbstring


## How to use

First, create an instance of the class:

```php
use Ruzgfpegk\Sessionator\Sessionator;
$sessionList = new Sessionator;
```

Then, for each session, chain everything you need:

* `$sessionList->newSession( 'type' )` : Set the new session type (see "Status" below to see what's currently supported for which format)
* `->setPathName( 'PathName' )` : The hierarchy where the session will be stored (MobaXterm uses the \ separator)
* `->setSessionName( 'SessionName' )` : Choose the name of the session under the path
* `->setSessionIcon( 'Icon_Name' )` : (Optional) Override the default icon for the session type (see Settings.md)
* `->setSessionComment( 'Comment' )` : (Optional) Set a comment to the session
* `->setHostName( 'target.server.local' )` : You have to connect do something, don't you?
* `->setUserName( 'root' )` : (Optional) The session username. Use "\<default\>" for the Windows one in MobaXterm.
* `->setSessionParam( 'parameterName', 'parameterValue' )` : (Optional) See Settings.md for the full list of settings
* `->addToList();` : You HAVE to put it at the end to save the built session in the stack

You can also import the contents of a session file in a given format:

* `$sessionList->importFromFile( 'MobaXtermFileToImport.mxtsessions', 'MobaXterm' )` : (Optional) To import connexions from an existing MobaXterm session file

You can start a new session from a reference session like this:

* `$sessionList->importFromSession( 'FolderName', 'SessionName' )` : Use 'FolderName\SessionName' as a reference session for this new one
* `->setSessionName( 'SessionName_Clone' )` : By default, '_Clone' will be appended at the end of the cloned session name

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

$sessionList = new Sessionator;

$sessionList->newSession( 'SSH' ) // Supported types: SSH and RDP
            ->setPathName( 'Main Directory\SSH' ) // Mandatory setting
            ->setSessionName( 'SSH Line 1' ) // Mandatory setting
            ->setSessionIcon( 'Terminal_Debian' ) // Changes icon from default SSH 109 to 149
            ->setSessionComment( 'Comment with # character' ) // Testing the "#" replacement
            ->setHostName( 'localhost' ) // Mandatory setting
            ->setUserName( 'testUserSsh' )
            ->setSessionParam( 'x11Forwarding', 'Disabled' ) // Changes index 5 from -1 to 0
            ->setSessionParam( 'fontSize', '12' ) // Changes terminal font size (index 1) from 10 to 12
            ->setSessionParam( 'privateKeyPath', 'C:\pkey.key' ) // Testing the "C:\" replacement
            ->addToList(); // Mandatory end call

$sessionList->importFromFile( 'MobaXtermFileToImport.mxtsessions', 'MobaXterm' );

$sessionList->download( 'MobaXterm' );
```


## Status

* :heavy_check_mark: Works to a usable extent
* :soon: Unstable WIP, or planned
* :no_entry_sign: Unsupported by the target application

| Software  | File export        | File import        | SSH                | RDP                | SFTP               | VNC                | Browser            |
|-----------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|
| MobaXterm | :heavy_check_mark: | :heavy_check_mark: | :heavy_check_mark: | :heavy_check_mark: | :heavy_check_mark: | :heavy_check_mark: | :heavy_check_mark: |
| Bash      | :heavy_check_mark: | :soon:             | :heavy_check_mark: | :soon:             | :heavy_check_mark: | :soon:             | :soon:             |


## Bash output

The Bash output produces a bash script that launches connections in the active window (by default), in screen or in tmux.

To quote its help:
```
 Usage: Connector.sh [-hk] [-t direct|screen|tmux] [-s SESSION] SERVER
 Starts a connection to SERVER and send it (or not) to a terminal manager with a named session
 
 -h               display this help and exit
 -k               keep the terminal active after starting the connection (tmux and screen only)
 -t TERMINAL      direct, screen or tmux (default: direct)
 -s SESSION       the name of the tmux or screen session to use (default: sessionator)
```

It only addresses servers by their name, so folders are ignored.

The amount of supported options is rather limited for now, so treat it as a preview.

### Bash completion

If you're only planning on using Bash, to enable completions the easiest method is to add the following snippet to your `~/.bashrc`,
and adjust the last line with your script name, here `Connector.sh`.

If you're planning on using Zsh with bashcompinit, or a cleaner organization, you should put it in a separate file,
for instance in your [local completions folder](https://github.com/scop/bash-completion/tree/main?tab=readme-ov-file#faq),
typically `~/.local/share/bash-completion/completions` (`mkdir -p` it if needed), with a name matching the connector script name
(the "complete" command at the end would only be used by Zsh's bashcompinit in this case).

```bash
_comp_sessionator() {
  local cur prev opts i servers sessions terminal command_path
  COMPREPLY=()
  cur="${COMP_WORDS[COMP_CWORD]}"
  prev="${COMP_WORDS[COMP_CWORD - 1]}"
  opts="-h -k -t -s"

  # Fetching the caller's path
  local command="${COMP_WORDS[0]}"
  command_path=$(command -v "${command}")

  # Completion for '-'
  if [[ ${cur} == -* ]]; then
    COMPREPLY=($(compgen -W "${opts}" -- ${cur}))
    return 0
  fi

  # Completion for '-t'
  if [[ ${prev} == -t ]]; then
    COMPREPLY=($(compgen -W "direct screen tmux" -- ${cur}))
    return 0
  fi

  # Preparing completion for '-s'
  for ((i = 1; i < ${#COMP_WORDS[@]}; i++)); do
    if [[ "${COMP_WORDS[i]}" == "-t" ]]; then
      terminal="${COMP_WORDS[i + 1]}"
      break
    fi
  done

  # Completion for '-s'
  if [[ "${prev}" == "-s" ]]; then
    if [[ "${terminal}" == "screen" ]]; then
      # List of active screen sessions matching the name
      sessions=$(screen -ls ${cur} | awk '/\t/ {print $1}' | cut -d. -f2)
    elif [[ "${terminal}" == "tmux" ]]; then
      # List of active tmux sessions matching the name
      sessions=$(tmux list-sessions -F "#{session_name}" 2>/dev/null | grep -i "^${cur}")
    fi
    COMPREPLY=($(compgen -W "${sessions}" -- "${cur}"))
    return 0
  fi

  # Completion for the server name
  if [[ ${COMP_CWORD} -eq 1 || "${prev}" != "-s" ]]; then
    servers=$(grep -o "^populate_server_info_[^[:space:]]* \"${cur}[^\"]*\"" "${command_path}" | cut -d'"' -f2)
    COMPREPLY=($(compgen -W "${servers}" -- "${cur}"))
    return 0
  fi
}
complete -F _comp_sessionator Connector.sh
```

### Zsh completion

At the end of your `~/.zshrc` file, to be able to use the bash completion script, add the following with the right path:

```zsh
autoload bashcompinit && bashcompinit
[[ -r ~/.local/share/bash-completion/completions/Connector.sh ]] && source ~/.local/share/bash-completion/completions/Connector.sh
```


## Possible evolutions

Right now the library allows you to define MobaXterm sessions, import an existing file if needed, and export everything.

The roadmap could be, in order:

* More documentation
* Import and export for other session managers


## Workflow

In this project, it is assumed that a session defines a connection.

In the Sessionator class, the newSession('target') method returns an object that extends the Connections\Common
abstract class with the specificities of "target" sessions (SSH, RDP, SFTP, VNC, etc.).

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
