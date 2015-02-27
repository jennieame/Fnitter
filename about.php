<?php
session_start();
require_once('insertImage.php');
require_once('functions.php');
?>

<!DOCTYPE html>
<html lang="se-sv">
<head>
	<meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
	<title>Fnitter</title>
</head>
<body>
	<?php require_once('menu.php'); 
		if (isset($_SESSION['namn'])) {
			require_once('profileLink.php');   
	    	require_once('allUsers.php'); 
		}
	?>
	<div id="wrapper">
		<div class="container">
			<h1>Fnitter</h1>
			<b>Som Twitter, fast Fnitter</b></br></br>
			<p><b>fnitt´er</b> subst. <i>fnittret</i> </P>
			<p>ORDLED: <i>fnittr-et</i></P>
			<p>- ihållande dämpat skratt</P>
			<i>~Källa ne.se</i>
		</div>
	</div>
	<?php require_once('menu.php'); 
	if (isset($_SESSION['namn'])) {
		require_once('footer.php');   
	}?>
</body>
</html>