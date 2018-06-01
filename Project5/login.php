<!--Project 5-->
<!--Name: Ritesh Deshmukh-->

<?php
function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');

try {
    $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}catch (PDOException $e) {
    print "Error: " . $e -> getMessage();
    die();
}

if (isset($_POST['username']) && isset($_POST['password'])) {

    // phpAlert("alert try");
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);
    $stmt = $dbh->prepare('SELECT * FROM users WHERE username = :username and password = :password');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $val = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($val) {
        $_SESSION['username'] = $val['username'];
        header('location:board.php');
        // exit;
    } else {
        print '<h3>Username and password do not match</h3>';
    }
}
?>

<html>
<head><title>Login Page</title></head>
<body>
</br></br><div style="width: 800px; margin: 0 auto;">
    <h2>Login Form</h2>
    <div style="margin: 50px;">
        <form action="login.php" method="post">
            <label>Username: </label><input type="text" name="username"/></br></br></br>
            <label>Password: </label><input type="text" name="password"/></br></br></br>
            <input type="submit" value="Log In" name="submit" class="submit" style="height: 30px; width: 100px"/>
        </form>
    </div>

</div>

</body>
</html>