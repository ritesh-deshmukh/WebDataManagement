<!--Project 5-->
<!--Name: Ritesh Deshmukh-->

<html>
<head><title>Message Board<br></title></head>
<body>
<div style="width: 400px; margin: 0 auto;">
    <b style="font-size: xx-large">Message Board</b>
</div>

<form action="board.php" method="post">
<div style="margin-left: 20px"><br>
    <div style="align-items: center;"><textarea rows="20" cols="50" name="new_message" placeholder="Enter message here and click on the New post button below."></textarea></div><br>
    <input type="submit" name="post_message" value="New Post" style="height: 30px; width: 100px;"/><br>
    <!--        <input type="submit" value="Reply" name="reply_button" style="height: 30px; width: 100px;"></br>-->
</div>

<?php
function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');

$query_display = "SELECT users.username, users.fullname, posts.id, posts.datetime, posts.replyto, posts.message
                                        FROM users,posts 
                                        WHERE users.username=posts.postedby 
                                        ORDER BY datetime DESC";

if (isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}else{
    header('location:login.php');
}

if (isset($_POST['logout_button'])){
//            phpAlert("logout alert");
    session_unset();
    session_destroy();
    header('location: login.php');
    exit();
}
try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

} catch (PDOException $e) {
  print "Error: " . $e -> getMessage();
  die();
}

if (isset($_POST['new_message'])){
    $new_message = trim($_POST['new_message']);
    $username = $_SESSION['username'];
    $query_insert1 = "INSERT INTO posts VALUES (UUID(), NULL, '$username', NOW(), '$new_message')";
    if (isset($_POST['post_message'])){
        $dbh -> beginTransaction();
//        $newvar = $dbh -> prepare("INSERT INTO posts VALUES (UUID(), NULL, '$username', NOW(), '$new_message')");
        $dbh -> exec($query_insert1) or die(print_r($dbh->errorInfo(), true));
//        $newvar -> bindParam(':username' , $username);
//        $newvar -> bindParam(':new_message', $new_message);
//        $newvar = exec();
        $dbh -> commit();
    }
    if (isset($_GET['reply_button'])){
//        phpAlert("logout alert");
//        $pass = $_POST['pass'];
        $reply_button = $_GET['reply_button'];
        $query_insert2 = "INSERT INTO posts VALUES (UUID(), '$reply_button', '$username', NOW(), '$new_message')";
        $dbh -> beginTransaction();
        $dbh -> exec($query_insert2) or die(print_r($dbh->errorInfo(), true));
        $dbh -> commit();
    }
}


    $stmt = $dbh->prepare($query_display);
    $stmt -> execute();
    print '<div style="position: absolute; margin-top: -380px; margin-left: 600px"><fieldset style="border: solid"><legend style="font-size: larger"><b><i>List of Messages</i></b></legend><table><tr><th>Username</th><th>Full Name</th><th>ID</th><th>Date Time</th><th>Reply To</th><th>Message</th><th>Reply</th></tr>';
    while ($row = $stmt->fetch()) {
        print '<tr><td style="text-align: center;">' . $row[0] . '&nbsp;&nbsp;</td><td style="text-align: center;"><i>&nbsp;&nbsp;' . $row[1] . '&nbsp;&nbsp;<i></td>' . '<td style="text-align: center;"><b>&nbsp;&nbsp;' . $row[2] . '&nbsp;&nbsp;</b></td><td style="text-align: center;">&nbsp;&nbsp;' . $row[3] . '&nbsp;&nbsp;</td><td style="text-align: center;">&nbsp;&nbsp;'. $row[4] . '&nbsp;&nbsp;</td>' .
            '<td style="text-align: center;">&nbsp;&nbsp;'.$row[5] .'&nbsp;&nbsp;</td><td align="center"><button type="submit" formaction=board.php?reply_button=' .$row[0]. '>Reply</button></td></tr>';
    }print '</table></fieldset></div>';
?>

<div><br>
    <input type="submit" value="Logout" name="logout_button" style="height: 30px; width: 100px; margin-left: 20px">
</div>

</form>
</body>
</html>

<!--print_r($dbh);-->
<!--$dbh->beginTransaction();-->
<!--$dbh->exec('delete from users where username="smith"');-->
<!--$dbh->exec('insert into users values("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")')-->
<!--or die(print_r($dbh->errorInfo(), true));-->
<!--$dbh->commit();-->
<!---->
<!--$stmt = $dbh->prepare('select * from users');-->
<!--$stmt->execute();-->
<!--print "<pre>";-->
<!--  while ($row = $stmt->fetch()) {-->
<!--    print_r($row);-->
<!--  }-->
<!--  print "</pre>";-->
