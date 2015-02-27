<?php
session_start();
require_once('db.php');

//---Skapa ny användare----//
$newName     = $_POST['newname'];
$newPassword = $_POST['newpwd'];
$mail        = $_POST['mail'];

$db = connectToDb();

if($newName != NULL && $newPassword != NULL && $mail != NULL){
    if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
        $control = mysqli_query($db, "SELECT * FROM `login` WHERE `user` = '".$newName."'");
        $count = mysqli_num_rows($control);

        if($count >= 1){
            $_SESSION['messages'] = array(
                array('status' => 'red', 'text' => 'Användarnamnet finns redan.'),
            );
        header('Location: form.php');
        }
        
        else{
            $newName     = mysqli_real_escape_string($db, $_POST['newname']);
            $newPassword = mysqli_real_escape_string($db, $_POST['newpwd']);
            $newNameSmall = strtolower($newName);

            //kryptera lösenordet
            $cryptPassword = md5($newPassword);

            $newUser = mysqli_query($db, "INSERT INTO `login`(`user`, `password`, `mail`) VALUES ('$newNameSmall', '$cryptPassword', '$mail')");

            $_SESSION['messages'] = array(
                array('status' => 'green', 'text' => 'Din användare är nu skapad och du kan logga in.'),
            );

            $followAtStart = mysqli_query($db,"INSERT INTO  `fnitter`.`following` (`user` ,`following`) VALUES ('$newNameSmall',  'fnitter'), ('$newName', '$newNameSmall')");
        header('Location: form.php');
        }
    }
    else{
        $_SESSION['messages'] = array(
        array('status' => 'red', 'text' => 'Ogiltlig mailadress.'),
        );
        header('Location: form.php');
    }
}
else {
    $_SESSION['messages'] = array(
        array('status' => 'red', 'text' => 'Du måste fylla i alla fält.'),
    );
    header('Location: form.php');
}

mysqli_close($db);


