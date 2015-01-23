<!DOCTYPE html>
<html lang="pl">
  <head>
  

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Harmonogram | Warsztaty językowe AIESEC University</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
  
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

<?php
// enable error reports on screen
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');  //On or Off

require_once "config.php";
// include Podio API
require_once 'podio-php-4.0.2/PodioAPI.php';

// authenticate client to Podio
Podio::setup($harmonogram_client_id, $harmonogram_client_secret);

// authenticate client to app
try {
  Podio::authenticate_with_app($harmonogram_app_id, $harmonogram_app_token);
  // Authentication was a success, now you can start making API calls.
}
catch (PodioError $e) {
  // Something went wrong. Examine $e->body['error_description'] for a description of the error.
	echo $e . "<br><br><br>";
  }
  
?>
    
<? include "top-body.php"; ?>

  <div class="col-md-10 centered">
      <div class="text-center"><img id="logo" class="text-center" src="img/logo.png" style="width:300px; margin: 0 auto;" /></div>
      <br/>
     <h3 class="text-center">Aktualny harmonogram zajęć:</h3>

  </div>
 
    
    

  <div class="col-md-10 col-md-offset-1 centered">
    
     
<?
// iterate through weekdays
for($weekday=1; $weekday<7; $weekday++) {
  // Get items from app with harmonogram_app_id
  $collection = PodioItem::filter($harmonogram_app_id, array(
	"sort_by" => "hour2", // sort items by hour ascending
	"sort_desc" => false,
	"filters" => array(
		"weekday" => $weekday)

	)); 

// weekdays
$days = array("", "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela");

?> <div class="col-md-2 news-content"> <?
print "<h3 class='text-center'>".$days[$weekday]."</h3><br/>";
  
// Iterate through all items in current weekday
foreach ($collection as $item) {
// assign item fields
  $group = $item->title;
  $description = $item->fields["classroom"]->values;
  $start_time = $item->fields["hour2"]->start_time;
  $end_time = $item->fields["hour2"]->end_time;

  // add 1 hour to times (because of problems with UTC timezones on Podio)
  $start_time->add(new DateInterval('PT1H'));
  $end_time->add(new DateInterval('PT1H'));
  
  echo '<div class="row">
          <div class="col-md-12 centered news-item">';
  // print group name
  echo "<p><strong>".$group."</strong></p>";
  
  // print hours
  echo "<p>".$start_time->format("H:i")." - ".$end_time->format('H:i')."</p>";
  
  // print classroom
  echo "<p>".$item->fields["classroom"]->values."</p>";
  
  // separate items
  ?> </div>
          </div>
          <br/>
<?
}
?> </div> <?
}
?>

    </div>
   
  

    
<? include 'bottom-scripts.php'; ?>
    

    
  </body>
</html>