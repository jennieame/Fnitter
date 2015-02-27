<?php 
session_start();
require_once('db.php');
require_once('insertImage.php');
require_once('follow.php');
require_once('functions.php');

if (!isset($_SESSION['namn'])) {
    $_SESSION['messages'] = array(
        array('status'=>'red', 'text'=>'Logga in för att fortsätta.'),
    );
    header('Location: form.php');
    die;
}

//Hitta användare och posta flöde
function profileSearch(){
	$db = connectToDb();

	if ($_SERVER["REQUEST_METHOD"] == "GET"){

		$searchProfile = mysqli_real_escape_string($db, $_GET["profile"]);

		$resultProfile = mysqli_query($db, "SELECT * FROM posts WHERE `writer` = '$searchProfile' ORDER BY id DESC");
		$countProfile  = mysqli_query($db, "SELECT COUNT(*) AS total FROM posts WHERE `writer` = '$searchProfile'");
		$data          = mysqli_fetch_assoc($countProfile);
	
    	$numberOfProfile  = $data['total'];

		if($numberOfProfile > 0){
			if ($resultProfile) {
				
				if($searchProfile == $_SESSION['namn']){
					print '<META HTTP-EQUIV="Refresh" Content="0; URL=profile.php">';
	   				exit; 
				}

				print "<div id='searchProfile'>";
					getProfile($searchProfile, 'unfollow', 'Sluta följ ');		
				print "</div>";

				print "<div class='container'>";
	 				postFlow($searchProfile,'searchProfile.php?profile='.$searchProfile, 5);
				print"</div>";
			}		
		}			
	}
	mysqli_close($db);
}

//hämta information om användaren
function getProfile($searchProfile, $action, $text){
	$db = connectToDb();

	$searchProfile = mysqli_real_escape_string($db, $_GET["profile"]);
	$resultProfile = mysqli_query($db, "SELECT * FROM login WHERE `user` = '$searchProfile'");

	$ifUser 	   = mysqli_query($db, "SELECT COUNT(*) AS total FROM login WHERE `user` = '$searchProfile'");
	$userData 	   = mysqli_fetch_assoc($ifUser);
	$existingUser  = $userData['total'];

	//--- ifall inte användaren finns ---//
	if($existingUser <= 0) {
		print "<div class='container'>";
			print "<h2>Tyvärr!</h2><br><p>Det finns ingen användaren som heter ".$searchProfile."</p>";
			print "<p>Gå tillbaka till <a href='index.php'>startsidan</a></p><br>";
			print '<p> Testa igen: </p> <br>
				   <form action="searchProfile.php?" method="GET" id="search">
						<input type="text" placeholder="Sök användare, avsluta med enter" name="profile">
					</form>';
		print "</div>";
	}

	else{ 
		while($row = mysqli_fetch_assoc($resultProfile)){
	
		  	$writer   = filter_var($row['user'],  FILTER_SANITIZE_SPECIAL_CHARS);
			$about     = filter_var($row['about'], FILTER_SANITIZE_SPECIAL_CHARS);
			print "<div class='crop'>";
			imageCurrentProfile($searchProfile);
			print "</div>";
				print "<div id='info'>";
					print "<h1>".$writer."</h1>";
					print "<p>" .$about.  "</p>";
					print "<a href='fnittrare.php?user=".$searchProfile."'>";
						countFollowers($searchProfile);
						print " | ";
						countFollowings($searchProfile);
 				print "</a></div>";
				print "</div>";

			print "<form action='' method='POST'>";
            print "<input type='hidden' name='$action' value='$writer'>";
            print "<input class='button' type='submit' value='$text $writer '>";
            print "</form>";
		}
	}
 mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?= $_GET["profile"]; ?> - Fnitter</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
	<?php require_once('menu.php'); ?>
	<div id="wrapper">
 	<?php require('profileLink.php'); ?>
    <?php require_once('allUsers.php'); ?>
   
	<div class="container">
	<?php 
		//För att följa användare
		if (isset($_POST['writer']))
		{
	        followUser($_POST['writer'], $_SESSION['namn']);
	    }

	    //för att avfölja användare
	    if (isset($_POST['unfollow']))
	    {
	        unFollow($_POST['unfollow'],$_SESSION['namn']);
	    }

	    //kontrollerar att man följer
	    checkIfFollowing($_GET["profile"], $_SESSION['namn']);

	    //kommentera inlägg
		 if (isset($_POST['comment']))
	    {
	        commentPost($_POST['id'], $_POST['comment'],$_SESSION['namn']);
	    }
	?>
	</div>
	</div>

    <?php require_once('footer.php'); ?>

</body>
</html>
