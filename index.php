<?php
session_start();
require_once('db.php');
require_once('insertImage.php');
require_once('functions.php');

// Kontrollera om besökaren är inloggad. 
// Är de inte det så skicka dem till login-formuläret.
if (!isset($_SESSION['namn'])) {
    $_SESSION['messages'] = array(
        array('status'=>'red', 'text'=>'Du är inte inloggad.'),
    );
    header('Location: form.php');
    die;
}
    $profile   = $_SESSION['namn'];
// function getFollowers($profile){
    $db = connectToDb();
    $all  = array();

    $getFollowings = mysqli_query($db, "SELECT `following`.`following` FROM `following` WHERE user = '$profile' ");
    if ($getFollowings)
    {
        while($row = mysqli_fetch_assoc($getFollowings)){
            $following = $row['following'];
            array_push($all, $following);
        }
     }       

    $insert = implode("', '",$all);
 ?>

 <!DOCTYPE html>
 <html lang="sv-se">
 <head>
 	<meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' />
    <link rel="shortcut icon" type="image/png" href="/favicon2.png"/>
 	<title>Fnitter</title>
    <link rel="stylesheet" type="text/css" href="style.css">
 </head>
 <body>

<?php require_once('menu.php'); ?>

<div id="wrapper">
    <?php require_once('profileLink.php'); ?>  
    <?php require_once('allUsers.php'); ?>

    <div class="container">
        <h1>Skriv ett inlägg</h1>

     	<form action="index.php" method="post"> 
            <textarea type="text" name="post" placeholder="Skriv ditt inlägg (max 150tecken)" maxlength="150"></textarea><br/>
        	<input class="button" type="submit" value="Publicera">
    	</form>

    <?php
        if (isset($_POST['post']))
        {
            print writePost($profile);
        } 
    ?>
    </div>

    <div class="container">
    <?php
       if (isset($_POST['removeId']))
        {
            removePost($_POST['removeId']);
        }

        if (isset($_POST['comment']))
        {
            commentPost($_POST['id'], $_POST['comment'],$profile);
        }
    ?>
 	<?php postFlow($insert,'index.php', 5); ?>
    </div> 
</div>
<?php require_once('footer.php'); ?>
 </body>
 </html>

 