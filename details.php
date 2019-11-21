<?php
/**
 * Define Constants
 */
include_once("constants.php");
$input= str_replace('%2B', '+', $_GET['key']);
$dependedby_array = [];

// read the package object stored from file
$json = file_get_contents($output);
//convert the object to array
$package_array = json_decode($json,true);
echo '<h1>Details of Package '.$input.'</h1>';
echo '<ol>'.
  '<li>Name: '. $input .'</li>'.
  '<li>Description: '. (!empty($package_array[$input]['Description']) ? $package_array[$input]['Description']:'NO Package Description') .'</li>'.
  '<li>Depends On: ';
   if(!empty($package_array[$input]['Depends'])) {
  	foreach($package_array[$input]['Depends'] as $key => $value) {
	    echo '<a href="details.php?key='.urlencode($value).'">'.$value.'</a> ';
	  }
  } 
  else {
  	echo 'NO Dependencies Package';
  }

  echo '</li>'.
  '<li>Depended By: ';
  // created dependedby array
  foreach($package_array as $key => $value) {
	if(!empty($value['Depends'])) {
		if(in_array($input, $value['Depends'])){
			array_push($dependedby_array, $key);
		}
	}
  }
  // display the depended package array
  if(!empty($dependedby_array)) {
  	foreach($dependedby_array as $key => $value) {
	    echo '<a href="details.php?key='.urlencode($value).'">'.$value.'</a> ';
	  }
  } 
  else {
  	echo 'NO Dependend Package';
  }
  echo '</li></ol>';

?>