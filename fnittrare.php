<?php
session_start();
require_once('db.php');
require_once('insertImage.php');
require_once('functions.php');

if (!isset($_SESSION['namn'])) {
    $_SESSION['messages'] = array(
        array('status'=>'red', 'text'=>'Du måste logga in för att fortsätta.'),
    );
    header('Location: form.php');
    die;
}

//visa vilka du och andra användare följer
function socialFnitter(){
    $db = connectToDb();

    if ($_SERVER["REQUEST_METHOD"] == "GET"){
        if(empty($_GET["user"])){
        print "<div class=col-12 id='allaFnittrare'><h2>Fnittrare</h2>";
            getAllUsers();
        print"</div>";
    }
    elseif ($_GET["user"] == $_SESSION['namn']) {
        $user = mysqli_real_escape_string($db, $_GET["user"]);

        print  "<div class=col-12 id='allaFnittrare'>
                <h2>Fnittrare</h2>";
                     getAllUsers();
                 print"</div>
                 <input type='button' class='showUsers button' value='Dölj Fnittrare'>
                <input type='button'  class='hideUsers button' value='Visa alla fnittrare'> 
                <hr>";

        print  "<div class='col-6'>
                <h2>Du följer:</h2>";
                    getfollowers($user);
        print   "</h2>
                </div>";

        print  "<div class='col-6'>
                <h2>Dina följare:</h2>";
                    getfollowings($user);
        print   "</h2></div>";
    }
    else{
            $user = mysqli_real_escape_string($db, $_GET["user"]);

            print "<a href='searchProfile.php?profile=".$user."' alt='Tillbaka'><h1>".strtoupper($user)."</h1></a><hr>";
            print "<div class='col-6'><h2>$user följer:</h2>";
                getfollowers($user);
            print "</h2></div>";
            print "<div class='col-6'><h2>".$user."s följare:</h2>";
                getfollowings($user);
            print "</h2></div>";
        }
    }
    mysqli_close($db);
}

?> 

<!DOCTYPE html>
<html lang="sv-se">
<head>
    <meta charset="UTF-8">
    <title>Fnittrare - Fnitter</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body> 

	<?php require_once('menu.php'); ?>
    <div id="wrapper">
        <?php require_once('profileLink.php'); ?>
        <?php require_once('allUsers.php'); ?>

        <div class="container">
                <?php socialFnitter(); ?> 
        </div>

    </div>
<?php require_once('footer.php'); ?>

</body>
</html>