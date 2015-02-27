<?php
require_once('insertImage.php');
require_once('db.php');
require_once('functions.php');

$db = connectToDb();
$profile = $_SESSION['namn'];

$about = mysqli_query($db, "SELECT `about` FROM `login` WHERE `user` = '$profile'");
    if ($about){
      while($row = mysqli_fetch_assoc($about)){
      	$aboutUser = filter_var($row['about'],  FILTER_SANITIZE_SPECIAL_CHARS);
      }
    }

 mysqli_close($db);
?>

<html>
<div id="profile">
	<?php print imageCurrentProfile($_SESSION['namn']); ?>
  <div id="profileInfo">
  	<a href="profile.php"><h2><?= $_SESSION['namn']; ?></h2></a>
    <p><?= $aboutUser ?></p> 
  </div> 
        
  <a href="profileUpdate.php" class="link">Uppdatera profil</a>
  <a href="fnittrare.php?user=<?php print $profile ?>" class="link">Dina Fnittrare</a>
	<a href="news.php" class="link"><?php countNews(); ?></a>
</div>
</html>