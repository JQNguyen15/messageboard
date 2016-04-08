<?php require_once('includes/include.php'); ?>

<?php generateHeader('Search Result Page'); ?>


<?php logoNav(); ?>
      
      <!-- start of forum -->
        <div class="well well-lg">
            <?php if(!isset($_POST['searchSubmit'])) die('No search values provided!') //Check to make sure the user entered search words ?>
            <div class="row padme">
                <?php displayBackButton(); ?>
            </div>            
            <h2>Search Results:</h2>

            <?php
                //trim the search words so there is no white space to the left or right
                $searchWords = trim($_REQUEST['searchWords']);
                
                //If the user has selected either to search by Category or all and there exists search words
                if (($_POST['searchOptions'] == 1 || $_POST['searchOptions'] == 0) && $searchWords != "") {
            ?>                        
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td><h4>Categories</h4></td>
                        <td><h4>Topics</h4></td>
                        <td><h4>Posts</h4></td>
                        <td><h4>Last Posted</h4></td>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    
                    //Retrive the list of of categories that have the search words within their title or description 
                    $listOfCategories = sqlQPDO("SELECT * FROM category WHERE catName LIKE \"%{$searchWords}%\" OR catDescription LIKE \"%{$searchWords}%\"; ");
                    
                    //If there are no results let the user know
                    if ($listOfCategories === 0 ) {
echo <<<PRINTERR
                        <tr>
                            <td class="text-center" colspan="4"><p>No Search Results in Categories for '{$searchWords}'</p></td>
                        </tr>
PRINTERR;
                    } else {
                        //Print all categories that match the selected criteria
                        printCategories($listOfCategories);
                };
                    
                ?>
                </tbody>
            </table>

            <?php
                }
                                                
                //If the user has selected either to search by Topic or all and there exists search words
                if (($_POST['searchOptions'] == 2 || $_POST['searchOptions'] == 0) && $searchWords != "") {
            ?>                        

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td><h4>Topics</h4></td>
                        <td><h4>Replies</h4></td>
                        <td><h4>Last Posted</h4></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                    //Retrive the list of topics that have the search words within their topic name
                    $listOfTopics = sqlQPDO("SELECT * FROM (SELECT topics.*, replies.replyID, replies.replyContent, replies.replyDate FROM topics INNER JOIN replies ON topics.topicID=replies.topicID WHERE topicName LIKE \"%{$searchWords}%\" ORDER BY replyDate DESC) as c GROUP BY topicID ORDER BY replyDate DESC;");
                    
                    //If there are no results let the user know
                    if ($listOfTopics === 0 ) {
echo <<<PRINTERR
                        <tr>
                            <td class="text-center" colspan="3"><p>No Search Results in Topics for '{$searchWords}'</p></td>
                        </tr>
PRINTERR;
                    } else {
                        //Print all topics that match the selected criteria
                        printTopics($listOfTopics);
                    }
                ?>
                </tbody>
            </table>


            <?php
                }
                
                //If the user has selected either to search by Replies or all
                if ($_POST['searchOptions'] == 3 || $_POST['searchOptions'] == 0) {
            ?>                        
            
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td><h4>Replies</h4></td>
                        <td><h4>Posted by/on</h4></td>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    
                    //Trim the SearchWords and the Author fields
                    $searchWords = trim($_REQUEST['searchWords']);
                    $author = trim($_REQUEST['searchAuthor']);
                    
                    //If the user hasn't specified an author to search by, then just search for the reply
                    if ($author == "") {
                        //Retrive the list of replies that have the search words within their reply body
                        $listofReplies = sqlQPDO("SELECT * FROM topics, replies, users WHERE topics.topicID = replies.topicID AND replies.userID = users.userID AND replies.replyContent LIKE \"%{$searchWords}%\" ORDER BY replyDate DESC;");
                        $response = "$searchWords";
                    } else { //Otherwise seach by reply and author
                        //Retrive the list of replies that have the search words within their reply body and match the author that was requested
                        $listofReplies = sqlQPDO("SELECT * FROM topics, replies, users WHERE topics.topicID = replies.topicID AND replies.userID = users.userID AND replies.replyContent LIKE \"%{$searchWords}%\" AND users.username LIKE \"%{$author}%\" ORDER BY replyDate DESC;");
                        $response = "$searchWords by $author";
                    }
                    
                    //If there are no results let the user know
                    if ($listofReplies === 0 ) {
echo <<<PRINTERR
                        <tr>
                            <td class="text-center" colspan="2"><p>No Search Results in Replies for '$response'</p></td>
                        </tr>
PRINTERR;
                    } else {
                        //Print all replies that match the selected criteria
                        printReplies($listofReplies, true);
                    };
?>

    </tbody>
</table>

<?php
                };
?>
            
</div>
        
<?php generateFooter(); ?>        