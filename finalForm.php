<?php
include 'top.php';
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// We print out the post array so that we can see our form is working.

 

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.

$thisURL = $domain . $phpSelf;


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form

$firstName = "";
$lastName = "";
$email= "";
$gender = "male";
$frstName = false;
$lstName = false;
$Email = false;
$ages = "3-7";
$comments="";


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Intialize error flags one for each form element we validate
// in the order they appear in section 1c.
$firstNameERROR = false;
$lastNameERROR =false;
$emailERROR = false;
$genderERROR = false;
$activityERROR = false;
$totalChecked = 0;
$agesERROR = false;
$commentsERROR = false;
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables 
//
// create array to hold error messages filled (if any) in 2d displayed in 3c
$errorMsg = array();

// array used to hold form value that will be writtem to a CSV file
$dataRecord = array();

// have we mailed the information to the user?
$mailed=false;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
//SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])){

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2a Security
    //
    if (!securityCheck($thisURL)){
        $msg = '<p>sorry you cannot access this page. ';
        $msg.= 'security breach detected and reported.</p>';
        die($msg);
    }


    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2b Sanitize (clean) data
    // remove any potential JavaScript or html code from users input on 
    // form. Best to follow the same order as declared in section 1c.
$firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");                                  
$dataRecord[] = $firstName;

$lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
$dataRecord[] = $lastName;

$email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
$dataRecord[] = $email;

$gender = htmlentities($_POST["lstGender"],ENT_QUOTES,"UTF-8");
$dataRecord[] = $gender;

if (isset($_POST["chkFrstName"])) {
    $frstName = true;
    $dataRecord[] = htmlentities($_POST["chkFrstName"], ENT_QUOTES, "UTF-8");
    $totalChecked++; // count how many are checked if you need to
} else {
    $hiking = false;
    $dataRecord[] = ""; 
}
if (isset($_POST["chkLstName"])) {
    $lstName = true;
    $dataRecord[] = htmlentities($_POST["chkLstName"], ENT_QUOTES, "UTF-8");
    $totalChecked++; // count how many are checked if you need to
} else {
    $lstName = false;
    $dataRecord[] = ""; 
}
if (isset($_POST["chkEmail"])) {
    $Email = true;
    $dataRecord[] = htmlentities($_POST["chkEmail"], ENT_QUOTES, "UTF-8");
    $totalChecked++; // count how many are checked if you need to
} else {
    $Email = false;
    $dataRecord[] = ""; 
}

$ages = htmlentities($_POST["radAges"], ENT_QUOTES, "UTF-8");
$dataRecord[] = $ages;

$comments = htmlentities($_POST["txtComments"], ENT_QUOTES, "UTF-8");
$dataRecord[] = $comments;

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2c Validation
    //
    // Validation section. Check each value for possible errors, empty or
    // not what we expect. You will need an IF block for each element you will
    // check (see above section 1c and 1d). The if blocks should also be in the 
    // order that the elements appear on your form so that the error messages
    // will be in the order they appear. errorMsg will be displayed on the form 
    // see section 3b. The error flag ($emailERROR) will be used in section 3c.
if($firstName == "") {
    $errorMsg[] = "Please enter your first name";
    $firstNameERROR = true;
} elseif (!verifyAlphaNum($firstName)) {
    $errorMsg[] = 'Your first name appears to have extra characters.';
    $firstNameERROR = true;
}

if($lastName == "") {
    $errorMsg[] = "Please enter your last name";
    $lastNameERROR= true;
} elseif (!verifyAlphaNum($firstName)) {
    $errorMsg[] = 'Your last name appears to have extra characters.';
    $firstNameERROR = true;
}

if ($email == "") {
    $errorMsg[] = 'please enter your email address';
    $emailERROR= true;
} elseif (!verifyEmail($email)) {
    $errorMsg[] = 'Your email address appears to be incorrect.';
    $emailERROR= true;
}

if($gender == ""){
    $errorMsg[] = "please choose a gender";
    $genderError = true;
}

if($totalChecked < 1){
    $errorMsg[] = "Please choose at least one thing to show if your story is published";
    $activityERROR = true;
}

if($ages != "3-7" AND $ages != "8-12" AND $ages != "12-16"){
    $errorMsg[] = "Please choose a age range";
    $agesERROR = true;
}

if ($comments != "") { 
    if (!verifyAlphaNum($comments)) {
        $errorMsg[] = "Your story summary appears to have extra characters that are not allowed.";
        $commentsERROR = true;
    }
}
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2d Process Form - Passed Validation
    //
    // Process for when the form passes validation (the errorMsg array is empty)
    //
if (!$errorMsg) {
    if ($debug)
        print PHP_EOL . '<p>Form is valid</p>';


    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    // 
    // SECTION: 2e Save Data
    //
    // This block saves the data to a CSV file.
    $myFolder = 'data/';

    $myFileName = 'registration';

    $fileExt= '.csv';
  
    $filename = $myFolder . $myFileName . $fileExt;
    if ($debug) print PHP_EOL . '<p>filename is ' . $filename;
    
    // now we open the file for append
    $file = fopen($filename, 'a');

    // write the forms information
    fputcsv($file, $dataRecord);

    // close the file
    fclose($file);


    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2f Create message
    //
    // build a message to display on the screen in section 3a and to mail
    // to the person filling out the form (section 2g).
    $message = '<h2>We will email you for'
            . ' more information about your story> Here is the information you provided us.</h2>';
    
    foreach ($_POST as $htmlName => $value) {

       $message .= '<p>';
       // breaks up form names into words. for example
       // txtFirstName becomes First Nmae
       $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 3));

       foreach ($camelCase as $oneWord) {
           $message .= $oneWord . ' ';
       }
 
       $message .= ' = ' . htmlentities($value, ENT_QUOTES, "UTF-8") . '</p>';
    
    }

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2g Mail to user
    //
    // Process for mailing a message which contains the forms data
    // the message was built in section 2f.
    $to = $email; // the person who filled form
    $cc = '';
    $bcc = '<lmdesche@uvm.edu>';
 
    $from = '<lmdesche@uvm.edu>';

    // subject of mail should make sense to your form
    $subject = 'Cyber Stories: ';
    
    $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);


} // end form is valid

} //ends if form was submitted


//####################################################
//
// SECTION 3 Display Form 
//
?>

<article id='main'>
    
    <?php
    //############################
    //
    // SECTION 3a.
    //
    // If its the first time coming to the form or there are errors we are going 
    // to display the form 
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print '<h2>Thank you for providing your information.</h2>';
    
        print '<p>For your records a copy of this data has ';
    
        if (!$mailed) {
           print "not ";
        }
        print 'been sent</p>';
        print '<p>to: ' . $email . '</p>';
    
        print $message;
    } else {
        
        print '<h2>Register Today</h2>';
        print '<p class="form-heading">Your story could make the day of any child from all around the world.</p>';
    
    //#################################
    //
    // SECTION 3b Error Messages
    //
    // display any error messages before we print out the form.
    
    if($errorMsg) {
        print '<div id="errors">' . PHP_EOL;
        print '<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
        print '<ol>' . PHP_EOL;
      
        foreach ($errorMsg as $err) {
            print '<li>' . $err . '</li>' . PHP_EOL;
        }
    
        print '</ol>' . PHP_EOL;
        print '</div>' . PHP_EOL;
    }
    
    //####################################
    //
    //SECTION 3c html Form
    //
    /* Display the HTML form. note that the action is to this same page. $phpSelf
      is defined in top.php
      Note the line:
      value="<?php print $email; ?>
      this makes the form sticky by displaying either the initial default value (line ??)
      or the value they typed in (line ??)
      Note this line:
      <?php if($emailERROR) print 'class="mistake"'; ?>
      this prints out a css class so that we can highlight the background etc. to 
      make it stand out that a mistake happened here. 
     */
   ?>
    <h1>
        <p>If you would like to make your own contribution to this site please fill out the following
    form. Give us a vague idea of the story you would like to add and if it interests us we will 
    email you for the full story to be uploaded and made public for the children.</p>
    </h1>
    <form action="<?php print $phpSelf; ?>"
          id="frmRegister"
          method="post">
        
               <fieldset class="contact">
                   <legend>Contact Information</legend>
                   <p>
                      <label class="required text-field" for="txtFirstName">First Name</label>  
                      <input autofocus
                                <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                id="txtFirstName"
                                maxlength="45"
                                name="txtFirstName"
                                onfocus="this.select()"
                                placeholder="Enter your first name"
                                tabindex="100"
                                type="text"
                                value="<?php print $firstName; ?>"                    
                        >  
                      <p>
                      <label class="required text-field" for="txtLastName">Last Name</label>  
                      <input
                                <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                                id="txtLastName"
                                maxlength="45"
                                name="txtLastName"
                                onfocus="this.select()"
                                placeholder="Enter your last name"
                                tabindex="100"
                                type="text"
                                value="<?php print $lastName; ?>"                    
                        >                    
                    </p>
                    <p> 
                       <label class="required text-field" for="txtEmail">Email</label>
                       <input
                         <?php if ($emailERROR) print 'class="mistake"'; ?>  
                           id="txtEmail"
                           maxlength="45"
                           name="txtEmail"
                           onfocus="this.select()"
                           placeholder="Enter a valid email address"
                           tabindex="120"
                           type="text"
                           value="<?php print $email; ?>"
                          >
                   </p>
                   <p>
                   <label class= "listbox" <?php if ($genderERROR) print ' mistake'; ?>>Gender</label>
                   <select id = "lstGender"
                           name = "lstGender"
                           tabindex = "520" >
                        <option <?php if($gender=="Male") print " selected "; ?>
                            value ="Male">Male</option>
                        
                        <option <?php if($gender=="Female") print " selected "; ?>
                            value ="Female">Female</option>
                        
                        <option <?php if($gender=="Other") print " selected "; ?>
                            value ="Other">Other</option>
                   </select>
                   </p>
   
               </fieldset> <!-- ends contact -->
               <fieldset class="checkbox <?php if ($activityERROR) print ' mistake'; ?>">
               <legend>What would you like shown if your story is published(choose at least one):</legend>

                   <p>
                    <label class="check-field">
                        <input <?php if ($frstName) print " checked "; ?>                            
                            id="chkFrstName"
                            name="chkFrstName"
                            tabindex="420"
                            type="checkbox"
                            value="First Name"> First Name</label>
                </p>
                   <p>
                    <label class="check-field">
                        <input <?php if ($lstName) print " checked "; ?>
                            id="chkLstName"
                            name="chkLstName"
                            tabindex="430"
                            type="checkbox"
                            value="Last Name"> Last Name</label>
                </p>
                   <p>
                    <label class="check-field">
                        <input <?php if ($Email) print " checked "; ?>
                            id="chkEmail"
                            name="chkEmail"
                            tabindex="440"
                            type="checkbox"
                            value="Email"> Email</label>
                </p>
               </fieldset>
               <fieldset class="radio <?php if ($genderERROR) print ' mistake'; ?>">
    <legend>What is the age group your story will read to?</legend>
    <p>
        <label class="radio-field">
            <input type="radio" 
                   id="radAges3-7" 
                   name="radAges" 
                   value="3-7" 
                   tabindex="572"
                   <?php if ($ages == "3-7") echo ' checked="checked" '; ?>>
        3-7</label> 
    </p>
    <p>
        <label class="radio-field">
            <input type="radio" 
                   id="radAges8-12" 
                   name="radAges" 
                   value="8-12" 
                   tabindex="582"
                   <?php if ($ages == "8-12") echo ' checked="checked" '; ?>>
        8-12</label> 
    </p>
    <p>
        <label class="radio-field">
            <input type="radio" 
                   id="radAges13-17" 
                   name="radAges" 
                   value="13-17" 
                   tabindex="592"
                   <?php if ($ages == "13-17") echo ' checked="checked" '; ?>>
        13-17</label> 
    </p>
               </fieldset>
             <fieldset class="textarea">
    <p>
        <label  class="required"for="txtComments">Give a summary of your story</label>
        <textarea <?php if ($commentsERROR) print 'class="mistake"'; ?>
                  id="txtComments" 
                  name="txtComments" 
                  onfocus="this.select()" 
                  tabindex="200"><?php print $comments; ?></textarea>
    </p>
</fieldset>
   
               <fieldset class="buttons">
                   <legend></legend>
                   <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Register">
               </fieldset> <!-- ends buttons -->
    </form>

<?php
    } //end body submit
?>

</article>

<?php include 'footer.php'; ?>

</body>
</html>