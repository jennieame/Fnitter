<?php

function followUser($following, $user){

	$db   = connectToDb();
    $alreadyFollow = mysqli_query($db, "SELECT * FROM `following` WHERE `user` = '$user' AND `following` = '$following'" );

    if($alreadyFollow){
    	$count = mysqli_num_rows($alreadyFollow);
 
		if($count == 1)
		{
			print "<p>Du följer redan den här användaren. Vill du sluta följa $following ?</p>";
			print "<a href=''> <- Tillbaka </a>";
		}
		else		
		{
			print "Du har börjat följa <a href='searchProfile.php?profile=$following' >$following</a>";
			$startFollow = mysqli_query($db,"INSERT INTO  `jeer`.`following` (`user` ,`following`) VALUES ('$user',  '$following')");
			$news        = mysqli_query($db,"INSERT INTO `news`(`action`, `sendFrom`, `sendTo`) VALUES ('började följa dig', '$user', '$following')");
		}
	}
	mysqli_close($db);
}


function unFollow($following, $user){

	$db   = connectToDb();
	$removeFollowing = mysqli_query($db, "DELETE FROM `jeer`.`following` WHERE `following`.`user` = '$user' AND `following`.`following` = '$following'");
	
	print "Du har nu sluta följa $following!";

	mysqli_close($db);
}


function checkIfFollowing($follow, $user){

	$db   = connectToDb();
    $following = mysqli_query($db, "SELECT * FROM `following` WHERE `user` = '$user' AND `following` = '$follow'" );

    if($following){

    	$count = mysqli_num_rows($following);
 
		if($count >= 1)
		{
			profileSearch();
		}
		else {
			print "<div id='searchProfile'>";
				getProfile($follow, 'writer', 'Följ ');		
			print "</div>";
		}
	}
	mysqli_close($db);
}

function countFollowings($user){

	$db   = connectToDb();
    $following = mysqli_query($db, "SELECT `following` FROM `following` WHERE `user` = '$user'  AND `following` NOT LIKE '$user'" );

    if($following){
    	$count = mysqli_num_rows($following);
    	print "Följer: ".$count;
    }
}

function countFollowers($user){
	
	$db   = connectToDb();
    $followers = mysqli_query($db, "SELECT `following` FROM `following` WHERE `following` = '$user' AND `user` NOT LIKE '$user'" );

    if($followers){
    	$count = mysqli_num_rows($followers);
    	print "Följare: ".$count;
    }
	mysqli_close($db);

}
