<?php

Class Custom {

	private $CI;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->model('app_model');
	}


	public function print($data, $die = false)
	{
		echo  '<pre>';
		print_r($data);
		echo  '</pre>';

		if ($die) {
			die();
		}
	}


	public function creator_name($user_id)
	{
		$user = $this->CI->app_model->get_user($user_id);
		if ($user) {
			return $user->first_name.' '.$user->last_name;
		} else {
			return 'NaN';
		}		
	}






}