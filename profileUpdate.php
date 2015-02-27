<?php
session_start();
require_once('insertImage.php');
require_once('db.php');

if (!isset($_SESSION['namn'])) {
    $_SESSION['messages'] = array(
        array('status'=>'red', 'text'=>'Logga in för att fortsätta.'),
    );
    header('Location: form.php');
    die;
}

$profile = $_SESSION['namn'];

function UpdateAbout($profile){
	$db = connectToDb();
	$about = mysqli_query($db, "SELECT `about` FROM `login` WHERE `user` = '$profile'");
	$updatedAbout = $_POST['about'];

	if ($about){
        while($row = mysqli_fetch_assoc($about)){
        	$aboutUser = filter_var($row['about'],  FILTER_SANITIZE_SPECIAL_CHARS);
			$changeAbout = mysqli_query($db, "UPDATE `login` SET `about` = '$updatedAbout' WHERE `user` = '$profile'");

    		print "<p>Du har nu uppdataterat din profil!</p>";
        }
    }
    else{
    	print "<p>Det gick inte att uppdatatera din profil!</p>";
    }
 mysqli_close($db);
}

function changePassword(){
	$db = connectToDb();

	$oldPassword = $_POST['old'];
	$newPwd    = $_POST['new'];
	$profile   = $_SESSION['namn'];

	//ifall båda rutorna är ifyllda // annars skicka med en text
	if($oldPassword && $newPwd){
	    $cryptPassword = md5($oldPassword);

	    $correct      = mysqli_query($db, "SELECT * FROM login WHERE `user` = '$profile' AND `password` = '$cryptPassword'");
		$correctArray = mysqli_fetch_assoc($correct);

		if($correctArray){
		    	$cryptNewPassword = md5($newPwd);
				$changePwd = mysqli_query($db, "UPDATE `fnitter`.`login` SET `password` = '$cryptNewPassword' WHERE `login`.`user` = '$profile'");
		    	print "<p>Ditt lösenord är nu ändrat!</p>";
		    }
		else {
		    print "<p>Lösenordet matchar inte med din användare!</p>". "</br>";
		}
	}
	else{
	 	print"<p>Du måste fylla i båda rutorna</p>";
	 }
 mysqli_close($db);
 }

?>
<!DOCTYPE html>
<html lang="sv-se">
<head>
<meta charset="UTF-8">
<title>Uppdatatera Profil - Fnitter</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body> 
	<?php require_once('menu.php'); ?>
    <?php require_once('profileLink.php'); ?>
    <?php require_once('allUsers.php'); ?>

<div class="container">
	<h1>Uppdatera Profil </h1>
	<div class="update">
		<h2>Profilbild</h2>
			<form enctype="multipart/form-data" action="profileUpdate.php" method="POST">
				<input type="file" name="upload">
				<input class="button" type="submit" onclick='reload()' value="Ladda upp">
			</form>
		<?php
		if (isset($_FILES['upload']))
		{
	    	print insertAvatar($profile);
		}
		?>
	</div>
	<div class="update">
		<h2>Om mig</h2>
		<form enctype="text" action="profileUpdate.php" method="POST">
			<input type="text" name="about" placeholder="Skriv en rad om dig">
			<input class="button" type="submit" value="Spara">
		</form>
		<?php
		if (isset($_POST['about']))
		{
	    	print updateAbout($profile);
		}
		?>
	</div>
	<div class="update">
		<h2>Byt lösenord</h2>
		<form enctype="text" action="profileUpdate.php" method="POST">
			<input type="password" name="old" placeholder="Gammalt">
			<input type="password" name="new" placeholder="Nytt">
			<input class="button" type="submit" value="Byt lösenord">
		</form>
		<?php
		if (isset($_POST['old'], $_POST['new']))
		{
	    	print changePassword();
		}
		?>
	</div>
</div>
    <?php require_once('footer.php'); ?>
</body>
</html>
