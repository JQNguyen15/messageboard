<?php require_once('includes/include.php'); ?>
<?php

//If the user wishes to post a new reply to a topic,
if (isset($_REQUEST['submit'])) {
    //Call the function to post a new reply to the database and refresh the page to show the recently posted reply
    $IDs = sqlNewReplyPDO($_SESSION['auth_info']['userID'], $_REQUEST['replyText'], $_REQUEST['top']);    
    header("Location: viewTopic.php?top={$_REQUEST['top']}#r$IDs");
} else {
    //If the user has requested to delete the reply and the user is authenticated,
    if (isset($_REQUEST['delete']) && isset($_SESSION['auth'])) {
        // Check to see if the user has admin privleges to delete replies
        if ($_SESSION['auth_info']['groupID'] == 2){
            //Call the function to delete the selected reply from the database
            sqlDelReply((int)$_GET['delete']);
        }
    }
?>

<?php generateHeader('View Topic'); ?>


<?php logoNav(); ?>
      <script type="text/javascript">
        /*
            Initialize the tinymce text editor
        */
        tinymce.init({
            selector: 'textarea',
            menubar: false
        });
      </script>

      <!-- start of forum -->
        <div class="well well-lg">
            <?php if (!isset($_REQUEST['top'])) die("Fatal Error: Topic not Provided!") //If the user has not provided a topic id, kill the php script and warn the user ?>
            
            <div class="row">
                <?php showSearch(); //Show the search bar ?>
            </div>
            
            <div class="row padme">
<?php

//Show the back button to the user
displayBackButton();

//If the user is authenticated, display the Post a Reply button to the user
if (isset($_SESSION['auth'])) {
echo <<<PRINTREPLY
                <div class="col-md-2 pull-right">
                    <a href="#reply"><button type="button" class="btn btn-newtopic"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Post a Reply!</button></a>
                </div>            
PRINTREPLY;
    }
?>
            </div>
            <br />
            <table class="table table-striped table-bordered">
            <tbody>
    <?php 
    // Seach the databse for the first reply for the first topic (created by the topic creator) and display it to the user
    $mainTopic = sqlQPDO("SELECT * FROM topics NATURAL JOIN replies NATURAL JOIN users WHERE topicID = {$_REQUEST['top']} LIMIT 1;");
    
    //Format the reply date and join date to human readable format
    $mainTopic[0]['replyDate'] = date( 'j M Y g:ia', strtotime($mainTopic[0]['replyDate']) );
    $mainTopic[0]['joinDate'] = date( 'j M Y g:ia', strtotime($mainTopic[0]['joinDate']) );

echo <<<PRINTTOP
                    <tr id="r{$mainTopic[0]['replyID']}">
                        <td class="col-md-8 mainTopic">
                            <h4>{$mainTopic[0]['topicName']}</h4>
                            <p>{$mainTopic[0]['replyContent']}</p>
                        </td>
                        <td class="col-md-3 mainTopic">
                            <p>Posted By: {$mainTopic[0]['username']}<br/>
                            Posted: {$mainTopic[0]['replyDate']}<br/>
                            Joined: {$mainTopic[0]['joinDate']}<br/>
                            </p>                            
                        </td>
                    </tr>
PRINTTOP;
    
    //Search the database for the rest of the replies for the topic and order them by reply date 
    $listofReplies = sqlQPDO("SELECT * FROM topics, replies, users WHERE topics.topicID = replies.topicID AND replies.userID = users.userID AND topics.topicID = {$_REQUEST['top']} ORDER BY replies.replyDate;");
    
    //Remove the first reply as its already been displayed then print the rest to the user
    array_shift($listofReplies);
    printReplies($listofReplies, false);
                    
    echo "</tbody>";
    
    //If the ueser is authenticated display the reply form to the uesr using tiny mce
    if (isset($_SESSION['auth'])) {
echo <<<PRINTREPLY
                <tfoot>
                <tr><td></td></tr>
                    <tr>
                      <td class="col-md-6" >
                        <form id="reply" method="post" action="{$_SERVER['PHP_SELF']}?top={$_REQUEST['top']}">
                            <div class="form-group" id="replyTextBox">
                                <label for="replyText">Reply:</label>
                                <textarea id="replyText" name="replyText" class="form-control" rows="8"></textarea>
                            </div>
                            <button id="submit" name="submit" type="submit" class="btn btn-sm">Submit</button>
                        </form>                      
                      </td>
                    </tr>
                </tfoot> 
PRINTREPLY;
    }
?>
            </table>
        </div>
<?php
}
?>        
<?php generateFooter(); ?>        