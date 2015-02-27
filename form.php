<?php
require_once('db.php');
session_start();

?>
<!DOCTYPE html>
<html lang="sv-se">
<head>
    <meta charset="UTF-8">
    <title>Fnitter</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body> 
<?php
require_once('menu.php');

if (isset($_SESSION['messages'])) {
    foreach ($_SESSION['messages'] as $msg) {
   
        print "<div class='container'>";
        echo '<p style="color:'.$msg['status'].'">'.$msg['text'].'</p>';
        print "</div>";
    }
    unset($_SESSION['messages']);
}

// Om besökaren redan är inloggad ge dom uppmaningen
// att besöka profilen direkt istället. (Kan ersättas med header Location)
if (isset($_SESSION['namn'])) {
    print "<div class='container'>";
    print "<p> Du är redan inloggad, gå till din <a href='profile.php'>profil</a>.</p>";
    print "</div>";
    die;
}

?>
<div id="wrapper">
    <div class="container">
        <h1> Logga in </h1>
        <form action="login.php" method="post">
            <label>Namn:</lable>     <input type="text"     placeholder="Namn"     name="namn"/><br/>
            <label>Lösenord:</label> <input type="password" placeholder="Lösenord" name="pwd" /><br/>
            <input class="button" type="submit" value="Logga in">
        </form> 
    </div>
    <div class="container">
        <h1>Ny användare</h1>
        <form action="createUser.php" method="post">
            <label>Namn:</label>     <input type="text"     placeholder="Namn"     name="newname" />
            <label>Lösenord:</label> <input type="password" placeholder="Lösenord" name="newpwd" />
            <label>E-mail:</label>   <input type="mail"     placeholder="E-mail"   name="mail" />
            <input class="button" type="submit" value="Skapa">
        </form>
    </div>
</div>
</body>
</html>