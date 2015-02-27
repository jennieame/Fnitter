<?php
//Funktioner rörande inlägg och kommentarer

//-----INLÄGG-----///

//skriv ett inlägg
function writePost($profile){
$db = connectToDb();

//lägg in ett inlägg i Databasen.
    $post      = mysqli_real_escape_string($db, $_POST['post']);
    $date      = date("Y:m:d");

    if($post == ""){
        $text = "<p>Du har inte skrivit något i inlägget</p>";
    }
    elseif(strlen($post) > 150)
    {
        $text = "<p>Tyvärr, Inlägget har fler än 150 tecken</p>";
    }
    else{
    $post    = mysqli_real_escape_string($db, $_POST['post']);

    $newPost = mysqli_query($db, "INSERT INTO `posts`(`post`, `date`, `writer`) VALUES ('$post', '$date', '$profile')");
    $text = "<p>Inlägget är nu publicerat.</p>";

    }
print $text;
mysqli_close($db);
}

//Posta inlägg
function postFlow($writers, $sendURL, $limit){
    $db = connectToDb();

    $posts = mysqli_query($db, "SELECT * FROM `posts` WHERE writer IN ('".$writers."') ORDER BY id DESC");

    if ($posts)
    {
        while($row = mysqli_fetch_assoc($posts)){

            $writer   = filter_var($row['writer'],  FILTER_SANITIZE_SPECIAL_CHARS);
            $post     = filter_var($row['post'],    FILTER_SANITIZE_SPECIAL_CHARS);
            $id       = $row['id'];

            if($writer == $_SESSION['namn']){
                $url = "profile.php";
            }
            else{
                $url = "searchProfile.php?profile=$writer";
            }

            $urlComment = "comments.php?id=$id&post=$post";

            print "<div class='post'>";
            print "<div class='note'>";
                print "<div id='$id'>";
                    print imageCurrentProfile($writer);
                    print "<div class='writer'>";
                        print "<a href='$url'><h2>".$writer. "</h2></a>";
                        print "<p class='date'>".$row['date']."</p>";
                    print "</div>";
                print "</div>";

                print "<div class='message'>";
                    linkURL($post);
                print "</div>";

                //ifall det är du som har skrivit inlägget
                if($writer == $_SESSION['namn']){
                    print "<img src='edit.png' class='edit showEdit'>";
                    editPost($id);

                    print "<form method='POST'>";
                        print "<input type='hidden' name='removeId' value='$id'>";
                        print "<input type='submit' class='buttonRemove' value='Ta bort'>";
                    print "</form>";
                }

            print "</div>";

            print "<div id='comments'>";
                print "<form action='".$sendURL."#".$id."' method='POST'>";
                    print "<input type='hidden' name='id' value='$id'>";
                    print "<input type='text' name='comment' placeholder='Kommentera'>";
                    print "<input class='button' type='submit' value='Kommentera'>";
                print "</form>";
            print "</div>";
            print "<div class='show'>Kommentarer (".getNumberOfComments($id).")</div>";
                print "<div class='comments'>";
                    postComments($id);
                print "</div>";
            print "</div>";
        }
    }
    mysqli_close($db);
}


// Gör url till länk
function linkURL($str){

    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/";
    $urls = array();
    $urlsToReplace = array();
    
    if(preg_match_all($reg_exUrl, $str, $urls)) {
        $numOfMatches = count($urls[0]);
        $numOfUrlsToReplace = 0;

        for($i=0; $i<$numOfMatches; $i++) {
            $alreadyAdded = false;
            $numOfUrlsToReplace = count($urlsToReplace);

            if(!$alreadyAdded) {
                array_push($urlsToReplace, $urls[0][$i]);
            }
        }

        $numOfUrlsToReplace = count($urlsToReplace);
        
        for($i=0; $i<$numOfUrlsToReplace; $i++) {
            $str = str_replace($urlsToReplace[$i], "<a href=\"".$urlsToReplace[$i]."\" target='blank'>".$urlsToReplace[$i]."</a> ", $str);
        }
        print $str;
    } 
    else {
        print $str;
    }
}

//-----KOMMENTARER-----//

// Skriv en kommentar
function commentPost($id, $comment, $writer){
 	$db = connectToDb();

	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		$comment = mysqli_real_escape_string($db, $comment);
        $writer  = mysqli_real_escape_string($db, $writer);
		$date    = date("Y:m:d");

		if($comment != ""){
            $newPost = mysqli_query($db, "INSERT INTO `comments`(`comment`, `writer`, `date`, `postId`) VALUES ('$comment', '$writer', '$date', '$id')");
			$news    = mysqli_query($db, "INSERT INTO `news`(`action`, `sendFrom`, `postId`) VALUES ('kommenterade ditt', '$writer', '$id')");
		}
		else{
			print  "<script> alert('Du skrev aldrig någon kommentar, Försök igen!');</script>";
		}
	}
	mysqli_close($db);
}

//Visa Kommentarer
function postComments($id){
 	$db = connectToDb();

    $checkComments = mysqli_query($db, "SELECT * FROM comments ");

	while($row = mysqli_fetch_assoc($checkComments)){

		$writer  = $row['writer'];
        $url     = "searchProfile.php?profile=$writer";
        $comment = filter_var($row['comment'],  FILTER_SANITIZE_SPECIAL_CHARS);
	        
        if($writer == $_SESSION['namn']){
            $url = "profile.php";
        }
        else{
            $url = "searchProfile.php?profile=$writer";
        }

        if($id == $row['postId']){
            print "<br>";
            print "<p><a href='$url'>". $row['writer']. "</a> <span class='date'>".$row['date']."</span></p>";
                linkURL($comment);
            print "<hr>";
		}
	}
	mysqli_close($db);
}

//Antal Kommentarer
function getNumberOfComments($post){
    $db = connectToDb();
    $countComments = mysqli_query($db, "SELECT COUNT(*) AS total FROM comments WHERE `postId` = '$post'");

    $commentData       = mysqli_fetch_assoc($countComments);
    $numberOfComments  = $commentData['total'];

    return "<span class='numberOfComments'>".$numberOfComments."</span>";

    mysqli_close($db);
}

//----  TA BORT INLÄGG  -----//

function removePost($id){
    $db = connectToDb();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        $profile  = mysqli_real_escape_string($db, $_SESSION['namn']);
        $findPost = mysqli_query($db, "SELECT * FROM posts WHERE `id` = '$id' AND `writer` = '$profile' ORDER BY id DESC");

        if ($findPost) {
            if (mysqli_fetch_assoc($findPost) > 0){

                $text = "Nu har vi tagit bort ditt inlägget!";
                print $text;

                //Ta bort inlägget
                $removePost = mysqli_query($db, "DELETE FROM `jeer`.`posts` WHERE `posts`.`id` = '$id'");
            }

            // ifall inte inlägget finns, eller inte är behörig att ta bort inlägget
            else {
                $text = "Du kan inte ta bort det här inlägget!";
                print $text;
            }
        }
    }
    mysqli_close($db);
}

//---   REDIGERA INLÄGG  ---//

function editPost($id){
    $db = connectToDb();
        $safeId = mysqli_real_escape_string($db, $id);
        $findPost = mysqli_query($db, "SELECT * FROM posts WHERE `id` = '$safeId' ORDER BY id DESC");

        if ($findPost) {
            while($row = mysqli_fetch_assoc($findPost)){
            
            print "<div class='editPost'><h3>Redigera</h3>
                    <form action='index.php#".$safeId."' method='post'> 
                        <textarea type='text' name='editPost".$safeId."' maxlength='150'>".$row['post']."</textarea><br/>
                        <input class='button' type='submit' value='Spara ändringar'>
                    </form></div>";
            }
             if (isset($_POST['editPost'.$safeId.'']))
        {
            updatePost($_POST['editPost'.$safeId.''], $id);
        }
}
    mysqli_close($db);
}

function updatePost($post, $id){
    $db = connectToDb();
    
    $updatedPost = mysqli_real_escape_string($db, $post);
    $update      = mysqli_query($db, "UPDATE `jeer`.`posts` SET `post` = '$updatedPost' WHERE `posts`.`id` = $id");

    print "Nu är inlägget uppdaterat";
    mysqli_close($db);

}

//----NYHETER-----//

//Visa Nyheter
function News(){
    $db = connectToDb();
    $inlogged = filter_var($_SESSION['namn'], FILTER_SANITIZE_SPECIAL_CHARS);

    $showNews  = mysqli_query($db, "SELECT * FROM news LEFT JOIN posts ON news.postID=posts.id WHERE news.status = 0");
    print "<ul>";
    while($row = mysqli_fetch_assoc($showNews)){
        $owner    = filter_var($row['writer'],   FILTER_SANITIZE_SPECIAL_CHARS);
        $owner2   = filter_var($row['sendTo'],   FILTER_SANITIZE_SPECIAL_CHARS);
        $sendFrom = filter_var($row['sendFrom'], FILTER_SANITIZE_SPECIAL_CHARS);
        $post     = filter_var($row['postId'],   FILTER_SANITIZE_SPECIAL_CHARS);
        $action   = filter_var($row['action'],   FILTER_SANITIZE_SPECIAL_CHARS);
        $id       = filter_var($row['id'],       FILTER_SANITIZE_SPECIAL_CHARS);

        if($owner == $inlogged || $owner2 == $inlogged){
        
            if($post > 0){
                print "<li><a href ='searchProfile.php?profile=$sendFrom'> ". $sendFrom . "</a> ". $action ."<a href='index.php#$post'> inlägg. </a></li>";
            }   
            else{
                print "<li><a href ='searchProfile.php?profile=$sendFrom'> ". $sendFrom . "</a> ". $action."</li>";
            }  
           
        }   
    }
    print "</ul>"; 
    countNews();
    print "<form method='POST'>";
    print "<input type='hidden' name='markAsSeen' value='$inlogged'>";
    print "<input type='submit' class='button' value='Markera alla som lästa'>";
    print "</form>";

    mysqli_close($db);
}

//Räkna och skriv ut antalet nyheter
function countNews(){
    $db = connectToDb();    
    $user =  mysqli_real_escape_string($db, $_SESSION['namn']);
    $countNews = mysqli_query($db, "SELECT * FROM news LEFT JOIN posts ON news.postID=posts.id WHERE news.status = 0 AND ( posts.writer LIKE '$user' OR news.sendTo LIKE '$user' )");

    $count = mysqli_num_rows($countNews);
    print "<p>Antal nyheter: ".$count."</p>";
    mysqli_close($db);
}

//Markera nyheter som lästa
function markAsSeen($user){
    $db = connectToDb();
    $countNews = mysqli_query($db, "UPDATE news LEFT JOIN posts ON news.postID = posts.id SET news.status = 1 WHERE ( posts.writer LIKE '$user' OR news.sendTo LIKE '$user')");
    mysqli_close($db);
}