function FindProxyForURL(url, host) {
	<?
if (rand(1,100)==50)
    echo 'return "DIRECT";';
else
    echo 'return "PROXY 192.168.0.6:3128;"';
?>
}