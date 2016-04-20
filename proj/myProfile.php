<?php require_once('includes/include.php'); ?>

<?php generateHeader('My Profile Page'); ?>


<?php logoNav(); ?>
      
      <!-- start of forum -->
        <div class="well well-lg">
            <?php 
                // Check to see if the user has been authenticated. If they have not then kill the php script and display an error!
                if (!isset($_SESSION['auth'])) die("UNAUTHORIZED ACCCESS: YOU ARE NOT SUPPOSED TO BE HERE!"); 
            ?>
            <?php 
                // if the user has clicked the update button
                if(isset($_POST['submit'])){
                    //Update the users information and display a success message
                    $userID = sqlUpdateUserPDO($_POST['inputEmail'], $_POST['inputPassword']);   
                    printSuccessBox('Profile Updated!','success');
                }
            ?>
            <h3>Update Your Profile</h3>

            <div class="row">
                <div class="col-sm-8">            
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="update" name="update">
                      <div class="form-group">
                        <label for="inputEmail" class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-6">
                          <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email" maxlength="30" value="<?php echo $_SESSION['auth_info']['useremail'] ?>">
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
                          <button id="submit" name="submit" type="submit" class="btn btn-success">Update!</button>
                        </div>
                      </div>
                    </form>            
                </div>
                <div class="col-sm-4">
                    <div><p id="emailStatus"></p></div>
                    <div><p id="pwordStatus"></p></div>
                </div>
            </div>
            
        </div>
        
<?php generateFooter(); ?>        