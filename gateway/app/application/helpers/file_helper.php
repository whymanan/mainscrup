<?php
/**
 * File Helper
 *
 * @author       omie vailsun
 * @purpose      File Helper
 */

if(! function_exists("getFileInfo")) {

  function getFileInfo($filename = '') {
    if (file_exists($filename)) {
      $file = pathinfo($filename);
      $file['status'] = 1;
      $file['last_modified'] = date ("F d Y H:i:s.", filemtime($filename));
      $file['size'] = filesize($filename);
      if (getimagesize($filename)) {
        $file['dime'] = getimagesize($filename);
      }
      return $file;
    }else{
      return array('status' => 0, 'msg' => "File Not Found");
    }
  }
}

if(! function_exists("getDuration")) {
  function getDuration($file){
      if (file_exists($file)){
        $filedata = array();
        $handle = fopen($file, "r");
        ## read video file size
        $contents = fread($handle, filesize($file));
        fclose($handle);
        $make_hexa = hexdec(bin2hex(substr($contents,strlen($contents)-3)));
        if (strlen($contents) > $make_hexa){
        $pre_duration = hexdec(bin2hex(substr($contents,strlen($contents)-$make_hexa,3))) ;
        $post_duration = $pre_duration/1000;
        $timehours = $post_duration/3600;
        $timeminutes =($post_duration % 3600)/60;
        $timeseconds = ($post_duration % 3600) % 60;
        $timehours = explode(".", $timehours);
        $timeminutes = explode(".", $timeminutes);
        $timeseconds = explode(".", $timeseconds);
        $filedata = $timehours[0]. ":" . $timeminutes[0]. ":" . $timeseconds[0];}
        return $filedata;
      } else {
        return false;
      }
  }
}

if(! function_exists("convertToReadableSize")) {
  function convertToReadableSize($size){
    $base = log($size) / log(1024);
    $suffix = array("", "KB", "MB", "GB", "TB");
    $f_base = floor($base);
    return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
  }
}

if(! function_exists("writeJSON")) {
  function writeJSON($formdata){
    try
    {
      if (is_file(FILE_JSON_INFO)) {
        //Get form data
   	   //Get data from existing json file
   	   $jsondata = file_get_contents(FILE_JSON_INFO);

   	   // converts json data into array
   	   $arr_data = json_decode($jsondata, true);
   	   // Push user data to array
   	   array_push($arr_data, $formdata);

          //Convert updated array to JSON
   	   $jsondata = json_encode($formdata, JSON_PRETTY_PRINT);
   	   //write json data into file-data.json file
   	   if(file_put_contents(FILE_JSON_INFO, $jsondata))
   	        return true;
   	   else
   	        return false;

      }else {
        // If file Is Not Exist
        $jsondata = json_encode($formdata, JSON_PRETTY_PRINT);
        //write json data into file-data.json file
        if(file_put_contents(FILE_JSON_INFO, $jsondata))
             return true;
        else
             return false;
      }

     }
     catch (Exception $e) {
              echo 'Caught exception: ',  $e->getMessage(), "\n";
     }
 }
}

if(! function_exists("readJSON")) {
  function readJSON(){
    if (is_file(FILE_JSON_INFO)) {
      return file_get_contents(FILE_JSON_INFO);
    }else{
      redirect(base_url('admin/media/get_file_refrace'));
    }
  }
}
