<?php
session_start();
require_once('db.php');
require_once('insertImage.php');
require_once('functions.php');
?>

<!DOCTYPE html>
<html lang="sv-se">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' />
    <title>Nyheter- Fnitter</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body> 

<?php require_once('menu.php'); ?>
<?php require_once('profileLink.php'); ?>
<?php require_once('allUsers.php'); ?>

<div id="wrapper">
    <div class="container">
    	<h1> Nyheter</h1>
    	<?php news(); ?>
    	<?php 
    	if (isset($_POST['markAsSeen'])){
            	markAsSeen($_POST['markAsSeen']);
            }
        ?>
    </div>
</div>

<?php require_once('footer.php'); ?>

</body>
</html>