const xhr = new XMLHttpRequest();
function setEventsToProfile() {
    document.querySelectorAll('.user-add-view').forEach(element => {
        if (!element.dataset.eventAdded) {
            element.addEventListener('click', checkInteraction);
            element.dataset.eventAdded = true;
        }
    });
}

function checkInteraction(e) {
    const xhr = new XMLHttpRequest(); // Moved inside checkInteraction function
    const username = e.target.id.split('-')[2]; // Adjusted index to 1
    const operation = e.target.id.split('-')[0];
    xhr.open('POST', './interactUser.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                e.target.textContent = "Cancel";
            } else {
                console.error("Server error", xhr.responseText); // Changed console.log to console.error for errors
            }
        }
    };

    xhr.send(`username=${username}&operation=${operation}`);
}

setEventsToProfile();
