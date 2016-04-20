<?php require_once('includes/include.php'); ?>

<?php generateHeader('Admin Page'); ?>


<?php logoNav(); ?>
      
      <!-- start of forum -->
        <div class="well well-lg">
            <?php 
                // Check to see if the user is authenticated and has admin privileges to be on this page. If they do not then stop the script and display a warning to the user
                if (!isset($_SESSION['auth'])) die("UNAUTHORIZED ACCCESS: YOU ARE NOT AN ADMIN!");
                if ($_SESSION['auth_info']['groupID'] != 2) die("UNAUTHORIZED ACCCESS: YOU ARE NOT AN ADMIN!"); 
            ?>
            <?php
                //Check to see if the admin requested to delete a category 
                if(isset($_POST['delCat'])){
                    //Delete each category from the database
                    foreach($_POST['catArr'] as $value){
                      sqlDelCategory((int)$value);
                    }
                    //Print a success response to the admin
                    printSuccessBox('Categories Deleted!','warning');
                
                } else if (isset($_POST['delTop'])) {    
                    //Delete each topic from the database
                    foreach($_POST['topArr'] as $value){
                      sqlDelTopic((int)$value);
                    }
                    //Print a success response to the admin
                    printSuccessBox('Topics Deleted!','warning');
                    
                } else if (isset($_POST['createCat'])) {
                    //Create the category in the database
                    sqlNewCategoryPDO($_POST['inputCat'], $_POST['inputCatDesc']);
                    //Print a success response to the admin
                    printSuccessBox('Category Created!','success');                    
                }
            ?>
            
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h3>Create Category</h3>
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="createCat" name="createCat">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputCat">Category Name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="inputCat" name="inputCat" placeholder="Category Name" maxlength="50">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputCatDesc">Category Description</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputCatDesc" name="inputCatDesc" placeholder="Category Description" maxlength="100">
                            </div>
                        </div>      
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-10">      
                                <button type="submit" id="createCat" name="createCat" class="btn btn-success">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h3>Delete Category</h3>
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="delCat" name="delCat">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td class="text-right"><h5>Select</h5></td>
                                <td><h5>Category</h5></td>
                            </tr>
                        </thead>
                        <tbody>                                                
                        <?php 
                            //Retrieve all categories from the database
                            $listOfCategories = sqlQPDO("SELECT * FROM category;");
                            
                            //List all categories to the user
                            foreach ($listOfCategories as $category) {
echo <<<PRINTTBL
                        <tr>
                            <td class="col-sm-1">
                                <div class="checkbox pull-right">
                                  <label>
                                    <input type="checkbox" name="catArr[]" value="{$category['catID']}" aria-label="...">
                                  </label>
                                </div>                                
                            </td>
                            <td class="col-sm-11">
                                <h5>{$category['catName']}</h5>
                            </td>
                        </tr>
PRINTTBL;
                            };
                            
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                  <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-10">
                                      <button id="delCat" name="delCat" type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                  </div>
                                </form>                
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h3>Delete Topic</h3>
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="delTop" name="delTop">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td class="text-right"><h5>Select</h5></td>
                                <td><h5>Topic</h5></td>
                            </tr>
                        </thead>
                        <tbody>                                                
                        <?php 
                            //Retrieve all topics from the database
                            $listOfTopics = sqlQPDO("SELECT * FROM topics;");
                            
                            //List all topics to the user
                            foreach ($listOfTopics as $topic) {
echo <<<PRINTTBL
                        <tr>
                            <td class="col-sm-1">
                                <div class="checkbox pull-right">
                                  <label>
                                    <input type="checkbox" name="topArr[]" value="{$topic['topicID']}" aria-label="...">
                                  </label>
                                </div>                                
                            </td>
                            <td class="col-sm-11">
                                <h5>{$topic['topicName']}</h5>
                            </td>
                        </tr>
PRINTTBL;
                            };
                            
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                  <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-10">
                                      <button id="delTop" name="delTop" type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                  </div>
                                </form>                
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

  <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h3>Ban User</h3>
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="banUser" name="banUser">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td class="text-right"><h5>Select</h5></td>
                                <td><h5>User</h5></td>
                            </tr>
                        </thead>
                        <tbody>                                                
                        <?php 
                            //Retrieve all user from the database
                            $listOfUsers = sqlQPDO("SELECT * FROM users;");
                            
                            //List all users
                            foreach ($listOfUsers as $user) {
echo <<<PRINTTBL
                        <tr>
                            <td class="col-sm-1">
                                <div class="checkbox pull-right">
                                  <label>
                                    <input type="checkbox" name="userArr[]" value="{$user['userID']}" aria-label="...">
                                  </label>
                                </div>                                
                            </td>
                            <td class="col-sm-11">
                                <h5>{$user['username']}</h5>
                            </td>
                        </tr>
PRINTTBL;
                            };
                            
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                  <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-10">
                                      <button id="banUser" name="banUser" type="submit" class="btn btn-danger">Ban</button>
                                    </div>
                                  </div>
                                </form>                
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>              
        </div>


        </div>
        
<?php generateFooter(); ?>        