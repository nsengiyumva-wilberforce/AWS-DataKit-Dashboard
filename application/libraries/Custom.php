<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom {

	private $CI;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->config->item('base_url');
	}


	public function run_curl_get($url)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
		));
		$resp = curl_exec($curl);
		curl_close($curl);
		return $resp;
	}


	public function run_curl_post($url, $values)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $values
		));
		$resp = curl_exec($curl);
		curl_close($curl);
		return $resp;
	}



	public function at_curl_post($url, $values)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $values,
			CURLOPT_HTTPHEADER => array('apiKey: 4448e74020e2bff0689b86a4bbf76aedbb0a9946bce94e4e8e61148f843ef94c')
		));
		$resp = curl_exec($curl);
		curl_close($curl);
		return $resp;
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


	public function date_maker($date)
	{
		$date = explode('/', $date);
		return $date[2].'-'.$date[0].'-'.$date[1];
	}



	public function storage_size($storage_info, $filesystems)
	{
		$data = [];
		foreach ($storage_info as $storage) {
			if (in_array($storage->Filesystem, $filesystems)) {
				$data[str_replace('/', '_', $storage->Filesystem)] = (array)$storage;
			}
		}
		return $data;
	}


}