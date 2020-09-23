<?php
require_once "pdo.php";
require_once "util.php";
session_start();
?>
<html>
<head>
  <title>Abhiroop mokshagna Bheemineni</title>
  <?php require_once "head.php"?>
</head>
<body>
<h1>Welcome to CRUD Resume Application</h1>
<?php
  flashMessage();
  if(!isset($_SESSION['name'])){
    echo '<a href = "login.php">Please log in</a>';
    $stmt = $pdo->query("SELECT profile_id, first_name,last_name , headline FROM users JOIN Profile ON users.user_id = Profile.user_id");
    echo '<table border = "1">';
    echo '<tr><th>Name</th><th>Headline</th></tr>';
    while($row  = $stmt->fetch(pdo::FETCH_ASSOC)){
      echo '<tr><td>';
      echo("<a href='view.php?profile_id=" . $row['profile_id'] . "'>" . $row['first_name'] . $row['last_name']  . "</a>");
      echo '</td><td>';
      echo (htmlentities($row['headline']));
      echo '</td></tr>';
    }
    echo '</table>';
  }else{
    $stmt = $pdo->query("SELECT profile_id, first_name,last_name , headline FROM users JOIN Profile ON users.user_id = Profile.user_id");
    echo '<table border = "1">';
    echo '<tr><th>Name</th><th>Headline</th><th>Action</th></tr>';
    while($row  = $stmt->fetch(pdo::FETCH_ASSOC)){
      echo '<tr><td>';
      echo("<a href='view.php?profile_id=" . $row['profile_id'] . "'>" . $row['first_name'] . $row['last_name']  . "</a>");
      echo '</td><td>';
      echo (htmlentities($row['headline']));
      echo '</td><td>';
      echo('<a href="edit.php?profile_id=' . $row['profile_id'] . '">Edit</a> / <a href="delete.php?profile_id=' . $row['profile_id'] . '">Delete</a>');
      echo '</td></tr>';
    }
    echo '</table>';
    echo '<br>';
    echo '<a href = "add.php">Add New Entry</a>';
    echo '<br>';
    echo '<a href = "logout.php">Logout</a>';
  }
?>
</body>
</html>
