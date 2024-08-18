#!/bin/bash

# This script has been generated using the Sessionator library ( https://github.com/Ruzgfpegk/sessionator )
# Keep in mind that it's going to be rewritten at each export

# Globals:
declare -A SERVER_INFO
declare CONNECTION_COMMAND

#######################################
# The list of options for the script
# Arguments:
#  None
#######################################
show_help() {
  echo "Usage: ${0##*/} [-hk] [-t direct|screen|tmux] [-s SESSION] SERVER"
  echo "Starts a connection to SERVER and send it (or not) to a terminal manager with a named session"
  echo ""
  echo "  -h               display this help and exit"
  echo "  -k               keep the terminal active after starting the connection (tmux and screen only)"
  echo "  -t TERMINAL      direct, screen or tmux (default: direct)"
  echo "  -s SESSION       the name of the tmux or screen session to use (default: sessionator)"
}

#######################################
# Helper function to populate SSH server info
# Globals:
#   SERVER_INFO
# Arguments:
#   The server name
#   The host to connect to
#   The port to connect to
#   The user to connect as
#   The x11Forwarding setting (empty string if disabled)
#   The compression setting (empty string if disabled)
#   The privateKeyPath setting (empty string if disabled)
#   The sshGatewayHostList setting (empty string if disabled)
#   The sshGatewayPortList setting (empty string if disabled)
#   The sshGatewayUserList setting (empty string if disabled)
#   The sshGatewayPrivateKeyList setting (empty string if disabled)
# TODO: Implement more options and handle more than one gateway
#######################################
populate_server_info_ssh() {
  local server="$1"
  local host="$2"
  local port="$3"
  local user="$4"
  local x11Forwarding="$5"
  local compression="$6"
  local privateKeyPath="$7"
  local sshGatewayHostList="$8"
  local sshGatewayPortList="$9"
  local sshGatewayUserList="${10}"
  local sshGatewayPrivateKeyList="${11}"

  SERVER_INFO[$server, mode]="ssh"
  SERVER_INFO[$server, host]="$host"
  SERVER_INFO[$server, port]="$port"
  SERVER_INFO[$server, user]="$user"
  SERVER_INFO[$server, x11Forwarding]="$x11Forwarding"
  SERVER_INFO[$server, compression]="$compression"
  SERVER_INFO[$server, privateKeyPath]="$privateKeyPath"
  SERVER_INFO[$server, sshGatewayHostList]="$sshGatewayHostList"
  SERVER_INFO[$server, sshGatewayPortList]="$sshGatewayPortList"
  SERVER_INFO[$server, sshGatewayUserList]="$sshGatewayUserList"
  SERVER_INFO[$server, sshGatewayPrivateKeyList]="$sshGatewayPrivateKeyList"
}

#######################################
# Helper function to populate SFTP server info
# Globals:
#   SERVER_INFO
# Arguments:
#   The server name
#   The host to connect to
#   The port to connect to
#   The user to connect as
#   The preserveFileDates setting (empty string if disabled)
#   The compression setting (empty string if disabled)
#   The privateKeyPath setting (empty string if disabled)
#   The sshGatewayHostList setting (empty string if disabled)
#   The sshGatewayPortList setting (empty string if disabled)
#   The sshGatewayUserList setting (empty string if disabled)
#   The sshGatewayPrivateKeyList setting (empty string if disabled)
# TODO: Implement more options and handle more than one gateway
#######################################
populate_server_info_sftp() {
  local server="$1"
  local host="$2"
  local port="$3"
  local user="$4"
  local preserveFileDates="$5"
  local compression="$6"
  local privateKeyPath="$7"
  local sshGatewayHostList="$8"
  local sshGatewayPortList="$9"
  local sshGatewayUserList="${10}"
  local sshGatewayPrivateKeyList="${11}"

  SERVER_INFO[$server, mode]="sftp"
  SERVER_INFO[$server, host]="$host"
  SERVER_INFO[$server, port]="$port"
  SERVER_INFO[$server, user]="$user"
  SERVER_INFO[$server, preserveFileDates]="$preserveFileDates"
  SERVER_INFO[$server, compression]="$compression"
  SERVER_INFO[$server, privateKeyPath]="$privateKeyPath"
  SERVER_INFO[$server, sshGatewayHostList]="$sshGatewayHostList"
  SERVER_INFO[$server, sshGatewayPortList]="$sshGatewayPortList"
  SERVER_INFO[$server, sshGatewayUserList]="$sshGatewayUserList"
  SERVER_INFO[$server, sshGatewayPrivateKeyList]="$sshGatewayPrivateKeyList"
}

#######################################
# Generate the SSH connection command
# Globals:
#   SERVER_INFO
#   CONNECTION_COMMAND
# Arguments:
#   The server to connect to, as stored in SERVER_INFO
# TODO: Implement more options
#######################################
ssh_connection_command() {
  local server="$1"
  local host="${SERVER_INFO[$server, host]}"
  local port="${SERVER_INFO[$server, port]}"
  local user="${SERVER_INFO[$server, user]}"
  local x11Forwarding="${SERVER_INFO[$server, x11Forwarding]}"
  local compression="${SERVER_INFO[$server, compression]}"
  local privateKeyPath="${SERVER_INFO[$server, privateKeyPath]}"
  local sshGatewayHostList="${SERVER_INFO[$server, sshGatewayHostList]}"
  local sshGatewayPortList="${SERVER_INFO[$server, sshGatewayPortList]}"
  local sshGatewayUserList="${SERVER_INFO[$server, sshGatewayUserList]}"
  local sshGatewayPrivateKeyList="${SERVER_INFO[$server, sshGatewayPrivateKeyList]}"

  local ssh_options=""

  if [[ -n "$sshGatewayHostList" && -n "$sshGatewayPortList" && -n "$sshGatewayUserList" ]]; then
    ssh_options="$ssh_options -o ProxyCommand=\"ssh -W %h:%p -q"
    if [[ -n "$sshGatewayPrivateKeyList" ]]; then
      ssh_options="$ssh_options -i $sshGatewayPrivateKeyList"
    fi
    ssh_options="$ssh_options $sshGatewayUserList@$sshGatewayHostList\""
  fi
  if [[ "$compression" -eq 1 ]]; then
    ssh_options="$ssh_options -C"
  fi
  if [[ "$x11Forwarding" -eq 1 ]]; then
    ssh_options="$ssh_options -X"
  fi
  if [[ -n "$privateKeyPath" ]]; then
    ssh_options="$ssh_options -i $privateKeyPath"
  fi
  if [[ "$port" != "22" ]]; then
    ssh_options="$ssh_options -p $port"
  fi
  if [[ -z "$user" ]]; then
    # Get the current user if none is set
    user=$(whoami)
  fi

  CONNECTION_COMMAND="ssh${ssh_options} $user@$host"
}

#######################################
# Generate the SFTP connection command
# Globals:
#   SERVER_INFO
#   CONNECTION_COMMAND
# Arguments:
#   The server to connect to, as stored in SERVER_INFO
# TODO: Implement more options
#######################################
sftp_connection_command() {
  local server="$1"
  local host="${SERVER_INFO[$server, host]}"
  local port="${SERVER_INFO[$server, port]}"
  local user="${SERVER_INFO[$server, user]}"
  local preserveFileDates="${SERVER_INFO[$server, preserveFileDates]}"
  local compression="${SERVER_INFO[$server, compression]}"
  local privateKeyPath="${SERVER_INFO[$server, privateKeyPath]}"
  local sshGatewayHostList="${SERVER_INFO[$server, sshGatewayHostList]}"
  local sshGatewayPortList="${SERVER_INFO[$server, sshGatewayPortList]}"
  local sshGatewayUserList="${SERVER_INFO[$server, sshGatewayUserList]}"
  local sshGatewayPrivateKeyList="${SERVER_INFO[$server, sshGatewayPrivateKeyList]}"

  local sftp_options=""

  if [[ -n "$sshGatewayHostList" && -n "$sshGatewayPortList" && -n "$sshGatewayUserList" ]]; then
    sftp_options="$sftp_options -o ProxyCommand=\"ssh -W %h:%p -q"
    if [[ -n "$sshGatewayPrivateKeyList" ]]; then
      sftp_options="$sftp_options -i $sshGatewayPrivateKeyList"
    fi
    sftp_options="$sftp_options $sshGatewayUserList@$sshGatewayHostList\""
  fi
  if [[ "$compression" -eq 1 ]]; then
    sftp_options="$sftp_options -C"
  fi
  if [[ "$x11Forwarding" -eq 1 ]]; then
    sftp_options="$sftp_options -X"
  fi
  if [[ "$preserveFileDates" -eq 1 ]]; then
    sftp_options="$sftp_options -p"
  fi
  if [[ -n "$privateKeyPath" ]]; then
    sftp_options="$sftp_options -i $privateKeyPath"
  fi
  if [[ "$port" != "22" ]]; then
    sftp_options="$sftp_options -P $port"
  fi
  if [[ -z "$user" ]]; then
    # Get the current user if none is set
    user=$(whoami)
  fi

  CONNECTION_COMMAND="sftp${sftp_options} $user@$host"
}

#######################################
# Execute the command through screen
# Globals:
#   None
# Arguments:
#   The command to execute
#   The window name
#   The session name
#   The keep terminal flag
#######################################
command_to_screen() {
  local command="$1"
  local window_name="$2"
  local session_name="$3"
  local keep_terminal="$4"

  # Store error code of session listing:
  (screen -list | grep -q "\.$session_name")
  local session_missing="$?"
  (screen -list | grep "\.$session_name" | grep -q "(Attached)")
  local session_unattached="$?"

  # Choose the right command depending on if the session exists or not
  if [[ $session_missing -eq 1 ]]; then
    # No session : create the session and execute the command in it
    screen -dmS "$session_name" -t "$window_name" $command
  else
    # Session exists : send the window/command to the session
    screen -S "$session_name" -X screen -t "$window_name" $command
  fi

  # If keep_terminal is empty/false and the session is not attached, switch to the session in the same shell
  if [[ -z "$keep_terminal" && $session_unattached -eq 1 ]]; then
    exec screen -r "$session_name" -p "$window_name"
  fi
}

#######################################
# Execute the command through tmux
# Globals:
#   None
# Arguments:
#   The command to execute
#   The window name
#   The session name
#   The keep terminal flag
#######################################
command_to_tmux() {
  local command="$1"
  local window_name="$2"
  local session_name="$3"
  local keep_terminal="$4"

  local session_attached

  # Store output of filtered list-sessions
  session_attached=$(tmux list-sessions -F '#{session_attached}' -f "#{session_name} eq '${session_name}' and #{session_attached}==1" 2>/dev/null)
  local session_missing="$?"

  # Choose the right command depending on if the session exists or not
  if [[ $session_missing -eq 1 ]]; then
    # No session : create the session and execute the command in it
    tmux new-session -d -s "$session_name" -n "$window_name" "$command"
  else
    # Session exists : send the window/command to the session
    tmux new-window -t "$session_name" -n "$window_name" "$command"
  fi

  # If keep_terminal is empty/false and the session isn't already attached, we switch to the session in the same shell
  if [[ -z "$keep_terminal" && $session_attached -eq 0 ]]; then
    exec tmux attach-session -t "$session_name"
  fi
}

#######################################
# Timestamped STDOUT function from GSSG
# Arguments:
#  None
#######################################
err() {
  echo "[$(date +'%Y-%m-%dT%H:%M:%S%z')]: $*" >&2
}

#######################################
# Main function
# Globals:
#   SERVER_INFO
#   CONNECTION_COMMAND
# Arguments:
#   See the show_help function
#######################################
main() {
  local opt
  local server_name
  local session_name="sessionator"
  local terminal="direct"
  local keep_terminal=""
  local connection_mode

  while getopts "hkt:s:" opt; do
    case $opt in
    h)
      show_help
      exit 0
      ;;
    k)
      keep_terminal="true"
      ;;
    t)
      terminal="$OPTARG"
      ;;
    s)
      session_name="$OPTARG"
      ;;
    *)
      show_help >&2
      exit 1
      ;;
    esac
  done
  shift "$((OPTIND - 1))"

  if [[ $# -lt 1 ]]; then
    show_help
    exit 1
  fi

  server_name="$1"

  # Populate the SERVER_INFO array
  initialize_server_list

  if [[ -z "${SERVER_INFO[$server_name, mode]}" ]]; then
    err "Server information for $server_name is not found."
    exit 1
  fi

  # From SERVER_INFO :
  connection_mode="${SERVER_INFO[$server_name, mode]}"

  # Generate the connection command
  case "$connection_mode" in
  ssh)
    ssh_connection_command "$server_name"
    ;;
  sftp)
    sftp_connection_command "$server_name"
    ;;
  *)
    err "Invalid mode for $server_name: $connection_mode. Supported modes are 'ssh' and 'sftp'."
    exit 1
    ;;
  esac

  # In case of emergency, uncomment to check:
  #echo "CONNECTION_COMMAND: $CONNECTION_COMMAND"

  # Execute the final command
  case "$terminal" in
  direct) # Exits the script and executes the command directly
    exec $CONNECTION_COMMAND
    ;;
  screen)
    command_to_screen "$CONNECTION_COMMAND" "$server_name" "$session_name" "$keep_terminal"
    ;;
  tmux)
    command_to_tmux "$CONNECTION_COMMAND" "$server_name" "$session_name" "$keep_terminal"
    ;;
  *)
    err "Invalid terminal: $terminal. Supported terminals are 'direct', 'screen' and 'tmux'."
    exit 1
    ;;
  esac
}

#######################################
# The list of commands to populate the SERVER_INFO array, generated by the library
# Example of call: populate_server_info_ssh "example_ssh" "example.com" "22" "ssh_user" "" "" ""
# Globals:
#   None
# Arguments:
#   None
#######################################
initialize_server_list() {
  ## START_SERVERS ##
  # The following sample entries will be overwritten by the library
  populate_server_info_ssh "example_ssh" "127.0.0.1" "22" "ssh_user" "0" "1" "" "" "" "" ""
  populate_server_info_sftp "example_sftp" "example.net" "2222" "sftp_user" "1" "1" "" "" "" "" ""
  ## END_SERVERS ##
}

main "$@"
