<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends CI_Controller {

	public function resize_all_uploaded_photos($photo_size)
	{
		$directory = './uploads/';
		$files = array_diff(scandir($directory), array('..', '.'));
		$file_paths = [];
		foreach ($files as $file) {
			$file_paths[] = $directory.$file;
		}
		$this->media->resize_multiple_photos($file_paths, $photo_size);
		// $this->media->resize_photo($file_paths[0], $photo_size);
	}






}
