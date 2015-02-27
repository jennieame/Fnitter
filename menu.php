<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>

<?php

if (isset($_SESSION['namn'])) {
    if (!isset($_SESSION['messages'])) {

    	print '<div id="menu">';
	    	print '<a href="index.php" title="Hem"><h1>Fnitter</h1></a>';

				print '<ul>';
					print '<a href="profile.php"><li>Din profil</li></a>';
		        	print '<a href="about.php"><li>Om Fnitter</li></a>';
					print '<a href="logout.php"><li>Logga ut</li></a>';
				print '</ul>';

					print '<form action="searchProfile.php?" method="GET" id="search">
					<input type="text" placeholder="Sök användare, avsluta med enter" name="profile">
					</form>';

		print '</div>';
    }
}

else{
	print '<div id="menu">';
		print '<a href="form.php"><h1>Fnitter</h1></a>';
		print '<ul>';
			print '<a href="form.php"><li>Startsidan</li></a>';
		    print '<a href="about.php"><li>Om Fnitter</li></a>';
		print '</ul>';
	print '</div>';
} 
?>
