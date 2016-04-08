<?php 


/*
	Description:	The purpose of this function is to connect to the database using PDO method and run a query statement.
					It will take a string as an input (the SQL Query) and use that string on the database. The script will die if its been run more than once or the credentials are incorrect 
*/
function sqlDBPDO($query) {
    global $servername, $dbname, $password, $username;
	try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $statement = $db->prepare($query);
        $statement->execute();
        $db = null;
    }
    catch(PDOException $e) {
        die ("<br/><strong>FATAL ERROR</strong>: Incorrect credentials provided OR Databse already exists and is populated!");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="img/favicon.ico">

        <title>JNAC Forums - Installation</title>
<?php

//Dynamically load all css from the following location
$cssLoc='./css';
$jsLoc='./css';

echo "      <!-- CSS -->\n";
$files = scandir($cssLoc);
foreach ($files as $file) {
    if (is_file("$cssLoc/$file")) 
        echo "   <link href='$cssLoc/$file' rel='stylesheet'>\n";
}

echo "</head>";
?>
  <!-- start of body -->
  <body>

    <div class="container">

        <div>
            <img class="img-responsive" id="logo" src="img/testlogo.png" />
        </div>
              
      <!-- start of forum -->
        <div class="well well-lg">
            <h3>Configuration/DB Installation</h3>
            
<?php
// If the user has hit the submit button on the database information page
if (isset($_POST['submit'])) {
    
    //Check to see if all fields have been input
    if (!isset($_POST['inputHOSTNAME']) || !isset($_POST['inputDBNAME']) || !isset($_POST['inputDBUSERNAME']) || !isset($_POST['inputDBPASSWORD']) || !isset($_POST['inputDBPASSWORDCONFIRM'])) die("<strong>Fatal Error:</strong> Missing Parameters! Please go back and try again");

    //Trim the input from the user (may cause problems with password matching and config.php generation)
    $servername = trim($_POST['inputHOSTNAME']);
    $dbname = trim($_POST['inputDBNAME']);
    $username = trim($_POST['inputDBUSERNAME']);
    $password = trim($_POST['inputDBPASSWORD']);
    $passwordconfirm = trim($_POST['inputDBPASSWORDCONFIRM']);

    //If the password don't match, report a fatal error!
    if ($password !== $passwordconfirm) die ("<strong>Fatal Error: </strong>Passwords do not match!");

    
// create an array that will store the database schema and all sample data
$dbinfo = array();

// Create an array that creates all tables on the database    
$tables[] = "CREATE TABLE IF NOT EXISTS `category` (
  `catID` int(10) NOT NULL,
  `catName` varchar(50) NOT NULL,
  `catDescription` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;";

$tables[] = "CREATE TABLE IF NOT EXISTS `groups` (
  `groupID` int(5) NOT NULL,
  `groupName` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;";

$tables[] = "CREATE TABLE IF NOT EXISTS `replies` (
  `replyID` int(10) NOT NULL,
  `replyContent` mediumtext,
  `replyDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `topicID` int(10) NOT NULL,
  `userID` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;";

$tables[] = "CREATE TABLE IF NOT EXISTS `topics` (
  `topicID` int(10) NOT NULL,
  `topicName` varchar(80) NOT NULL,
  `catID` int(10) NOT NULL,
  `userID` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;";

$tables[] = "CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `userpw` varchar(64) NOT NULL,
  `useremail` varchar(30) NOT NULL,
  `groupID` int(5) NOT NULL DEFAULT '1',
  `userPriv` varchar(50) NOT NULL DEFAULT 'normal',
  `joinDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;";

//Move the tables array into the master db array
array_push ($dbinfo, $tables);

// Create an array that populates all the sample data into the database
$populate[] = "INSERT INTO `category` VALUES
(8, 'General Discussion', 'Talk about anything here!'),
(9, 'Java Discussion', 'Java Programming, Troubleshooting and help'),
(10, 'C/C++ Discussion', 'C/C++ programming talk & troubleshooting'),
(11, 'Web Languages', 'PHP, SQL, JavaScript, CSS and HTML');";

$populate[] = "INSERT INTO `groups` VALUES
(1, 'Regular'),
(2, 'Admin');";

$populate[] = "INSERT INTO `replies` VALUES
(21, '<p><strong>Welcome</strong> to the forums</p>', '2016-03-28 22:17:40', 21, 22),
(22, '<p>WoOOooOOo</p>', '2016-03-28 22:18:00', 21, 22),
(24, '<p>I am new to web programming and i am not sure how to use xampp. How do i do? thanks.</p>', '2016-03-28 22:18:49', 22, 21),
(25, '<p>follow these steps</p>\r\n<p>https://blog.udemy.com/xampp-tutorial/</p>', '2016-03-28 22:19:39', 22, 22),
(26, '<p>hey new programmer, go to</p>\r\n<p>https://blog.udemy.com/xampp-tutorial/</p>\r\n<p>to get a tutorial</p>', '2016-03-28 22:20:25', 22, 21),
(27, '<p>Any suggestions?</p>', '2016-03-28 22:21:06', 23, 22),
(28, '<h2><code>Java rocks, especially when it''s taught by Subir</code></h2>', '2016-03-28 22:21:35', 24, 20),
(29, '<p>LOL</p>', '2016-03-28 22:22:03', 24, 20),
(31, '', '2016-03-28 22:23:18', 25, 21),
(32, '<p>There are to many web languages. Why cant we have ONE standard?!?!?</p>', '2016-03-28 22:23:28', 26, 20),
(36, '<p>Hey guys, i want to learn how to program. What languages do i start with?</p>', '2016-03-28 22:25:00', 27, 21),
(38, '<p>heLLo!!</p>', '2016-03-28 23:34:33', 21, 22),
(39, '<p>Is Java like JavaScript? They both have Java in it, I heard they were pretty similar</p>', '2016-03-29 00:20:14', 24, 23),
(40, '<p>Look Ma! I''m on the Internets!&nbsp;</p>', '2016-03-29 00:21:38', 21, 23),
(41, '<p>I heard Haskell was really good for learning how to program and is used in a lot of modern programs.&nbsp;</p>', '2016-03-29 00:22:37', 27, 23),
(42, '<p>Hi! I would like to get to know all you other fourm folks better so I thought I''d make a post about sharing about our favourite animals.</p>\r\n<p>As you can see, my username is ILikeTurtles and my favourite animal is the cow.</p>\r\n<p>That is all.<br /><br /></p>', '2016-03-29 00:26:31', 29, 24),
(43, '<p>One language standard would oversaturate the market with web programmers with no where to go, we''d be out of jobs if that was the case!&nbsp;</p>', '2016-03-29 00:28:01', 26, 24),
(44, '<p>Testing</p>', '2016-03-29 00:29:18', 30, 22),
(46, '<p>hey</p>', '2016-03-29 01:03:17', 21, 24),
(47, '<p>I don''t know&nbsp;</p>', '2016-03-29 01:32:38', 27, 24),
(65, '<p>Absolute C++</p>', '2016-03-29 17:04:29', 23, 20),
(66, '<p>Stroupstrup''s Books</p>', '2016-03-29 17:04:45', 23, 20),
(68, '<p>im a dumb ape</p>', '2016-03-30 17:40:55', 37, 25),
(69, '<p>testing</p>', '2016-03-30 18:12:04', 38, 20);";

$populate[] = "INSERT INTO `topics` VALUES
(21, 'Hello everyone!', 8, 22),
(22, 'How do  i use xampp', 8, 21),
(23, 'Favorite C++ textbook', 10, 22),
(24, 'Java is great!', 9, 20),
(25, 'hey guys, i want to learn how to program. What lan', 11, 21),
(26, 'Problems with Web Languages', 11, 20),
(27, 'Want to program', 11, 21),
(29, 'Whats your favourite Animal?', 8, 24),
(30, 'Hey!', 8, 22),
(37, 'ooh ooh ahh ahh', 8, 25),
(38, 'just a test', 8, 20);";

$populate[] = "INSERT INTO `users` VALUES
(20, 'sylvestn', '8bb0cf6eb9b17d0f7d22b456f121257dc1254e1f01665370476383ea776df414', 'sylvestn@uwindsor.ca', 2, 'normal', '2016-03-28 22:14:20'),
(21, 'azen', '8bb0cf6eb9b17d0f7d22b456f121257dc1254e1f01665370476383ea776df414', 'azenaboa@uwindsor.ca', 1, 'normal', '2016-03-28 22:16:20'),
(22, 'JohnDoe', '95364982e77a908360e2d455acc875f0320d50f8a33aa0389d894929bd53cd4d', 'nguyen1v@uwindsor.ca', 2, 'normal', '2016-03-28 22:16:25'),
(23, 'SiuKingBon', '8a9bcf1e51e812d0af8465a8dbcc9f741064bf0af3b3d08e6b0246437c19f7fb', 'cto_alpharius@hotmail.com', 1, 'normal', '2016-03-29 00:19:14'),
(24, 'ILikeTurtles', '5c1f24b0bfe2b3cfb056f1450fde6c0e13d9d541dd7d38c16e0493ffe5001716', 'myemail@emails.com', 1, 'normal', '2016-03-29 00:25:06'),
(25, 'murs', 'f52fbd32b2b3b86ff88ef6c490628285f482af15ddcb29541f94bcf526a3f6c7', 'gorgexpress@gmail.com', 1, 'normal', '2016-03-30 17:39:52'),
(26, 'testing', 'aef9543ac1781e7543351d4c94fd756f4c39119a9cbab3a5d7494b15b65689f6', 'test@test.com', 1, 'normal', '2016-03-30 17:45:30'),
(27, 'testing', 'aef9543ac1781e7543351d4c94fd756f4c39119a9cbab3a5d7494b15b65689f6', 'test@test.com', 1, 'normal', '2016-03-30 17:46:04'),
(28, 'testing', 'aef9543ac1781e7543351d4c94fd756f4c39119a9cbab3a5d7494b15b65689f6', 'test@test.com', 1, 'normal', '2016-03-30 17:46:09'),
(29, 'testing', 'aef9543ac1781e7543351d4c94fd756f4c39119a9cbab3a5d7494b15b65689f6', 'test@test.com', 1, 'normal', '2016-03-30 17:47:11'),
(30, 'preney', '8bb0cf6eb9b17d0f7d22b456f121257dc1254e1f01665370476383ea776df414', 'preney@uwindsor.ca', 2, 'normal', '2016-03-30 22:21:16');";

//Move the populate array into the master db array
array_push ($dbinfo, $populate);

// Create an array that sets all primary keys on all the tables
$PKs[] = "ALTER TABLE `category`
  ADD PRIMARY KEY (`catID`);";
  
$PKs[] = "ALTER TABLE `groups`
  ADD PRIMARY KEY (`groupID`);";
  
$PKs[] = "ALTER TABLE `replies`
  ADD PRIMARY KEY (`replyID`), ADD KEY `constraint_3` (`userID`), ADD KEY `constraint_4` (`topicID`);";

$PKs[] = "ALTER TABLE `topics`
  ADD PRIMARY KEY (`topicID`), ADD KEY `constraint_1` (`catID`), ADD KEY `constraint_2` (`userID`);";
  
$PKs[] = "ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`), ADD KEY `constraint_5` (`groupID`);";

//Move the primary keys array into the master db array
array_push ($dbinfo, $PKs);

// Create an array that sets all auto incrementaions on all primary keys on all the tables
$autoInc[] = "ALTER TABLE `category`
  MODIFY `catID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;";

$autoInc[] = "ALTER TABLE `groups`
  MODIFY `groupID` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;";

$autoInc[] = "ALTER TABLE `replies`
  MODIFY `replyID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;";
 
$autoInc[] = "ALTER TABLE `topics`
  MODIFY `topicID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;";

$autoInc[] = "ALTER TABLE `users`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;";

//Move the auto increment array into the master db array
array_push ($dbinfo, $autoInc);

// Create an array that sets all foreign key constraints on all tables
$constraints[] = "ALTER TABLE `replies`
ADD CONSTRAINT `constraint_3` FOREIGN KEY (`userID`) REFERENCES `users` (`UserID`),
ADD CONSTRAINT `constraint_4` FOREIGN KEY (`topicID`) REFERENCES `topics` (`topicID`);";

$constraints[] = "ALTER TABLE `topics`
ADD CONSTRAINT `constraint_1` FOREIGN KEY (`catID`) REFERENCES `category` (`catID`),
ADD CONSTRAINT `constraint_2` FOREIGN KEY (`userID`) REFERENCES `users` (`UserID`);";

$constraints[] = "ALTER TABLE `users`
ADD CONSTRAINT `constraint_5` FOREIGN KEY (`groupID`) REFERENCES `groups` (`groupID`);";

//Move the constraints array into the master db array
array_push ($dbinfo, $constraints);


//Dynamically create the config.php that stores authentication information for the website (must be placed into private_html)
$myfile = fopen("config.php", "w");
fwrite($myfile, '<?php' . "\n");
fwrite($myfile, '//Description: The purpose of this php file is to store all configuration options that for the database.'. "\n");
fwrite($myfile, "\$servername = '". $servername . "';\n");
fwrite($myfile, "\$username = '". $username . "';\n");
fwrite($myfile, "\$password = '". $password . "';\n");
fwrite($myfile, "\$dbname = '". $dbname . "';\n");
fwrite($myfile, '?>');
fclose($myfile);

//Itterate through the master db array and execute each SQL into the database
foreach ($dbinfo as $directive) {
    foreach ($directive as $step) {
        sqlDBPDO($step);
    }
}

///Display success only if all commands are successful
echo <<<SUCCESS
    <div class="row">
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p class="text-center"><strong>IMPORT SUCCESSFUL!</strong> Remember: Please move <strong>'config.php'</strong> to the private_html directory</p>
        </div>
    </div>
SUCCESS;
    
//If the user is visiting the installdb.php for the first time, display the db form so the user can fill out the credentials    
    } else {
?>
            <div class="row">
                <div class="col-sm-8">            
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="installdb" name="installdb">
                      <div class="form-group">
                        <label for="inputHOSTNAME" class="col-sm-4 control-label">Database Hostname</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="inputHOSTNAME" name="inputHOSTNAME" placeholder="Database Hostname">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputDBNAME" class="col-sm-4 control-label">Database Name</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="inputDBNAME" name="inputDBNAME" placeholder="Database Name">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputDBUSERNAME" class="col-sm-4 control-label">Database Username</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="inputDBUSERNAME" name="inputDBUSERNAME" placeholder="Database Username">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputDBPASSWORD" class="col-sm-4 control-label">DB Password</label>
                        <div class="col-sm-4">
                          <input type="password" class="form-control" id="inputDBPASSWORD" name="inputDBPASSWORD" placeholder="Database Password">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputDBPASSWORDCONFIRM" class="col-sm-4 control-label">DB Password Confirmation</label>
                        <div class="col-sm-4">
                          <input type="password" class="form-control" id="inputDBPASSWORDCONFIRM" name="inputDBPASSWORDCONFIRM" placeholder="Database Password again">
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                          <button id="submit" name="submit" type="submit" class="btn btn-success">Create!</button>
                        </div>
                      </div>
                    </form>            
                </div>            
            </div>
<?php
}
?>
        </div>
        
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
        