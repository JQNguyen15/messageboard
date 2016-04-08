<?php require_once('includes/include.php'); ?>

<?php generateHeader('Registration Page'); ?>


<?php logoNav(); ?>
      
      <!-- start of forum -->
        <div class="well well-lg">
<?php
//If the uesr clicked on the submit button
if (isset($_REQUEST['submit'])) {
    //Add the new user to the database and notify the user that it was successfully added
    $userID = sqlNewUserPDO($_POST['inputEmail'], $_POST['inputUsername'], $_POST['inputPassword']);   
    echo "<br/>";
    printSuccessBox('New User Created!','success');
    echo "<br/><br/><br/><br/>";
} else {
?>
            <h3>New User Registration</h3>

            <div class="row">
                <div class="col-sm-8">            
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="register" name="register">
                      <div class="form-group">
                        <label for="inputEmail" class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-6">
                          <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email" maxlength="30">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputUsername" class="col-sm-4 control-label">Username</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="inputUsername" name="inputUsername" placeholder="Username" maxlength="20">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputPassword" class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-4">
                          <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                          <button id="submit" name="submit" type="submit" class="btn btn-success">Register!</button>
                        </div>
                      </div>
                    </form>            
                </div>
                <div class="col-sm-4">
                    <div><p id="emailStatus"></p></div>
                    <div><p id="usernameStatus"></p></div>
                    <div><p id="pwordStatus"></p></div>
                </div>
            </div>
        </div>
<?php
}
?>        
<?php generateFooter(); ?>        