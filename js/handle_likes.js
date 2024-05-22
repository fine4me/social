const xhr = new XMLHttpRequest();
function toggle_like(e) {
    // Post form data to php endpoint, form entry has post_id field
    // Get element which triggered this function as it's bound in onclick, then get value of its like_post attribute and cast it to integer to get post id
    let post_id = parseInt(e.target.getAttribute('like_post'));
    xhr.open('POST', 'like.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`post_id=${post_id}`);
    // Get response
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                console.log("success")
                let response = xhr.responseText.trim();
                console.log(response);
                if (response == 'liked') {
                    // Add +1 to inner text element with id of likeCount-<post_id>
                    document.getElementById(`likeCount-${post_id}`).innerText = parseInt(document.getElementById(`likeCount-${post_id}`).innerText) + 1;
                    document.getElementById(`likepost-${post_id}`).classList.toggle('liked')
                } else if (response == 'unliked') {
                    // Subtract -1 to inner text element with id of likeCount-<post_id>
                    document.getElementById(`likeCount-${post_id}`).innerText = parseInt(document.getElementById(`likeCount-${post_id}`).innerText) - 1;
                    document.getElementById(`likepost-${post_id}`).classList.toggle('liked')
                }
            } else {
                console.log("Server error", xhr.responseText);
            }
        }
    }
}



function refresh_evh() {

    // Add event listener to like-post class and trigger toggle_like function on click, do not add if event handler is already attached
    document.querySelectorAll('.like-react').forEach(element => {
            element.addEventListener('click', toggle_like);
    })
}

refresh_evh();

