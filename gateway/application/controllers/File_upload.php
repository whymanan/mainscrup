<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_upload extends CI_Controller {

  	public function __construct()
  	{
  		parent::__construct();
      $this->load->model('common_model');
      $this->load->helper('directory');

  	}
    public function index() {
      if ($_FILES) {
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $allowImage = array("jpg", "png", "jpeg", "gif");
        $allowPdf = array("pdf", "doc", "ppt");
        if (in_array($extension , $allowImage)) {
          $path = $this->common_model->upload_image($_FILES['file']['size']);
          if(!empty($path)) {
            echo json_encode($path);
          }
        } else if(in_array($extension, $allowPdf)) {

          $path = $this->common_model->upload_Pdf($_FILES['file']['size']);
          if(!empty($path)) {
            echo json_encode($path);
          }
        } else {

          echo json_encode(array('status' => 0, 'msg' => 'not allow  to upload '.$extension.' file type.'));

        }
      }else{
        echo json_encode(array('status' => 0, 'msg' => 'only file upload'));
      }
    }

    public function update_json(){
      $map = directory_map(UPLOAD_FILE, FALSE, TRUE);
      $files = self::Concatenate_Filepaths($map);
      $fileData = self::getFileWithInfo($files);
      foreach ($fileData as $value) {
        $data = [
          'name' => $value['filename'],
          'status' => $value['status'],
          'filetype' => $value['extension'],
          'details' => json_encode($value),
        ];
        $this->common_model->insert($data, 'gallery');
      }
    }

    public function Concatenate_Filepaths ($upload, $prefix = UPLOAD_FILE) {
      $return = array();
      foreach ($upload as $key => $file) {
        if (is_array($file)) {
          $return = array_merge($return, self::Concatenate_Filepaths($file, $prefix . '/' . $key));
        }
        else {
            $return[] = $prefix . '/' . $file;
        }
      }

      return $return;
    }


    public function getFileWithExt($path)
    {
      $filedata = array();

      usort($path, function($x, $y) {
        return filemtime($x) < filemtime($y);
      });

      foreach ($path as $value) {
        if ( preg_match('/(\.jpg|\.jpeg|\.png|\.bmp|\.gif)$/i', $value) )
          $filedata['image'][] = getFileInfo($value);
        else if( preg_match('/(\.mp4|\.mkv|\.avi|\.webm)$/i', $value ) )
          $filedata['video'][] = $value;
        else
          $filedata['other'][] = $value;
      }
      return $filedata;
    }

    public function getFileWithInfo($path)
    {
      $filedata = array();

      usort($path, function($x, $y) {
        return filemtime($x) < filemtime($y);
      });

      foreach ($path as $value) {
        $filedata[] = getFileInfo($value);
      }
      return $filedata;
    }
}
