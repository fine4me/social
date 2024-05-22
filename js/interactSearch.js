function setEventsToProfile() {
    // Add event listener to elements with the class 'like-post' and trigger checkInteraction function on click, do not add if event handler is already attached
    document.querySelectorAll('.user-add-view').forEach(element => {
        if (!element.dataset.eventAdded) { // Ensure the event handler is not added multiple times
            element.addEventListener('click', checkInteraction);
            element.dataset.eventAdded = true; // Mark as event added
        }
    });
}

function checkInteraction(e) {
    let username = e.target.id.split('-')[2];
    let operation = e.target.id.split('-')[0];
    xhr.open('POST', './interactUser.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`username=${username}&operation=${operation}`);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                console.log(xhr.responseText);
            } else {
                console.log("Server error", xhr.responseText);
            }
        }
    }
}

// Call the function to set events
setEventsToProfile();
