<?php

Class Media {

	private $CI;

	function __construct() {
		$this->CI =& get_instance();
		// $this->CI->load->database();
		// $this->CI->load->model('app_model');


	}


	public function resize_photo($file_path, $photo_size)
	{
		$size['large'] = array('width'=>1200, 'height'=>1200);
		$size['medium'] = array('width'=>800, 'height'=>800);
		$size['small'] = array('width'=>400, 'height'=>400);

		$width = $size[$photo_size]['width'];
		$height = $size[$photo_size]['height'];

		$config['image_library'] 	= 'gd2';
		$config['source_image'] 	= $file_path;
		$config['maintain_ratio']	= TRUE;
		$config['width']         	= $width;
		$config['height']       	= $height;

		$this->CI->load->library('image_lib', $config);
		if ( ! $this->CI->image_lib->resize()) {
			echo $this->CI->image_lib->display_errors()."\n";
		} else {
			echo $file_path." successfully resized\n";
		}
	}



	public function resize_multiple_photos($files, $photo_size)
	{
		$size['large'] = array('width'=>1200, 'height'=>1200);
		$size['medium'] = array('width'=>800, 'height'=>800);
		$size['small'] = array('width'=>400, 'height'=>400);

		$width = $size[$photo_size]['width'];
		$height = $size[$photo_size]['height'];

		$this->CI->load->library('image_lib');
		foreach ($files as $file_path) {
			$config['image_library'] 	= 'gd2';
			$config['source_image'] 	= $file_path;
			$config['maintain_ratio']	= TRUE;
			$config['width']         	= $width;
			$config['height']       	= $height;
			$this->CI->image_lib->initialize($config);
			if ( ! $this->CI->image_lib->resize()) {
				echo $this->CI->image_lib->display_errors()."\n";
			} else {
				echo $file_path." successfully resized\n";
			}
		}
	}









}