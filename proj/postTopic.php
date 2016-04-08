<?php require_once('includes/include.php'); ?>
<?php
// If the user has submitted a request to post a new topic
if (isset($_REQUEST['submit'])) {
    
    //Insert the new topic into the datbase and then redirect the user to the topic that was just created
    $IDs = sqlNewTopicPDO($_REQUEST['topicTitle'], $_REQUEST['cat'], $_SESSION['auth_info']['userID'], $_REQUEST['topicText']);    
    header("Location: viewTopic.php?top={$IDs['topicID']}#r{$IDs['replyID']}");
} else {
?>

<?php generateHeader('Post a New Topic'); ?>


<?php logoNav(); ?>
      <script type="text/javascript">
        /*
            Initialize tinymce for the textarea on the page
        */
        tinymce.init({
            selector: 'textarea',
            menubar: false
        });
      </script>
      <!-- start of forum -->
        <div class="well well-lg">
            <h3>Post a new Topic</h3>            
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']."?cat=".urlencode($_GET['cat'])?>" id="postTopic">
                <div class="form-group">
                    <label for="topicTitle">Topic Title:</label>
                    <input type="text" class="form-control" id="topicTitle" name="topicTitle" placeholder="Topic Title" maxlength="80">
                </div>
                <div class="form-group" id="topicTextBox">
                    <label for="topicText">Topic Body:</label>
                    <textarea id="topicText" name="topicText" class="form-control" rows="8"></textarea>
                </div>
                <button id="submit" name="submit" type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
<?php
}
?>
        
<?php generateFooter(); ?>        