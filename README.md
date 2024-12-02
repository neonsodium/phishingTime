# PhishingTime

**PhishingTime** is a script designed for macOS to set up a phishing server using a PHP local server and Cloudflare's tunnel service. It monitors and logs user activity, such as IPs and credentials, while presenting a custom phishing URL.

---

## Features

- **PHP Local Server**: Hosts the phishing page on a local server.
- **Cloudflare Tunnel**: Exposes the local server to the internet.
- **Real-Time Monitoring**: Tracks IP addresses, user agents, and credentials in `ip.txt` and `passwd.txt`.
- **Custom URL**: Generates a URL in the format `https://instagram.com@<cloudflare_url>` for phishing.
- **Logging**: Logs data for analysis and creates backups in the `auth/` folder.

---

## Requirements

### Tools
- **PHP**: Used to host the local server.
- **Cloudflared**: Required to expose the local server via a secure tunnel.
- **pbcopy**: macOS utility for copying data to the clipboard.

### Files
Ensure the following files are present:
- `ip.txt`: Temporary file for IP addresses.
- `passwd.txt`: Temporary file for credentials.
- `auth/log`: Log file for Cloudflare.
- `auth/ip.txt`: Backup for IPs.
- `auth/passwd.txt`: Backup for credentials.

---

## Installation

### Step 1: Install PHP
Ensure PHP is installed:
```bash
php -v
```
If not installed, use:
```bash
brew install php
```

### Step 2: Install Cloudflared
Install Cloudflared using Homebrew:
```bash
brew install cloudflared
```

### Step 3: Clone the Repository
Clone or download the script to your machine:
```bash
git clone https://github.com/your-repo/phishingtime.git
cd phishingtime
```

---

## Usage

### Step 1: Run the Script
Make the script executable:
```bash
chmod +x phishingtime.sh
```

Run the script:
```bash
./phishingtime.sh
```

### Step 2: Access the Custom URL
The script will:
1. Start a PHP server on `127.0.0.1:8008`.
2. Launch a Cloudflare tunnel and generate a phishing URL.
3. Copy the phishing URL to the clipboard.

Paste the URL to share it.

### Step 3: Monitor Logs
Real-time logs for IPs and credentials will be displayed in the terminal. Data will be saved in:
- `auth/ip.txt` (IP logs)
- `auth/passwd.txt` (Credential logs)

---

## Stopping the Script
To stop the script:
1. Press `Ctrl + C`.
2. The script will stop the PHP server and Cloudflared.

---

## Notes
- **Compatibility**: This script is designed specifically for macOS.
- **For Educational Purposes Only**: Misuse of this script for malicious purposes may result in legal consequences.

---

## Troubleshooting

### Issue: Cloudflared Tunnel URL Not Found
- Check if Cloudflared is installed and in the system path.
- Ensure the `auth/log` file has the correct permissions.
- Increase the timeout duration in the script if needed.

### Issue: PHP Server Not Starting
- Confirm that no other process is using `127.0.0.1:8008`.
- Check the PHP installation.

---

**Disclaimer**: This script is for educational purposes only. Use it responsibly. Misuse may violate laws and result in severe penalties.
