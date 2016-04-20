<?php 
	require_once('includes/include.php');
	generateHeader('Main Page'); 
	logoNav(); 
	//redirect if user is logged in already
	if (isset($_SESSION['auth'])){
		header("Location: index.php");
	}
?>

<div class="well well-lg">
<?php 	if (isset($_REQUEST['submit']) && (checkEmail($_POST['inputEmail'])==1)) { // email in DB
//first randomize a string
$pass=generateRandomString();
//set random password to the account
sqlSetRandomPassword($_POST['inputEmail'],$pass);
//notify the person via email
emailPassword($_POST['inputEmail'],$pass);

echo <<<PRINTSUCC
            <div class="row">
                <div class="alert alert-warning alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p class="text-center"><strong>Random password sent to email!</strong></p>
                </div>
            </div>
PRINTSUCC;
	}else if (isset($_REQUEST['submit']) && (checkEmail($_POST['inputEmail'])==0)){ //email not found in DB
echo <<<PRINTSUCC
            <div class="row">
                <div class="alert alert-warning alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p class="text-center"><strong>Error: Email not found</strong></p>
                </div>
            </div>
PRINTSUCC;
	} 
?>
	<h1 class="headerPadding center">Password Recovery</h1>
	<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="recovery" name="recovery">
		<div class="form-group">
			<label for="inputEmail" class="col-sm-4 control-label">Email</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email">
                </div>
                 <div class="col-sm-offset-4 col-sm-10">
                        <button id="submit" name="submit" type="submit" class="btn btn-success">Submit!</button>
                 </div>
		</div>
	</form>
</div>