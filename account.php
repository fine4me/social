<?php
global $conn;
include_once('./dbadapter.php');
include_once('./utils.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['signup_php'])){
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $create_ts = time();
            $email_verfify = 1;
            $first_name = '';
            $last_name = '';
            $fullName = trim($_POST['full_name']);
            $nameParts = array_filter(explode(' ', $fullName));
            if (count($nameParts) > 1) {
                $first_name = ucfirst($nameParts[0]) ;
                $last_name =  ucfirst($nameParts[count($nameParts)-1]);
            } else {
                $first_name = $fullName;
            }

            try{
                $statement = $conn->prepare("INSERT INTO users (username, email, password, create_ts, is_email_verified, first_name, last_name) VALUES (?, ?, ?, ?, ?, ? , ?)");
                $statement->bind_param("sssiiss", $_POST['username'], $_POST['email'], $password, $create_ts, $email_verfify,$first_name,$last_name);
                $statement->execute();
                $statement->close();
            }catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) { // 1062 is the error code for duplicate entry
                    setMessage("Email or username already in use !", 3);
                    echo json_encode($_SESSION);
                } else {
                    throw $e; // Rethrow the exception if it's not a duplicate entry error
                }
            } catch (Exception $e) {
                setMessage("Internal error !", 3);
                echo json_encode($_SESSION);
            }
            header('Location: index.php');
            exit();
        }else if(isset($_POST['login_php'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            try{
                $statement = $conn->prepare('SELECT password,id FROM users WHERE username = ?');
                $statement->bind_param('s',$username);
                $statement->execute();
                $result = $statement->get_result();
                $ua = getUserAgent();
                if($result->num_rows == 1){
                    while($row = $result->fetch_assoc()){
                        if(password_verify($password,$row["password"])){
                            $createTime = time();
                            $sessionKey = generateRandomString(64);
                            $isActive = 1;
                            $stmtSession = $conn->prepare(("INSERT INTO sessions (session_key, user_id, is_active, create_time, ua) VALUES(?, ? ,? ,? ,? )"));
                            $stmtSession->bind_param('siiis',$sessionKey,$row["id"],$isActive,$createTime,$ua);
                            $stmtSession->execute();
                            $stmtSession->close();
                            session_start();
                            $_SESSION['skey'] = $sessionKey;
                            header('Location: home.php');
                            exit();
                        }
                    }
                }
                $statement->close();
            }catch(Exception $ex){
                setMessage("Internal error !", 3);
                exit();
            }
            setMessage("User name or password incorrect !", 1);
            header('Location: index.php');
        }
    }
?>