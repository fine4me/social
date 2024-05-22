//set interval code to fire every 5 seconds
function notificationCount(){

    const xhr = new XMLHttpRequest();
    xhr.open('POST', './handlenotification.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`checking_notif=1`);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                let unread_count = parseInt(xhr.responseText.trim());
                if (unread_count > 0) {
                    document.querySelector('#notification_count').textContent = unread_count;
                } else {
                    document.querySelector('#notification_count').textContent = ''
                }
            } else {
                console.log("Server error", xhr.responseText);
            }
        }
    }
}
setInterval(notificationCount, 500);