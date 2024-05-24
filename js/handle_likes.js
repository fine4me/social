function toggle_like(e) {
    // Create a new XMLHttpRequest instance
    const xhr = new XMLHttpRequest();

    // Post form data to PHP endpoint, form entry has post_id field
    // Get element which triggered this function as it's bound in onclick, then get the value of its like_post attribute and cast it to integer to get post_id
    let post_id = parseInt(e.target.getAttribute('like_post'));
    xhr.open('POST', 'like.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`post_id=${post_id}`);

    // Get response
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                console.log("success");
                let response = xhr.responseText.trim();
                console.log(response);
                if (response == 'liked') {
                    // Add +1 to inner text element with id of likeCount-<post_id>
                    let likeCountElement = document.getElementById(`likeCount-${post_id}`);
                    likeCountElement.innerText = parseInt(likeCountElement.innerText) + 1;
                    document.getElementById(`likepost-${post_id}`).classList.toggle('liked');
                } else if (response == 'unliked') {
                    // Subtract -1 to inner text element with id of likeCount-<post_id>
                    let likeCountElement = document.getElementById(`likeCount-${post_id}`);
                    likeCountElement.innerText = parseInt(likeCountElement.innerText) - 1;
                    document.getElementById(`likepost-${post_id}`).classList.toggle('liked');
                }
            } else {
                console.log("Server error", xhr.responseText);
            }
        }
    }
}

function refresh_evh() {
    // Add event listener to like-react class and trigger toggle_like function on click, do not add if event handler is already attached
    document.querySelectorAll('.like-react').forEach(element => {
        element.removeEventListener('click', toggle_like);  // Remove existing event listener to prevent multiple bindings
        element.addEventListener('click', toggle_like);
    });
}

refresh_evh();
