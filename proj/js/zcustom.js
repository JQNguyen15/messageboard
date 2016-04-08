$(document).ready(function(){
	//In the search box, stop the propagation of the click event that selects the found item 
    $('#searchOptions').on('click', function(event){
        event.stopPropagation();
    });
    //Default action of the search forum is to search the site for the specific keyword 
	//If the searchForm is false, then the value is not submitted
    $('#searchForm').on('submit',function(event){
        if (searchForm() == false) {
            event.preventDefault(); 
        };
    });
//If the text box is empty when the user tries to submit a reply to a thread/topic
//the postReplyValidate checks to see if the box is empty, if it is then stop the post from being posted
    $('#reply').on('submit',function(event){
        if (postReplyValidate() == false) {
            event.preventDefault(); 
        };
    });
//Check to see if the topic that is to be posted is correctly completed
//If it is not complete then the submission is prevented 
    $('#postTopic').on('submit',function(event){
        if (postNewTopicValidate() == false) {
            event.preventDefault(); 
        };
    });
//For admins, they have the ability to create categories
//If the category is not complete then the submission is prevented 
    $('#createCat').on('submit',function(event){
        if (createCatValidate() == false) {
            event.preventDefault(); 
        };
    });
    
});	
//The function createCatValidate is used to check if a new category has its title and description 
//If the user forgets to complete one of the fields then an error is presented the user must fix the problem
//All categories must have a title and a description 
function createCatValidate() {
    var catTitle = $('#inputCat').val();
    var catDesc = $('#inputCatDesc').val();
    catVal = true;
    //If the category title has no characters then a popover appears stating Missing Category Title 
    if (catTitle.length == 0) {
		//The has-error class has corresponding css that causes the text to turn red and standout to the user as an error message 
        $("#inputCat").parent('div').addClass('has-error');
        $('#inputCat').popover({
    		placement: 'left',
    		trigger: 'manual',
    		content: 'Error: Missing Category Title!',    		
    	});        

        $('#inputCat').popover('show');
    	//After 3 seconds, the popover is removed from the screen 
        setTimeout(function(){
    		$('#inputCat').popover('destroy');
            $("#inputCat").parent('div').removeClass('has-error');
    		},3000);

        
        catVal = false;
    }
    //If category descripition is blank then a popover appears stating that Missing Category Description
    if (catDesc == 0) {
        $("#inputCatDesc").parent('div').addClass('has-error');
        $('#inputCatDesc').popover({
    		placement: 'left',
    		trigger: 'manual',
    		content: 'Error: Missing Category Description!',    		
    	});        

        $('#inputCatDesc').popover('show');
    	
        setTimeout(function(){
    		$('#inputCatDesc').popover('destroy');
            $("#inputCatDesc").parent('div').removeClass('has-error');
    		},3000);
        
        catVal = false;
    }
    
    return catVal;
}
//Posts made by users must have at least one character for the post to be valid
//If the user tries to post a blank post then an error is presented indicating that text must be provided 
function postReplyValidate() {
    var replyBox = tinyMCE.get('replyText').getContent();
    if (replyBox.length == 0) {
        $('#replyTextBox').popover({
    		placement: 'right',
    		trigger: 'manual',
    		content: 'Error: Must Provide Text!',    		
    	});        

        $('#replyTextBox').popover('show');
    	
        setTimeout(function(){
    		$('#replyTextBox').popover('destroy');
    		},3000);
        return false;
    } else {
        return true;
    }
}
//When creating a new topic, the body of the first post must have at least one character in it
//The title must also have at least one character
function postNewTopicValidate() {
    var topicTitleText = $('#topicTitle').val();
    var topicBody = tinyMCE.get('topicText').getContent();
    var topicVal = true;
    
	//When the body length is 0 then a popover shows up stating that Must Provide Text 
    if (topicBody.length == 0) {
        $('#topicTextBox').popover({
    		placement: 'bottom',
    		trigger: 'manual',
    		content: 'Error: Must Provide Text!',    		
    	});        

        $('#topicTextBox').popover('show');
    	//Removes the popover after 3 seconds 
        setTimeout(function(){
    		$('#topicTextBox').popover('destroy');
    		},3000);
        //Set topicVal to false so that it is not posted 
        topicVal = false;
    }
	
    //When the body length is 0 then a popover shows up stating that Must Provide Title
    if (topicTitleText.length == 0) {
        $("#topicTitle").parent('div').addClass('has-error');
        $('#topicTitle').popover({
    		placement: 'top',
    		trigger: 'manual',
    		content: 'Error: Must Provide Title!',    		
    	});        

        $('#topicTitle').popover('show');
    	
        setTimeout(function(){
    		$('#topicTitle').popover('destroy');
            $("#topicTitle").parent('div').removeClass('has-error');
    		},3000);
        
        topicVal = false;
    }
    return topicVal;
}
//For the searching ability of the website, the user must provide a 
//keyword or an author to search for
//If neither are provided then an error message pop ups telling the user to enter the thing they're searching for 
function searchForm() {
    if ((searchBox() == true) || (searchBox() == false && authorBox() == true)) {
        $("#searchForm").parent('div').removeClass('has-error');
        return true;
    } else {
        $("#searchForm").parent('div').addClass('has-error');
        $('#searchWords').popover({
    		placement: 'left',
    		trigger: 'manual',
    		content: 'Missing Search Term or Author',
    		
    	});
    	
        $('#searchWords').popover('show');
    	
        setTimeout(function(){
    		$('#searchWords').popover('destroy');
            $("#searchForm").parent('div').removeClass('has-error');
    		},3000);
        
        return false;
    }
}
//The searchBox function checks to see the length of the word in the keyword textbox 
function searchBox(){
    var searchWords = $('#searchWords').val();
    if (searchWords.length == 0) {
        return false;
    } else {
        return true;
    }
}
//The authorBox function checks to see the length of the word in the author textbox 
function authorBox(){
    var searchAuthor = $('#searchAuthor').val();
    if (searchAuthor.length == 0) {
        return false;
    } else {
        return true;
    }
}