<?php require_once('includes/include.php'); ?>

<?php generateHeader('Categories Page'); ?>


<?php logoNav(); ?>
      <!-- start of forum -->
        <div class="well well-lg">
            <?php if (!isset($_REQUEST['cat'])) die("Fatal Error: Category not Provided!") //If the user has not provided a category id then kill the php script and let the user know ?>
            
            <div class="row">
                <?php showSearch(); //Display the search menu to the user ?>
            </div>
            
            <div class="row padme">
<?php 

//Show the back button to the user
displayBackButton();

//If the user is authenticated then display the Post a new Topic button
if (isset($_SESSION['auth'])) {
echo <<<PRINTTOPIC
                <div class="col-md-2 pull-right">
                    <a href="postTopic.php?cat={$_REQUEST['cat']}"><button type="button" class="btn btn-newtopic">Post a new Topic!</button></a>
                </div>
PRINTTOPIC;
}
?>
            </div>
            <br />
                                    
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <?php 
                        //Retrieve the selected category name from the database
                        $categoryName = sqlQPDO("SELECT catName FROM category WHERE catID = {$_GET['cat']};");
                        ?>
                        <td><h4><?php echo $categoryName[0]['catName'] //Display the category name to the user ?></h4></td>
                        <td><h4>Replies</h4></td>
                        <td><h4>Last Posted</h4></td>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    //Retrieve a list of all topics within the given category sorted by date (descending)
                    $listOfTopics = sqlQPDO("SELECT * FROM (SELECT topics.*, replies.replyID, replies.replyContent, replies.replyDate FROM topics INNER JOIN replies ON topics.topicID=replies.topicID WHERE catID = {$_GET['cat']} ORDER BY replyDate DESC) as c GROUP BY topicID ORDER BY replyDate DESC;");
                    
                    //If the list is empty, notify the user
                    if ($listOfTopics === 0 ) {
echo <<<PRINTERR
                        <tr>
                            <td class="text-center info" colspan="3"><p>No Topics for this category</p></td>
                        </tr>
PRINTERR;
                    } else { //Otherwise,
                        //Display the list of all topics to the user
                        printTopics($listOfTopics);
                        
                        echo "</tbody>";
                        
                        //If the user is authenticated allow, show the post a new topic button
                        if (isset($_SESSION['auth'])) {
echo <<<PRINTTOPIC
                <tfoot>
                    <tr>
                      <td><a href="postTopic.php?cat={$_REQUEST['cat']}"><button type="button" class="btn btn-newtopic btn-xs">Post a new Topic!</button></a></td>
                    </tr>
                </tfoot> 
PRINTTOPIC;
                        }
                }
                ?>
            </table>
        </div>
        
<?php generateFooter(); ?>        