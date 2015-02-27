<?php
session_start();
require_once('db.php');

// Kontrollera om namn och lösenord matchar en användare i databasen
$db = connectToDb();

$name     =  mysqli_real_escape_string($db, $_POST['namn']);
$password = mysqli_real_escape_string($db, $_POST['pwd']);

$cryptPassword = md5($password);

$inloggad = mysqli_query($db, "SELECT * FROM login WHERE `user` = '$name' AND `password` = '$cryptPassword'");
$userInlog = mysqli_fetch_assoc($inloggad);

if($userInlog){
    // Om det matchar, logga in användaren och gå till startsidan
    $nameSmall = strtolower($_POST['namn']);
    $_SESSION['namn'] = $nameSmall;
    header('Location: index.php');
} 

else {
    // Om användaruppgifterna inte stämmer, skicka tillbaka till login-sidan
    // med ett felmeddelande (status=red).
    $_SESSION['messages'] = array(
        array('status' => 'red', 'text' => 'Användarnamnet och lösenordet matchar inte.'),
    );
    header('Location: form.php');
}

 mysqli_close($db);
 mysql_free_result ($inloggad);

