$(document).ready(function(){
		//When the user submits their registration information
		//the following statement checks to see if the information is valid
		//If it is not, then the submission is prevented 
        $('#register').on('submit',function(event){
           if (validateForm() == false) {
                event.preventDefault(); 
           };
        });

        $('#update').on('submit',function(event){
            emailIsValid = true;
           if (validateUpdateForm() == false) {
                event.preventDefault(); 
           };
        });
		//by default, username, password and email are all false 
        usernameIsValid = false;
        passwordIsValid = false;
        emailIsValid = false;
     
    	//As the user is trying to sign up for the website and changes the username, 
		//the following code calls the validateEmail function to check the email.
		//If the email is valid according to the function then a class is added to show that it is okay
    	$("#inputEmail").keyup(function(){
    		var email = $("#inputEmail").val();
    		if (validateEmail(email)) {
    			$("#emailStatus").html('');
    			$("#inputEmail").parent('div').parent('div').removeClass('has-error');
                $("#inputEmail").parent('div').parent('div').addClass('has-success');
                emailIsValid = true;		  
    		} else {
    			//#emailStatus id is under the sign up form, indciates whether 
    			//the name is valid or not. The HTML will change accordingly 
    			$("#emailStatus").html('<strong>Error:</strong> You must use a valid email address');
                $("#inputEmail").parent('div').parent('div').removeClass('has-success');
                $("#inputEmail").parent('div').parent('div').addClass('has-error');
                emailIsValid = false;		  
    		}
    	   
        });
        //Looking at the username for registration
		//Each time the user keyups the username, if the length is over 3 it is sent to the 
		//checkAvailability function to see if the username is available 
        $("#inputUsername").keyup(function(){
    		var userName = $("#inputUsername").val();
    		
    		if(userName.length >=3){
    			//continue 
    			$("#usernameStatus").html('');
    			$("#inputUsername").parent('div').parent('div').removeClass('has-error');
                checkAvailability();
    		}
    		else{
    			//#usernameStatus is somewhere under the sign up form, indciates whether 
    			//the name is valid or not
                $("#inputUsername").parent('div').parent('div').removeClass('has-success');
                $("#usernameStatus").css('background-color', '#f2dede');
                $("#usernameStatus").css('color', '#b94a48');
    			$("#usernameStatus").html('<strong>Error:</strong> The username requires at least 3 characters');
                $("#inputUsername").parent('div').parent('div').addClass('has-error');
    		}
    	});
    	//As the user is trying to set their password during sign up, 
		//the value is sent to the passStrength function to check its complexity 
    	$("#inputPassword").keyup(function(){
    		passStrength($('#inputPassword').val())
    	});
    	
});	
//Email, username and password must all be set to true for the form to be valid
function validateForm() {
    if (emailIsValid == true && usernameIsValid == true && passwordIsValid == true) {
        return true;
    } else {
        return false;
    }
}
//Used for a check to change password 
function validateUpdateForm() {
    if (emailIsValid == true && passwordIsValid == true) {
        return true;
    } else {
        return false;
    }
}

function reload(){
	document.location.reload(true);
}
//Function is used to check if the desired username is available to be used
//if it is available then usernameIsValid returns true and allows the user to continue
//otherwise it is not available and the user must pick a different name 
function checkAvailability(){  
  
        //get the username 
        var userName = $('#inputUsername').val();  
  
        //use ajax to run the check  
        $.post("check_username.php", { userName: userName },  
            function(result){  
                //if the result returned is 1  
                if(result == 0){  
                    //show that the username is available  
                    $("#usernameStatus").css('background-color', '#dff0d8');
                    $("#usernameStatus").css('color', '#5cb85c');
                    $("#inputUsername").parent('div').parent('div').removeClass('has-error');
                    $("#inputUsername").parent('div').parent('div').addClass('has-success');
                    $('#usernameStatus').html('Username \'' + userName + '\' is Available');
                    usernameIsValid = true;
                      
                }else{  
                    //show that the username is NOT available  
                    $("#usernameStatus").css('background-color', '#f2dede');
                    $("#usernameStatus").css('color', '#b94a48');
                    $("#inputUsername").parent('div').parent('div').removeClass('has-success');
                    $("#inputUsername").parent('div').parent('div').addClass('has-error');
                    $('#usernameStatus').html('Username \'' + userName + '\' is not Available');
                    usernameIsValid = false;
                }  
        });  
  
}  
//The following function is used to check if the email is of a valid type
//A regular expression is used to see if the format of name@domain.com is used 
//Returns 
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
//A set of regular expressions are used to check the password strength
//As more conditions are met the strength is increased and a cooresponding "weak", "good", or "strong" value is assigned 
function passStrength(password){
	var strength = 0;
	
		if(password.length < 7){
			$("#inputPassword").parent('div').parent('div').removeClass('has-success');
            $("#inputPassword").parent('div').parent('div').addClass('has-error');
            $("#pwordStatus").css('background-color', '#f2dede');
            $("#pwordStatus").css('color', '#b94a48');
            $('#pwordStatus').html('<strong>Error:</strong> Password needs to be at least 7 characters');
            passwordIsValid = false;  
			return;
		}
		//At least  than 10 characters
		if(password.length >=10 ){
			strength +=1;
		}
		// If password contains both lower and uppercase characters, increase strength value.
		if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
			strength += 1 
			}
		// If it has numbers and characters, increase strength value.
		if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)){
			strength += 1
		}
		// If it has one special character, increase strength value.
		if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)){ strength += 1
		}
		// If it has two special characters, increase strength value.
		if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)){
			strength += 1
		}
	//Strength less than 2 = weak password 
	if(strength <2 ){
        $("#inputPassword").parent('div').parent('div').removeClass('has-error');
        $("#inputPassword").parent('div').parent('div').addClass('has-success');
        $("#pwordStatus").css('background-color', '#f2dede');
        $("#pwordStatus").css('color', '#b94a48');
		$('#pwordStatus').html('<strong>Strength:</strong> Weak');
        passwordIsValid = true;
	}
	//Strength of 2 is a good password 
	else if (strength == 2 ){
	    $("#inputPassword").parent('div').parent('div').removeClass('has-error');
        $("#inputPassword").parent('div').parent('div').addClass('has-success');
        $("#pwordStatus").css('background-color', '#fcf8e3');
        $("#pwordStatus").css('color', '#f0ad4e');
		$('#pwordStatus').html('<strong>Strength:</strong> Good');
        passwordIsValid = true;
	}
	//Strenght greater than 2 = strong password 
	else{
        $("#inputPassword").parent('div').parent('div').removeClass('has-error');
        $("#inputPassword").parent('div').parent('div').addClass('has-success');
        $("#pwordStatus").css('background-color', '#dff0d8');
        $("#pwordStatus").css('color', '#5cb85c');
        $('#pwordStatus').html('<strong>Strength:</strong> Strong');
        passwordIsValid = true;
	}
		
}
