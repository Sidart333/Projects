<?php
include '../Coffeewebsite/components/connection.php';
session_start();
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id ='';
}

// register user
if(isset($_POST['submit'])){
    $id = unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = $_POST['cpass'];
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if($select_user->rowCount() > 0){
        $message[] = 'email already exist';
        echo 'email already exist';
    }else{
        if($pass != $cpass){
            $message[] = 'confirm your password';
            echo 'confirm your password';
        }else{
            $insert_user = $conn->prepare("INSERT INTO `users`(id,name,email,password) VALUES (?,?,?,?)");
            $insert_user->execute(([$id,$name,$email,$pass]));
            $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
            $select_user->execute([$email,$pass]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);
            if($select_user->rowCount()>0){
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];

            }
        }
    }
}

?>

<style type="text/css">
<?php include 'style.css'; ?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Website - register now</title>
</head>
<body>
    <div class="form-container">
        <section class="form-container">
            <div class="title">
                <img src="/img/download.png" alt="">
                <h1>register now</h1>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Explicabo in quas deserunt incidunt dolores?</p>
            </div>
            <form method="post">
                <div class="input-field">
                    <p>your name <sup>*</sup></p>
                    <input type="text" name="name" required placeholder="enter your name" maxlength="50">
                </div>
                <div class="input-field">
                    <p>your email <sup>*</sup></p>
                    <input type="text" name="email" required placeholder="enter your email" maxlength="50" oninput="this.value
                    = this.value.replace(/\//g,'')">
                </div>
                <div class="input-field">
                    <p>your password<sup>*</sup></p>
                    <input type="text" name="name" required placeholder="enter your name" maxlength="50" oninput="this.value
                    = this.value.replace(/\//g,'')">
                </div>
                <div class="input-field">
                    <p>confirm password<sup>*</sup></p>
                    <input type="submit" name="submit" value="register now" class="btn" maxlength="50">
                    <p>already have an account <a href="login.php">login now</a></p>
                </div>
            </form>
        </section>
    </div>
</body>
</html>