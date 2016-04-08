<?php require_once('includes/include.php'); ?>

<?php generateHeader('Contact Page'); ?>

<?php logoNav(); ?>

<div class="well well-lg">
	<h1 class="headerPadding center">About Us</h1>

	<div class="row">
		<div class="col-md-4 vcenter">
			<img src="img/james.jpg" class="img-circle" alt="James Nguyen" width="304" height="336">
		</div>
		

		<div class="col-md-5 vcenter ">
			Hi, I'm James Nguyen. Born in Ottawa, grew up in Windsor Ontario. I'm a former member of the Canadian Armed Forces. My military occupation was a <a href="http://www.forces.ca/en/job/maritimesurfaceandsubsurfaceofficer-65" target="_blank">MARS Officer</a> in the Navy. Currently working towards my undergrad in Computer Science at the University of Windsor. Expected to graduate in 2016 and currently looking for software engineering jobs. I love working with new technology and hope to do more work in web development. 
			<br>
			You can find out more about some of my work in the following links
			<br>
			<a href="https://github.com/JQNguyen15"  target="_blank">My github</a>
			<br>
			<a href="https://jqnguyen15.github.io/"  target="_blank">Porfolio page</a>
			<br>
			Feel free to connect with me on 
			<a href="https://ca.linkedin.com/in/jqnguyen15"  target="_blank">LinkedIn</a>
		</div>
	</div>

	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-5 vcenter ">
			Hello, I'm Nicholas Sylvestre. I was born in Windsor Ontario back in 1982. My first PC was an Intel based 386SX. I spent 8-10 hours per day during the summer on it at the age of 10. My first programming language was Basic, however I was formally taught Turing in high school. I moved on to more advanced languages as I gained further experience. I held a lead programming position from 2000-2004 at a local coding shop until it closed. I decided to go back to school in 2009 and spent 3 years at St. Clair College before coming to the university to recieve my degree. I am now teaching at St. Clair College part time until I graduate where I hope to apply for a full time teaching position.  
		</div>
		<div class="col-md-4 vcenter">
			<img src="img/nick.jpg" class="img-circle" alt="Nicholas Sylvestre" width="304" height="336">
		</div>
	</div>

	<div class="row">
		<div class="col-md-4 vcenter ">
			<img src="img/andrew.png" class="img-circle" alt="Andrew Azenabor" width="304" height="336">
		</div>
		<div class="col-md-5 vcenter">			
From Windsor, Ontario. My name is Andrew Azenabor. I have an Applied IT Certificate from University of
Windsor, two years experience related to Business Analysis and currently 
finishing my degree in Computer Science at University of Windsor. As 
for career, I am interested in Business/System Analyst positions and i 
expect to graduate in Summer 2017.
		</div>
	</div>

	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-5 vcenter">
			Hello! My name is Carson Siu and I was born in London Ontario. I've worked with computers my entire life but originally started my university career in Biotechnology and Biochemistry. After a few years of that I moved over to St Clair College where I took Computer Networking. I currently work as a part time instructor for the college while finishing my degree in Computer Science at the university. Hopefully when I graduate I can become a full-time Twitch streamer and play video games for a living, but bar that, a full time teaching position would be fine too.
		</div>
		<div class="col-md-4 vcenter">
			<img src="img/carson.png" class="img-circle" alt="Carson Siu" width="304" height="336">
		</div>
	</div>

	<h1 class="headerPadding center">Contact Us</h1>

    <?php 
    // Determine if the user has pressed the submit buttton on the form
    $action = ( array_key_exists( 'action', $_REQUEST) ? $_REQUEST['action'] : "" );
    
    //If the user has not pressed the submit form then display the contact form
    if ($action=="")    /* display the contact form */ 
        { 
        ?> 
            <form  class="center" action="" method="POST" enctype="multipart/form-data"> 
            <input type="hidden" name="action" value="submit"> 
            Your name:<br> 
            <input name="name" type="text" value="" size="30"/><br> 
            Your email:<br> 
            <input name="email" type="text" value="" size="30"/><br> 
            Your message:<br> 
            <textarea name="message" rows="7" cols="30"></textarea><br> 
            <input type="submit" value="Send email"/> 
            </form> 
            <?php 
        }  
    else                /* send the submitted data */ 
        { 
            $name=$_REQUEST['name']; 
            $email=$_REQUEST['email']; 
            $message=$_REQUEST['message']; 
            
            //If the user did not provide any input to the email form warning the user
            if (($name=="")||($email=="")||($message=="")) 
            { 
                echo "All fields are required, please fill <a href=\"\">the form</a> again."; 
            } 
            else
            {         
                //Format the email and use the php mail function to email the user 
                $from="From: $name<$email>\r\nReturn-path: $email"; 
                $subject="Message sent using your contact form"; 
                mail("nguyen1v@uwindsor.ca", $subject, $message, $from); 
                echo "Email sent!"; 
            } 
        }   
    ?> 

</div>
        
<?php generateFooter(); ?>        