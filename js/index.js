function viewRegister()     {
    document.querySelector('.contain-logo').style.height = "20%";
    document.querySelector('.login-heading').innerHTML = "Create new <br> Account  <span class='cont-signin' onclick='renderLoginPage()'>Already registered ? Log in here</span>";
    document.querySelector('.login-form').innerHTML = `
    <form action="account.php" method="post" class="login-information">
        <label for="username" class="label-name">UserName</label>
        <input type="text" id="username" class="input-name" placeholder="Your Name" required name="username">
        <label for="full_name" class="label-name">Full Name</label>
        <input type="text" id="full_name" class="input-name" placeholder="Your Name" required name="full_name">
        <label for="email" class="label-name">Email</label>
        <input type="email" id="email" class="input-name" placeholder="example@example.com" required name="email">
        <label for="user-password" class="label-name">Password</label>
        <input type="password" id="user-password" class="input-name" placeholder="*********" name="password">
        <input type="submit" value="Sign Up" name="signup_php" class="submit-login">
    </form>
`
document.querySelector('.signup-form').innerHTML = "";
}

function renderLoginPage() {
    document.querySelector('.contain-logo').style.height = "35%";
    document.querySelector('.login-heading').innerHTML = `Login <span class="cont-signin">Sign in to continue.</span>`;
    document.querySelector('.login-form').innerHTML = `
        <form action="account.php" method="post" class="login-information" >
            <label for="username" class="label-name">NAME</label>
            <input type="text" class="input-name" name="username" placeholder="Username" required>
            <label for="user-password" class="label-name">PASSWORD</label>
            <input type="password" class="input-name" name="password" placeholder="*********">
            <input type="submit" name="login_php" value="Log in" class="submit-login">
        </form>
    `;
    document.querySelector('.signup-form').innerHTML = '<span class="signup-register" onclick="viewRegister()">Signup !</span>';
}

renderLoginPage();