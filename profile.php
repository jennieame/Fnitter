<?php
session_start();
require_once('db.php');
require_once('insertImage.php');
require_once('functions.php');

// Kontrollera om besökaren är inloggad. 
// Är de inte det så skicka dem till login-formuläret.
if (!isset($_SESSION['namn'])) {
    $_SESSION['messages'] = array(
        array('status'=>'red', 'text'=>'Du måste logga in för att fortsätta.'),
    );
    header('Location: form.php');
    die;
}

?>
<!DOCTYPE html>
<html lang="sv-se">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' />
    <title><?= $profile ?> - Fnitter</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body> 
<?php require_once('menu.php'); ?>

<div class="wrapper">
	<div id="profilLink">
 		<?php require_once('profileLink.php'); ?>
 	</div>
 	<?php require_once('allUsers.php'); ?>

	<div class="container">
		<h1>Skriv ett inlägg</h1>

		<form action="profile.php" method="post"> 
		    <textarea type="text" name="post" placeholder="Skriv ditt inlägg (max 150tecken)"></textarea><br/>
		    <input class="button" type="submit" value="Publicera">
		</form>
		<?php
		    if (isset($_POST['post']))
		    {
		        writePost($profile);
		    }
		?>
	</div>

	<div class="container">
		<?php  
		 	postFlow($_SESSION['namn'],'profile.php', 5);

		 	if (isset($_POST['removeId']))
	        {
	            removePost($_POST['removeId']);
	        }

		    if (isset($_POST['comment']))
		    {
		        commentPost($_POST['id'], $_POST['comment'],$profile);
		    }
		?>
	</div>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>