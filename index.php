<?php
/**
 * Define Constants
 */
include_once("constants.php");
$data = [];

/**
 * [trimSpace from string]
 * @param  [string] $content [string]
 * @return [string]          [string]
 */
function trimSpace($content){
	return trim(preg_replace('/\s\s+/', ' ', $content));
}

if(file_exists($dir)){
	$lines = file($dir);
	$index = 0;
	foreach ($lines as $line_num => $line) {
		// dividing the packages
		if(preg_match("/{$search}/i", $line)) {
			$package_array = explode(":",trimSpace($line));
			//remove white spaces from array value
			$trimmed_package_array = array_map('trim', $package_array);
			$data[$trimmed_package_array[1]] =[];
		}
		else {
			//creating package object
			$package_content_array = explode(": ",trimSpace($line));
			//remove white spaces from array value			
			$trimmed_package_content_array = array_map('trim', $package_content_array);

			if(sizeof($trimmed_package_content_array)==2) {
				if($trimmed_package_content_array[0] == $search_depends) {
					// get the dependency package array
					$dependency_array = explode(",",trimSpace($trimmed_package_content_array[1]));
					// trim versions and remove duplicate package
					$dependency_array = preg_replace('/ .*$/', "", array_map('trim', $dependency_array));
					$data[$trimmed_package_array[1]][$trimmed_package_content_array[0]]=[];
					$data[$trimmed_package_array[1]][$trimmed_package_content_array[0]] = array_unique($dependency_array);
				}
				else {
					$data[$trimmed_package_array[1]][$trimmed_package_content_array[0]] = $trimmed_package_content_array[1];
						$description_index = $trimmed_package_content_array[0];
				}
			}
			if(sizeof($trimmed_package_content_array)==1) {
				$data[$trimmed_package_array[1]][$description_index] = $data[$trimmed_package_array[1]][$description_index]." ".$trimmed_package_content_array[0];
			}			
		}
	}
	// storing the package object in file
	file_put_contents($output, json_encode($data,true));
}
// read the package object stored from file
$json = file_get_contents($output);
//convert the object to array
$package_array = json_decode($json,true);
// sort the package array by key in alphabetic order 
ksort($package_array);
foreach($package_array as $key => $value) {
    echo '<a href="details.php?key='.urlencode($key).'">'.$key.'</a><br>';
  }
?>