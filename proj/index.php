<?php require_once('includes/include.php'); ?>

<?php generateHeader('Main Page'); ?>


<?php logoNav(); ?>
      
      <!-- start of forum -->
        <div class="well well-lg">
        	<div class="row">
                <?php showSearch(); // Display the search bar?>
            </div>
                        
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
                    
                    // Perform a Query on the database for all categories and return the list into an array
                    $listOfCategories = sqlQPDO("SELECT * FROM category;");
                    
                    // Display the list of categories into a table to the user
                    printCategories($listOfCategories);
                                        
                ?>
                </tbody>
            </table>
        </div>
        
<?php generateFooter(); ?>        