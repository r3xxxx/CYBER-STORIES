<?php
// parse the url into htmlentities to remove any suspicious vales that someone
// may try to pass in. htmlentities helps avoid security issues.

$chapters = array("horse","goat","chicken","cows","champ");
  

        
        
$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

// break the url up into an array, then pull out just the filename
$path_parts = pathinfo($phpSelf);
?>	
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- you can add php code here (similar to nav.php) to print a different title on each page -->
        <title>CYBER STORIES</title>

        <meta charset="utf-8">
        <meta name="author" content="Rex Godbout and Lowell Deschenes">
        <meta name="description" content=" Some cyber stories for some sick kids! ">


        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="../css/custom.css" type="text/css" media="screen">


<?php


if(in_array($path_parts['filename'], $chapters)){
    print '<body id="story">';
}else{
    print '<body id="' . $path_parts['filename'] . '">';
    
    
}

    

include 'header.php';
include 'nav.php';



//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
//
// PATH SETUP
//
$debug = false;

// This if statement allows us in the classroom to see what our variables are
// This is NEVER done on a live site
if (isset($_GET["debug"])) {
    $debug = true;
}


$domain = '//';

$server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');            
            
$domain .= $server;            
            
            
            if ($debug) {
                
                print '<p>php Self: ' . $phpSelf;
                print '<p>Path Parts<pre>';
                print_r($path_parts);
                print '<pre></p>';
            }
            
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
//
// iclude all libraries.
//
// Common mistake: not have the lib folder with these files.
// Google the difference between require and include
//
            print PHP_EOL . '<!-- include libraries -->' . PHP_EOL;
            
            require_once('lib/security.php');
            
            // notice this if statement only inludes the functions if it is
            // form page. A common mistake is to make a fom and call the page 
            // join.php which means you need to change it below
            if ($path_parts['filename']== "finalForm") {
                print PHP_EOL . '<!-- include form libraries-->' . PHP_EOL;
                include 'lib/validation-functions.php';
                include 'lib/mail-message.php';        
            }
            
            print PHP_EOL . '<!-- finished including libraries -->' . PHP_EOL;
            ?>
    
</head> 
<!-- ############ body section ##################-->

<?php
print '<body id="' . $path_parts['filename'] . '">';
    

if ($debug) {
    print '<p>DEBUG MODE IS ON</p>';
}


$cyberStories = '';
if (isset($_GET["cyberStories"])) {
$cyberStories = htmlentities($_GET['cyberStories'], ENT_QUOTES, "UTF-8");
}


// Open a CSV file
$debug = false;
if (isset($_GET["debug"])) {
$debug = true;
}

$myFolder = '';

$myFileName = 'stories';

$fileExt = '.csv';

$filename = $myFolder . $myFileName . $fileExt;

if ($debug)
print '<p>filename is stories.csv ' . $filename;

$file = fopen($filename, "r");

if ($debug) {
if ($file) {
print '<p>File Opened Succesful.</p>';
} else {
print '<p>File Open Failed.</p>';
}
}
?>
<?php
if ($file) {
if ($debug)
print '<p>Begin reading data into an array.</p>';

// read the header row, copy the line for each header row
// you have.
$stories[] = fgetcsv($file);

if ($debug) {
print '<p>Finished reading headers.</p>';
print '<p>My header array</p><pre>';
print_r($stories);
print '</pre>';
}

// read all the data
while (!feof($file)) {
$stories[] = fgetcsv($file);
//print_r(fgetcsv($file));
}

if ($debug) {
print '<p>Finished reading data. File closed.</p>';
print '<p>My data array<p><pre> ';
print_r($cyberstories);
print '</pre></p>';
}
} // ends if file was opened 
fclose($file);
?>


<!-- end content -->

