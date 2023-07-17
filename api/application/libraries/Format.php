<?php

Class Format {

	private $CI;

	function __construct() {
		$this->CI =& get_instance();
	}


	public function data($data, $format = null)
	{
		if (!is_null($format) && ($format == 'json' || $format == 'JSON')) {
			$this->CI->output
				->set_content_type('application/json')
				->set_header('Access-Control-Allow-Origin: *')
				->set_header('Access-Control-Allow-Methods: GET, POST, OPTIONS')
				->set_output(json_encode($data));
		} else {
			echo json_encode($data);
		}
	}

}