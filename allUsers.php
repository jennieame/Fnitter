<?php

//--- plocka ut 4 random användare ---//
function getRandomUsers(){
  $db = connectToDb();
  $profile = mysqli_real_escape_string($db, $_SESSION['namn']);

  $getUsers = mysqli_query($db, "SELECT * FROM `login` WHERE user NOT IN ('".$profile."' ) ORDER BY RAND() LIMIT 4");

    if($getUsers){
        while($row = mysqli_fetch_assoc($getUsers)){

        $writer = strtolower($row['user']);
        $url = "searchProfile.php?profile=$writer";

        print "<div class='fnittrare'>";
          print "<a href='$url' alt='$writer'>";
            print imageCurrentProfile($writer);
            print "<p>".$writer."</p><br>";
          print "</a>";
        print "</div>";
      }
   }
   mysqli_close($db);
}

//-- Hämta alla användare --//
function getAllUsers(){
    $db = connectToDb();
    $profile = mysqli_real_escape_string($db, $_SESSION['namn']);

   $getUsers = mysqli_query($db, "SELECT * FROM `login` WHERE user NOT IN ('".$profile."' )");

   if($getUsers){
      while($row = mysqli_fetch_assoc($getUsers)){

          $writer = strtolower($row['user']);
              $url = "searchProfile.php?profile=$writer";

        print "<div class='fnittrare'>";
          print "<a href='$url' alt='$writer'>";
            print imageCurrentProfile($writer);
            print "<p>".$writer."</p><br>";
          print "</a>";
        print "</div>";
      }
   }
   mysqli_close($db);
}

//---  Lista vilka jag följer  ---//
function getfollowings($user){
  $db = connectToDb();
  $getUsers = mysqli_query($db, "SELECT * FROM `following` WHERE `following` = '".$user."' ");

  if($getUsers){
    while($row = mysqli_fetch_assoc($getUsers)){
      if($row['user'] != $user){

        $writer = strtolower($row['user']);
        $url = "searchProfile.php?profile=$writer";
        print "<div class='fnittrare'>";
        print "<a href='$url' alt='$writer'>";
          print imageCurrentProfile($writer);
          print "<p>".$writer."</p><br>";
        print "</a>";
        print "</div>";
      }
    }
  }
  mysqli_close($db);
}


//---  Lista vilka som följer mig ---//
function getfollowers($user){
  $db = connectToDb();
  $getUsers = mysqli_query($db, "SELECT * FROM `following` WHERE user = '".$user."'");

  if($getUsers){
    while($row = mysqli_fetch_assoc($getUsers)){
      if($row['following'] != $user){
        $writer = strtolower($row['following']);

        $url = "searchProfile.php?profile=$writer";
        print "<div class='fnittrare'>";
        print "<a href='$url' alt='$writer'>";
          print imageCurrentProfile($writer);
          print "<p>".$writer."</p><br>";
          print "</a>";
        print "</div>";
      }
    }
  }
  mysqli_close($db);
}

?>

<div id='fnitters'>
    <h1>Andra Fnittrare</h1>
    <?php getRandomUsers(); ?>
    <hr>
    <a href="fnittrare.php" class="clear">Se fler fnittrare</a>
</div>