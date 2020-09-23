<?php // Do not put any HTML above this line
session_start();
require_once "pdo.php";
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}
$salt = 'XyZzy12*_';
//$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123
$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    $check = hash('md5', $salt.$_POST['pass']);

    $stmt = $pdo->prepare('SELECT user_id, name FROM users

    WHERE email = :em AND password = :pw');

    $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "User name and password are required";
        $_SESSION['failure'] = $failure;
        header("Location: login.php");
        return;
    } else if(strpos($_POST['email'],"@")===false){
        $failure = "Email must have an at-sign (@)";
        $_SESSION['failure'] = $failure;
        header("Location: login.php");
        return;
    } else if(row===false){
        $failure = "Incorrect password";
        $check = hash('md5',$salt.$_POST['pass']);
        error_log("Login fail ".$_POST['email']." $check");
        $_SESSION['failure'] = $failure;
        header("Location: login.php");
        return;
    } else{
          // $stmt = pdo->prepare("SELECT user_id FROM users WHERE email = :em and password = :pwd");
          // $stmt->execute(array(
          //   ':em' => htmlentities($_POST['email']);
          //   ':pwd' => $check;
          // ));
          // $row = $stmt->fetch($pdo::FETCH_ASSOC);
          if($row!==false){
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $_POST['email'];
            $_SESSION['success'] = 'Logged in';
            header("Location: index.php");
            error_log("Login success ".$_POST['email']);
            return;
          }else{
            $_SESSION['failure'] = "User Name not valid!";
            header("Location: login.php");
            error_log("Login failure "."User name = ".$_POST['email']);
            return;
          }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<title>Abhiroop mokshagna Bheemineni</title>
<?php require_once "head.php"?>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if(isset($_SESSION['failure'])){
  echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
  unset($_SESSION['failure']);
}
?>
<form method="post">
<label for="nam">User Name</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the name of the course we are learning
(all lower case) followed by 123. -->
</p>
</div>
</body>
</html>
<script type="text/javascript">
  function doValidate(){
    console.log('Validating...');
    try {
        pw = document.getElementById('id_1723').value;
        em = document.getElementById('nam').value;
        console.log("Validating pw="+pw);
        if (pw == null || pw == "" || em == null || em == "") {
          alert("Both fields must be filled out");
          return false;
        }else if(em.includes('@')==false){
          alert("invalid email address");
          return false;
        }
        return true;
    }catch(e){
      return false;
    }
    return false;
  }
</script>
