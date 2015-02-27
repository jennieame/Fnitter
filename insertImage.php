<?php
require_once('db.php');

//--- Ladda upp profilbild ---//
function insertAvatar($profile){
	$db = connectToDb();

//Formuläret är	postat	med	en	fil
if (isset($_FILES['upload']))	
{
	//Filen är uppladdad utan problem
	if ($_FILES['upload']['error']	==	0)	
	{
		$tmp	=	addslashes(file_get_contents($_FILES['upload']['tmp_name']));
		$name	=	addslashes($_FILES['upload']['name']);
		$image  =	getimagesize($_FILES['upload']['tmp_name']);
		$size	=	$_FILES['upload']['size'];

		if($image == FALSE){
			$text = "<p>Det var ingen bild.</p>";
		}

		else{
			if ($size >	1050000){
				$text = "<p>Bilden är för stor!</p>";
			}

			else{
				$changeAvatar = mysqli_query($db, "UPDATE `jeer`.`login` SET `avatar` = '$tmp' WHERE `login`.`user` = '$profile'");
				move_uploaded_file($tmp, $name);

				$text = "<p>Filen '$name' uppladdad!</p>";
			}
		}
	}

	else{
		$text = "<p>Något gick fel, försök igen.</p>";
	}
}

else{
	$text = "<p>Du valde ingen bild</p>";
	
}

return $text;

mysqli_close($db);
mysql_free_result ( $changeAvatar);
}

//------Hämta profilbild--------//

function imageCurrentProfile($profile){
	$db = connectToDb();

	$currentProfile = mysqli_query($db, "SELECT * FROM login WHERE `user` = '$profile'");

	if ($currentProfile){
		while($row = mysqli_fetch_assoc($currentProfile)){
			
			if( !empty ( $row['avatar'] ) ){
				print '<img src="data:image/jpeg;base64,'.base64_encode( $row['avatar'] ).'"/>';
			}
			else{
				print '<img src="avatar.png" alt="avatar"/>';
			}
		}
	}
	mysqli_close($db);
}
	
