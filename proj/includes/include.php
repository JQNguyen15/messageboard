<?php
session_start();

require_once('../../private_html/config.php');

/*
	Authors: Nicholas Sylvestre & James Nguyen
	Date: March 19, 2016
	Description: The purpose of this php file is to provide a library of common functions available to all the individual php files.

*/

//generates a random string of 10 characters
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}   

//function will give the user with corresponding email a random password, then call another function to email that password
function sqlSetRandomPassword($email,$pass) {
    global $servername, $dbname, $password, $username;

    try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $newpass=password_hash($pass,PASSWORD_DEFAULT);
        $sql="UPDATE users SET users.userpw='$newpass' WHERE users.useremail='$email';";

        $db->exec($sql);
        $db = null;
    }
    catch(PDOException $e) {
        die ("FATAL ERROR");
    }
}

function emailPassword($email,$pass){
    $msg = "Your new password for the Forum is $pass";
    mail("$email","Password Recovery",$msg);
}

//takes email as input, and checks if it is in DB, 1 for true, 0 for false
function checkEmail($email){
    global $servername, $dbname, $password, $username;
    try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("SELECT * FROM users WHERE useremail = :email");
        $stmt->bindValue(":email",trim($email));
        $stmt->execute();
        //0 if no results, 1 if exists
        if ($stmt->rowCount() == 0) {
            $db = null;
            return 0;
        } else if ($stmt->rowCount() >= 1) {
            $db = null;
            return 1;
        }
    }
    catch(PDOException $e) {
        die ("FATAL ERROR!");
    }
}



/*
    Function will check to see if a user is banned. If the user is banned, the user is redirected to a banned page and logged out
 */
function checkBan(){
    if (isset($_SESSION['auth'])){
        if ($_SESSION['auth_info']['userPriv']=='banned'){
            //echo '<center><img src="img/goonbegone.jpg" alt="Goon be gone!" style="width:800px;height:500px"></center><br>';
echo <<<BANNED
     <div class="well well-lg">
        <h1 class="center">GOON BE GONE</h1>
        <img src="img/goonbegone.jpg" class="centerimg" alt="Goon be gone!" style="width:800px;height:500px">
     </div>
BANNED;
            die(); 
        }
    }
}

/*
	Description:	The purpose of this function is to connect to the database using PDO method and run a query statement, returning the results.
					It will take a string as an input (the SQL Query) and use that string on the database and return the results as an array or false. 
*/
function sqlQPDO($sqlStatement){
    global $servername, $dbname, $password, $username;
	try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    	$statement = $db->prepare($sqlStatement);
    	$statement->execute();
        if ($statement->rowCount() == 0) {
            return 0;
        } else if ($statement->rowCount() >= 1) {
            $result=$statement->fetchAll();
        }
        
        $db = null;
    	return $result;
    }
    catch(PDOException $e) {
        die ("FATAL ERROR!");
    }
}

/*
	Description:	The purpose of this function check to see if the provided username exists in the database.
					It will take the username as a string as input, create a connection to the database, sanitize the input by using the PDO bind,
					then return a boolean true or false if the username exists.
*/
function checkAvailUsername($user) {
    global $servername, $dbname, $password, $username;
	try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    	$stmt = $db->prepare("SELECT * FROM users WHERE username = :user");
    	$stmt->bindValue(":user",trim($user));
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $db = null;
            return 0;
        } else if ($stmt->rowCount() >= 1) {
            $db = null;
            return 1;
        }
     
    }
    catch(PDOException $e) {
        die ("FATAL ERROR!");
    }

}

/*
	Description:	The purpose of this function is to delete a record from the replies table.
					It takes in an integer, the unique id of the reply record and deletes the record.
*/
function sqlDelReply($replyID){
    global $servername, $dbname, $password, $username;
    try{
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        // sql to delete a reply
        $sql = "DELETE FROM replies WHERE replyID=$replyID";

        // use exec() because no results are returned
        $db->exec($sql);
		$db=null;
    }    
    catch(PDOException $e){
        die ("FATAL ERROR");
    }
    
}


/*
	Description:	The purpose of this function is to delete a record from the topics table.
					Because there is a foriegn key restraint, we must first delete the replies before we can delete the topics
					It takes in an integer, the unique id of the topic record and deletes the record after deleting all replies.
*/
function sqlDelTopic($topicID){
    global $servername, $dbname, $password, $username;
    try{
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        // sql to delete a topic
        $sql = "DELETE FROM topics WHERE topicID=$topicID";
        $delreplies="DELETE FROM replies WHERE topicID=$topicID";
        //have to delete all the replies attached to a topic before deleting a topic
        $db->exec($delreplies);
        // use exec() because no results are returned
        $db->exec($sql);
    }    
    catch(PDOException $e){
        die ("FATAL ERROR");
    }
    $db=null;
}


/*
	Description:	The purpose of this function is to delete a record from the category table.
					Because there is a foriegn key restraint, we must first delete the replies before we can delete the topics, then 
					the topics before we can delete the category.
					It takes in an integer, the unique id of the category record and deletes the record after deleting all topics and replies.
*/
function sqlDelCategory($catID){
    global $servername, $dbname, $password, $username;
    try{
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        // sql to delete topics from a particular category
        $deltopics = "DELETE FROM topics WHERE catID=$catID";

        //delete all replies in all the topics
        $delreplies="DELETE FROM replies WHERE replies.topicID IN (SELECT topicID FROM topics WHERE catID=$catID)";

        $sql="DELETE FROM category WHERE catID=$catID";
        //have to delete all the replies attached to a topic before deleting a topic
        //then delete all the topics in a category before we can delete a category
        $db->exec($delreplies);
        $db->exec($deltopics);
        $db->exec($sql);
    }    
    catch(PDOException $e){
        die ("FATAL ERROR");
    }
    $db=null;
}

/*
	Description:	The purpose of this function is to create a new record in the category table.
					It takes in category name and description and inserts the record with a unique category ID.
					The function returns the newly created category ID to the caller.
*/
function sqlNewCategoryPDO($catName, $catDescription) {
    global $servername, $dbname, $password, $username;
	try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $statement = $db->prepare("INSERT INTO category (catName, catDescription) VALUES (:catName, :catDescription)");
        $statement->bindValue(':catName', $catName);
        $statement->bindValue(':catDescription', $catDescription);
        $statement->execute();
        return $db->lastInsertId("catID"); 
        $db = null;
    }
    catch(PDOException $e) {
        die ("FATAL ERROR");
    }
}


/*
	Description:	The purpose of this function is to create a new record in the topic table.
					It takes in Topic Name, which category id it belongs to, the user id that created the topic and the reply content 
					and inserts the record with a unique topic ID and reply ID.
					The function returns an array with the newly created topic ID and reply ID.
*/
function sqlNewTopicPDO($topicName, $catID, $userID, $replyContent) {
    global $servername, $dbname, $password, $username;
	try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $statement = $db->prepare("INSERT INTO topics (topicName, catID, userID) VALUES (:topicName, :catID, :userID)");
        $statement->bindValue(':topicName', $topicName);
        $statement->bindValue(':catID', $catID);
        $statement->bindValue(':userID', $userID);
        var_dump($statement);
        $statement->execute();
        $ids['topicID'] = $db->lastInsertId("topicID"); 
        

        $statement = $db->prepare("INSERT INTO replies (replyContent, topicID, userID) VALUES (:replyContent, :topicID, :userID)");
        $statement->bindValue(':replyContent', $replyContent);
        $statement->bindValue(':topicID', $ids['topicID']);
        $statement->bindValue(':userID', $userID);
        $statement->execute();
        $ids['replyID'] = $db->lastInsertId("replyID");
        $db = null;
        return $ids;
    }
    catch(PDOException $e) {
        die ("FATAL ERROR");
    }
}


/*
	Description:	The purpose of this function is to create a new record in the reply table.
					It takes in user ID that created the reply, reply content and the topic which the reply belongs to and inserts the record 
					with a unique reply ID.
					The function returns the newly created reply ID.
*/
function sqlNewReplyPDO($userID, $replyContent, $topicID) {
    global $servername, $dbname, $password, $username;
	try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $statement = $db->prepare("INSERT INTO replies (replyContent, topicID, userID) VALUES (:replyContent, :topicID, :userID)");
        $statement->bindValue(':replyContent', $replyContent);
        $statement->bindValue(':topicID', $topicID);
        $statement->bindValue(':userID', $userID);
        $statement->execute();
        $uid = $db->lastInsertId("replyID");
        $db = null;
        return $uid;
    }
    catch(PDOException $e) {
        die ("FATAL ERROR");
    }
}

/*
	Description:	The purpose of this function is to create a new record in the users table.
					It takes in the email address, username and password for the user, hashes the password using SHA2 and inserts the record.
					The function returns the newly created unique userID.
*/
function sqlNewUserPDO($email, $user, $pass) {
    global $servername, $dbname, $password, $username;
	try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $statement = $db->prepare("INSERT INTO users (username, userpw, useremail) VALUES (:username, SHA2(:userpw,256), :useremail)");
        $statement->bindValue(':username', trim($user));
        $statement->bindValue(':userpw', trim($pass));
        $statement->bindValue(':useremail', trim($email));
        $statement->execute();
        $uid = $db->lastInsertId("userID");
        $db = null;
        return $uid;
    }
    catch(PDOException $e) {
        echo $e->getMessage();
        die ("FATAL ERROR");
    }
}

/*
	Description:	The purpose of this function is to update a record in the users table.
					It takes in the updated email and password and updates the record for the current user.
*/
function sqlUpdateUserPDO($email, $pass) {
    global $servername, $dbname, $password, $username;
	try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $statement = $db->prepare("UPDATE users SET userpw=SHA2(:userpw,256), useremail=:useremail WHERE username=\"{$_SESSION['auth_info']['username']}\"");
        $statement->bindValue(':userpw', trim($pass));
        $statement->bindValue(':useremail', trim($email));
        $statement->execute();
        sqlAuthPDO($_SESSION['auth_info']['username'], $pass);
        $db = null;
    }
    catch(PDOException $e) {
        die ("FATAL ERROR");
    }
}

/*
	Description:	The purpose of this function is to logout the currently logged in user.
					The function destroys the super global SESSION and redirects the user to the main page.
*/
function doLogout() {
  $_SESSION = array();
  session_destroy();  
  header("Location: index.php");
}

/*
	Description:	The purpose of this function is to authenticate the user against the database of users.
					It takes in a username and a password and hashes the password and compares the values against the users table.
					If the statement has a rowCount > 0 then there exists a user in the database that matches the given username password and
					the function populates the super global $_SESSION with the user's information, and a boolean flag to indicate the user is
					authenticated and returns true.
					If there is no match (rowCount == 0) then we mark the authentication attempt so we can send feedback to the user and the function
					returns false.
					
*/
function sqlAuthPDO($user, $pass) {
    global $servername, $dbname, $password, $username;
    $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :name AND userpw = SHA2(:password,256)");
	$stmt->bindValue(":name",trim($user));
	$stmt->bindValue(":password",trim($pass));
	$stmt->execute();

	if($stmt->rowCount() == 1){ 
		unset($_SESSION['auth_attempt']);
        $_SESSION['auth'] = true;   
		$_SESSION['auth_info'] = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;
        return true;
    } else {
        $_SESSION['auth_attempt'] = true;
        $db = null;
        return false;
    }      	        
}

/*
	Description:	The purpose of this function is to generate the header for each html file.
					More comments have been included in the function itself.
*/
function generateHeader($title) {

	//If we hit the logout button, perform the logout function
	if (isset($_REQUEST['logoutButton'])) {
		doLogout();
	}
	
	//If we pressed the login button, perform the authenticate function and if the user is on the register page, take them to the main page.
	if (isset($_REQUEST['loginButton'])) {
		if (sqlAuthPDO($_POST['username'], $_POST['password']) && stristr($_SERVER['PHP_SELF'], 'register.php')) {
			header('Location: index.php');
		} 
	}
	
	//Set the relative location(directory) for the css and js files
    $cssLoc = './css';
    $jsLoc = './js';
    
echo <<<GENHEAD
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="img/favicon.ico">

        <title>JNAC Forums - $title</title>

GENHEAD;
	
	// Dynamically load all the css files from the css directory
    echo "      <!-- CSS -->\n";
    $files = scandir($cssLoc);
    foreach ($files as $file) {
        if (is_file("$cssLoc/$file")) 
            echo "   <link href='$cssLoc/$file' rel='stylesheet'>\n";
    }
    
	// Dynamically load all the js files from the css directory
    echo "      <!-- JavaScript -->\n";
    $files = scandir($jsLoc);
    foreach ($files as $file) {
        if (is_file("$jsLoc/$file")) 
            echo "   <script src='$jsLoc/$file'></script>\n";
    }

    echo "</head>";
}

/*
	Description:	The purpose of this function is to generate the footer for each html file.

*/
function generateFooter() {

echo<<<GENFOOT
        <!-- start of footer -->
        <div class="well well-sm footer">
            <div class="row">
                <div class="col-xs-6 col-sm-4">
                    <p class="text-left">Running:</p>
                    <img class="pull-left logo" src="img\Boostrap_logo.svg.png"/>
                    <img class="pull-left logo" src="img\iconmonstr-linux-os-3-48.png"/>

                </div>
                
                <div class="col-xs-6 col-sm-4">
                    <p class="text-center">&copy; 2016 JNAC Inc.</p>
                    <p class="text-center">Andrew Azenabor<br />James Nguyen<br />Carson Siu<br />Nicholas Sylvestre
                    </p>
                </div>
                
                <div class="col-xs-6 col-sm-4">
                    <p class="text-right">Supported By:</p>
                    <img class="pull-right logo" src="img\iconmonstr-firefox-3-48.png"/>
                    <img class="pull-right logo" src="img\iconmonstr-chrome-3-48.png"/>
                    <img class="pull-right logo" src="img\iconmonstr-internet-explorer-3-48.png"/>
                </div>
            </div>
        </div>

    </div> <!-- /container -->

  </body>
</html>

GENFOOT;
}

/*
	Description:	The purpose of this function is to generate the body, logo and menu bar for each web page.
					More comments have been included in the function itself.
*/
function logoNav() {
echo<<<LOGONAV
  <!-- start of body -->
  <body>

    <div class="container">

        <div>
            <img class="img-responsive" id="logo" src="img/testlogo.png" />
        </div>

       <nav class="navbar navbar-default">
        <div class="container-fluid">
         <div class="row">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
            </div>
            
            <div id="navbar" class="navbar-collapse collapse">
                <div class="col-xs-6">
                    <ul class="nav navbar-nav">
                      <li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a></li>
LOGONAV;
if (!isset($_SESSION['auth'])){
echo<<<LOGONAV
                    <li><a href="forgot.php"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Forgot Password</a></li>
LOGONAV;
}
echo<<<LOGONAV
                </ul>
                </div>
                <div class="col-xs-6">
LOGONAV;

/*
		removed these lines from the navbar for eti
		<li><a href="faq.php"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> FAQ</a></li>
        <li><a href="contact.php"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Contact Us</a></li>
 */
				//Check to see if the user has been autenticated successfully
                if(isset($_SESSION['auth'])){
echo<<<LOGONAV
                    <ul class="nav navbar-nav nav-stacked pull-right">
LOGONAV;

					//Check to see if the user is an administrator, if so display the administration button
					if ($_SESSION['auth_info']['groupID'] == 2) {
						echo "<li><a class=\"text-danger\" href=\"admin.php\"><span class=\"glyphicon glyphicon-flash\" aria-hidden=\"true\"></span> Admin</a></li>";
					}
echo<<<LOGONAV
                        <li><a href="myProfile.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>  My Profile</a></li>
                        <li>
                        <form class="form-inline" action="{$_SERVER['PHP_SELF']}" method="post">
                            <button type="submit" id="logoutButton" name="logoutButton" class="btn btn-danger logingroup"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</button>
                        </form>
                        </li>
                    </ul>
LOGONAV;
                } else { //If the user is not authenticated then display the login/password input boxes and submit/register button
echo<<<LOGONAV
                    <ul class="nav navbar-nav nav-stacked pull-right">
                        <li>
                            <form class="form-inline logingroup" action="{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}" method="post">
                              
LOGONAV;
					//Check to see if the user made an authenication request against the database. 
					//If it failed display a warning and repopulate the username box with what was submitted.
					if (isset($_SESSION['auth_attempt'])) { 
echo <<<FORMSTUFF
    <div class="form-group has-error">
    <input type="text" class="form-control input-sm" id="username" name="username" placeholder="Username" maxlength="20" value="{$_POST['username']}">
    <script type="text/javascript">
    $(document).ready(function(){
    	$('#username').popover({
    		placement: 'left',
    		trigger: 'manual',
    		content: 'Invalid Email or Password',
    		
    	});
    	$('#username').popover('show');
    	setTimeout(function(){
    		$('#username').popover('destroy');
    		},3000);
     });
    </script>
FORMSTUFF;
					//Remove the authentication failed attempt flag.
					unset($_SESSION['auth_attempt']);
					} else { //Display the normal login/password input and sign in/register buttons
echo <<<FORMSTUFF
    <div class="form-group">
    <input type="text" class="form-control input-sm" id="username" name="username" placeholder="Username" maxlength="20">
FORMSTUFF;
					}
echo<<<LOGONAV
                              
                              <div class="form-group">
                                <input type="password" class="form-control input-sm" id="password" name="password" placeholder="Password">
                              </div>
                              <button type="submit" name="loginButton" id="loginButton" class="btn btn-info btn-sm">Sign in</button>
                            </form>
                                <a href="register.php" class="btn btn-danger btn-sm">Register</a>
                            </div>
                        </li>
                        <li>
                            
                        </li>                                        
                    </ul>                    
LOGONAV;
                }
echo<<<LOGONAV
               </div>
            </div>       
        </div>
       </div>
      </nav>
LOGONAV;

//checkBan(); 
}

/*
	Description:	The purpose of this function is to generate the search buttton available on the main page, the topics page and the replies page.
*/
function showSearch() {
echo <<<SEARCHBAR
		<div class="col-md-6 pull-right">
            <form class="form-horizontal" role="form" method="POST" action="search.php" id="searchForm" name="searchForm">
                <div class="input-group" id="adv-search">
                    <input type="text" class="form-control" placeholder="Search..." id="searchWords" name="searchWords" maxlength="120"/>
                        <div class="input-group-btn">
                            <div class="btn-group" role="group">
                                <div class="dropdown dropdown-lg">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>                
                                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                              <div class="form-group" id="searchOptionsDiv">
                                                <label for="filter">Filter by</label>
                                                    <select class="form-control" id="searchOptions" name="searchOptions">
                                                    <option value="0" selected>All</option>
                                                    <option value="1">Categories</option>
                                                    <option value="2">Topics</option>
                                                    <option value="3">Replies</option>
                                                </select>
                                              </div>
                                              <div class="form-group">
                                                <label for="contain">Author</label>
                                                <input class="form-control" type="text" name="searchAuthor" id="searchAuthor" maxlength="50"/>
                                              </div>
                                              <button type="submit" name="searchSubmit" id="searchSubmit1" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                        </div>
                                    </div>
                                <button type="submit" name="searchSubmit" id="searchSubmit2" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

SEARCHBAR;
}

/*
	Description:	The purpose of this function is to print the names and descriptions of all categories in the category table
					It takes in an array of categories from the database, counts the number of topics and replies within each category as well as the last user who entered a post and
                    displays all the information within a table.
*/
function printCategories($listOfCategories) {
    
    //For every category in the list
    foreach ($listOfCategories as $category) {
        //Perform an sql query to determine the number of topics in the category
        $numOfTopics = sqlQPDO("SELECT Count(*) as Total FROM category NATURAL JOIN topics WHERE catID = {$category['catID']};");
        
        //Perform an sql query to determine the number of replies in the category
        $numOfReplies = sqlQPDO("SELECT Count(*) AS Total FROM category, topics, replies WHERE category.catID = topics.catID AND topics.topicID = replies.topicID AND category.catID = {$category['catID']};");
        
        //Perform an sql query to determine the person that last posted in the category
        $lastPost = sqlQPDO("SELECT * FROM category, topics, replies, users WHERE category.catID = topics.catID AND topics.topicID = replies.topicID AND replies.userID = users.userID AND category.catID = {$category['catID']} ORDER BY replies.replyDate DESC LIMIT 1;");
        
        //If the category is not empty
        if (!$lastPost) {
            $lastPost = "";
        } else {
            // Format the date and time stored in the database to human readable
            $lastPost[0]['replyDate'] = date( 'j M Y g:ia', strtotime($lastPost[0]['replyDate']) );
            $lastPost = $lastPost[0]['replyDate'] . " by: <br/> " . $lastPost[0]['username'];
        }
echo <<<PRINTTBL
                <tr>
                    <td class="col-md-6">
                        <!-- Insert Category here -->
                        <h3><a href="viewCat.php?cat={$category['catID']}">{$category['catName']}</a></h3>
                        <!-- Insert Category Description here -->
                        <p>{$category['catDescription']}</p>
                    </td>
                    <td class="col-md-1">
                        <!-- Insert # of topics here -->
                        <p>{$numOfTopics[0]['Total']}</p>
                    </td>
                    <td class="col-md-1">
                        <!-- Insert # of replies here -->                    
                        <p>{$numOfReplies[0]['Total']}</p>
                    </td>
                    <td class="col-md-2">
                        <!-- Insert date & person here  -->
                        <p>$lastPost</p>
                    </td>
                </tr>
PRINTTBL;
    };

}

/*
	Description:	The purpose of this function is to print information about each topic in a table
					It takes in an array of topics from the database, counts the number of replies within each topic as well as the last user who entered a post and
                    displays all the information within a table.
*/
function printTopics($listOfTopics) {
    
    //For every topic in the list
    foreach ($listOfTopics as $topic) {
        
        //Perform an sql query to determine the number of replies in the topic
        $numOfReplies = sqlQPDO("SELECT Count(*) AS Total FROM replies WHERE topicID = {$topic['topicID']};");
        //Decrement one because of the original topic 
        $numOfReplies[0]['Total']--;
        
        //Perform an sql query to determine the person that last posted in the topic & the date last posted in human readable
        $lastPost = sqlQPDO("SELECT * FROM replies NATURAL JOIN users WHERE topicID = {$topic['topicID']} ORDER BY replyDate DESC LIMIT 1;");
        $lastPost[0]['replyDate'] = date( 'M j Y g:ia', strtotime($lastPost[0]['replyDate']) );
        $lastPost = $lastPost[0]['replyDate'] . " by: <br/> " . $lastPost[0]['username'];
                        
                    
echo <<<PRINTTBL
                <tr>
                    <td class="col-md-6">
                        <!-- Insert Topic here -->
                        <h4><a href="viewTopic.php?top={$topic['topicID']}">{$topic['topicName']}</a></h4>
                    </td>
                    <td class="col-md-1">
                        <!-- Insert # of replies here -->                    
                        <p>{$numOfReplies[0]['Total']}</p>
                    </td>
                    <td class="col-md-2">
                        <!-- Insert date & person here  -->
                        <p>$lastPost</p>
                    </td>
                </tr>
PRINTTBL;
    };    
}

/*
	Description:	The purpose of this function is to print the replies to each topic
					It takes in an array of replies from the database and weather or not the is being shown on the search result page or in the normal forum view, 
                    and prints the reply content in a table including the reply date an the user's joined date in human readable form 
*/
function printReplies($listofReplies, $search) {
    foreach ($listofReplies as $replies) {
        $replies['replyDate'] = date( 'j M Y g:ia', strtotime($replies['replyDate']) );
        $replies['joinDate'] = date( 'j M Y g:ia', strtotime($replies['joinDate']) );

echo <<<PRINTTBL
                <tr id="r{$replies['replyID']}">
                    <td class="col-md-8 replyPadding">
                        <p>{$replies['replyContent']}</p>

PRINTTBL;
        if (isset($_SESSION['auth'])) {
            if (!$search) {
echo <<<PRINTREPLY
                        <a href="#reply"><button type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Reply</button></a>
PRINTREPLY;
            } else {
echo <<<PRINTCONTEXT
                        <a href="viewTopic.php?top={$replies['topicID']}#r{$replies['replyID']}"><button type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> View Context</button></a>
PRINTCONTEXT;
            }
            
            if ($_SESSION['auth_info']['groupID'] == 2){
echo <<<PRINTDELETE
                        <a href="viewTopic.php?top={$replies['topicID']}&delete={$replies['replyID']}"><button type="button" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</button></a>
PRINTDELETE;
            }
        }
echo <<<PRINTTBL
                    </td>
                    <td class="col-md-3">
                        <p>Posted By: {$replies['username']}<br/>
                        Posted: {$replies['replyDate']}<br/>
                        Joined: {$replies['joinDate']}<br/>
                        </p>
                    </td>
                </tr>
PRINTTBL;
    };

}

/*
	Description:	The purpose of this function is to print an information box to the user
					It takes in the text to be displayed to the user and the type of information that should be displayed and prints it to the user. 
*/
function printSuccessBox($text, $type) {
echo <<<PRINTSUCC
            <div class="row">
                <div class="alert alert-{$type} alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p class="text-center"><strong>$text</strong></p>
                </div>
            </div>
PRINTSUCC;
}

/*
	Description:	The purpose of this function is to print a back button to the user
					It outputs a button which, when clicked will invoke the back button via javascript 
*/
function displayBackButton() {
echo <<<PRINTBACK
    <div class="col-md-10 pull-left">
        <button type="button" onclick="javascript:history.back()"class="btn btn-warning active"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Back</button>
    </div>     
PRINTBACK;
}


?>



