<?php
defined('BASEPATH') or exit('No direct script access allowed');

//phpmailer 
require_once APPPATH . 'third_party/PHPMailer/src/PHPMailer.php';
require_once APPPATH . 'third_party/PHPMailer/src/SMTP.php';
require_once APPPATH . 'third_party/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class App extends CI_Controller
{

	// private $API_BASE_URLS = 'http://127.0.0.1/aws-api/';
	// private $API_BASE_URLS = 'http://127.0.0.1/aws-api/index.php/app/';
	// private $API_BASE_URLS = 'http://116.203.142.9/aws-api-bak/index.php/app/';


	public function __construct()
{
    parent::__construct();
	$this->load->model('loginModel');
    //$this->load->library('user_agent');
	
}


// public function googleAuth()
// {
// 	if (!$this->session->has_userdata('logged_in')) {
// 		redirect();
// 	}

// 	redirect();
// }


	public function question_library()
	{
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get_questions';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$question_list = $obj_array->data;

		$data['question_list'] = $question_list;
		$data['page'] = 'pages/question-library';
		$data['page_name'] = 'question-library';
		$this->load->view('base', $data);
	}

	public function entry($entry_id)
	{
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}
		
		$url = API_BASE_URL . 'entry?response_id=' . $entry_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['entry'] = $result->data;
		$data['page'] = 'pages/entry';
		$data['page_name'] = 'entry';

		//echo  json_encode( $result);

		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function logout() {
        if ($this->session->has_userdata('logged_in')) {
            $user_data = $this->session->userdata('logged_in');
            $uni_id = isset($user_data ['uni_id']) ? $user_data ['uni_id'] : null;
			
			  // Check if code is provided and validate it
			  $uni_id = session_id();

			  // Expire all codes that have expired
			  $this->loginModel->expireTheCode($uni_id);
	

            // Check if the model instance is not null before calling the method
            if ($this->loginModel !== null && $uni_id  !== null) {
				var_dump($uni_id );
                $this->loginModel ->updateLogoutTime($uni_id ); 
                echo "logout time has been updated.";
            } else {
                echo "Unable to update logout time.";
            }
    
            // Destroy the session
            $this->session->unset_userdata('logged_in');
            redirect();
        }
    }
	
	
   
	public function authenticate()
{
    $params = $this->input->post(NULL, TRUE);
    $params['format'] = 'json';
    $url = API_BASE_URL . 'admin-user/authenticate';

    $result = json_decode($this->custom->run_curl_post($url, $params));

    if ($result->status) {
        $user = $result->data;
        $permissions = json_decode($user->permission_list);
        $uni_id = session_id();
        
		// Setting  the timezone to Africa/Nairobi
        date_default_timezone_set('Africa/Nairobi');
        // Separate login info and user data
        $login_info = [
            'uni_id' => $uni_id,
            'agent' => $this->getUserAgentInfo(),
            'ip' => $this->getIpAddress(),
            'login_time' => date('Y-m-d H:i:s'),
            'name' => $user->first_name . ' ' . $user->last_name,
            'region_id' => $user->region_id,
            'region_code' => $user->region_code,
			
        ];

        // Generate code
        $code = $this->loginModel->generateCode($uni_id);
		//extracting email from the input 
		$email = $this->input->post('username');


        try {
			

            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ochwodavid0311@gmail.com';
            $mail->Password = 'batmmscbbqpslimo';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

			
            // Email Content
			$mail->setFrom('ochwodavid0311@gmail.com', 'ochwo david');
			$mail->addAddress($email, $email);
			 
			 $cid = 'large-logo'; 
			 $filename = ''; 

			// Load the image
			$filename = 'assets/images/Logo/large-logo.png';
			 $mail->addEmbeddedImage($filename, $cid);
		 
			 $data['name'] = $user->first_name . ' ' . $user->last_name;
			 $data['code'] = $code;
		 
			 $mail->Subject = 'Email login pin';
			 $mail->isHTML(true);
		 
			 // Use the HTML template for the email body
			 $email_content = $this->load->view('email_template', $data, TRUE);
		 
			 // Reference the embedded image in the email body
			 $email_content .= '<img src="cid:' . $cid . '" alt="Logo"><br><br>';
		 
			 // Set the email body
			 $mail->Body = $email_content;
		 
			 // Send Email
			 $mail->send();
            
            echo 'Email has been sent successfully!';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        
        $user_data = [
            'uni_id' => $uni_id,
            'name' => $user->first_name . ' ' . $user->last_name,
            'region_id' => $user->region_id,
            'region_code' => $user->region_code,
            'permissions' => $permissions,
            'code' => $code, 
            'logged_in' => TRUE
        ];

        // Save login info and set session data
        $la_id = $this->loginModel->saveLoginInfo($login_info);
        if ($la_id) {
            $this->session->set_userdata('logged_in', $la_id);
            $this->session->set_userdata($user_data);
        }

        //load the authorize view to allow the code to be input 
        $this->load->view('authorize', $user_data);
    } else {
        $this->session->set_flashdata('err_msg', $result->message);
        redirect();
    }
}


	
//get the kind of browser being used 
	public function getUserAgentInfo()
	{
		$agent = $this->input->user_agent();
	
		if ($this->agent->is_browser()) {
			$currentAgent = $this->agent->browser();
		} elseif ($this->agent->is_robot()) {
			$currentAgent = $this->agent->robot();
		} elseif ($this->agent->is_mobile()) {
			$currentAgent = $this->agent->mobile();
		} else {
			$currentAgent = 'unidentified user agent';
		}
	
		return $currentAgent;
	}
	
	

	public function logActivity($login_info) {
		// Assuming there is a model called Activity_model
		$this->load->model('loginModel');
	
		// Check if the model is loaded successfully
		if ($this->loginModel) {
			// Call the insert method on the model
			$this->loginModel->insert($login_info);
		} else {
			// Handle the case where the model failed to load
			log_message('error', 'Activity_model failed to load');
			// You might want to throw an exception or handle the error in an appropriate way
		}
	}
	
//getting the ip address of the device being used 
	function getIpAddress() {
		// Check for shared internet/ISP IP
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}
	
		// Check for IP address in proxy header
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
	
		// Otherwise, return the user's IP address
		else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	public function log_activity()
	{
		// Check if the user is logged in
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}
	
		// Retrieve login information from the model
		$login_info = $this->loginModel->getUserLoginInfo();
	
		// Assign login information to the data array
		$data['login_info'] = $login_info;
	
		// Define other data for the view
		$data['page'] = 'pages/activity-log';
		$data['page_name'] = 'activity-log';
	
		// Load the view with the data
		$this->load->view('base', $data);
	}

	public function log_activity_byId($id)
	{
		 // Check if the user is logged in
		 if (!$this->session->has_userdata('logged_in')) {
		
		 	redirect();
		 }

		 // Retrieve login information from the model
		$login_info = $this->loginModel->getUserLoginInfoByID($id);
		
		$result = $this->loginModel->getUserLoginInfoByID($id);
		
		 $data['login_info'] = $login_info;
	
		 // Define other data for the view
		 $data['page'] = 'pages/logs';
		 $data['page_name'] = 'logs';
	
		 
		 $this->load->view('base', $data);
		//$this->load->view('pages/logs');
	}
	

	public function validateCode($code)
{
    // Check if the user is logged in
    if ($this->session->has_userdata('logged_in')) {
        // Get the database ID from the session
        //$id = $this->session->userdata('id'); // Replace 'database_id' with the actual key used to store the database ID in the session
		$uni_id=session_id();
        // If the database ID is available
        if ($uni_id) {
            // Load the loginModel
            $this->load->model('loginModel');
            
            // Call the authenticateCode method of loginModel with the database ID
            return $this->loginModel->authenticateCode($uni_id, $code);
        } else {
            // If the database ID is not available in the session.
            return false;
        }
    } else {
        // If the user is not logged in, return false
        return false;
    }
}

	public function codeValidation()
{
    // CHECK FOR SESSION
    if ($this->session->has_userdata('logged_in')) {
        $this->load->model('loginModel'); 
        
        // Check if code is provided and validate it
        $uni_id = session_id();
        $code = $this->input->post('code');
        $uni_id = $this->input->post('uni_id');
        
        // Expire all codes that have expired
        //$this->loginModel->expireTheCode($uni_id); 

        if ($code ) {
            if (!$this->validateCode($code)) {
                
                // Code is invalid, redirect to login page
                $this->session->set_flashdata('error', 'Invalid verification code');
                $data['code'] = $code;
                //$data['uni_id'] = $uni_id;
                // Load the authorize.php view and pass data to it
                $this->load->view('authorize', $data);
				
            } else {
                // Code is valid, load the dashboard view
                echo "Code was valid";
                redirect();
                return;
            }
        } else {
            // User is not logged in, redirect to login page
            $this->load->view('login');
        }
    }
}


	
public function index()
{
    // Check for session
    if ($this->session->has_userdata('logged_in')) {
        $code = $this->input->post('code');
        $uni_id = session_id(); 
        
        // Check if a code is provided
        if ($code) {
          

            // Validate the code
            if (!$this->validateCode($code)) {
                // Code is invalid, set flash message and redirect
                $this->session->set_flashdata('error', 'Invalid verification code');
                redirect();
                return;
            } else {
                // Code is valid
               
            }
        }

        // Load dashboard data
        $url = API_BASE_URLS . 'server-disk-space';
        $result = json_decode($this->custom->run_curl_get($url));
        $storage_info = $result->data ?? [];
        $storage = $this->custom->storage_size($storage_info, ['/dev/vda1', 'udev']);
        $data['storage'] = $storage;

        $url = API_BASE_URL . 'charts';
        $result = json_decode($this->custom->run_curl_get($url));
        $charts = $result->data ?? [];
        $data['charts'] = $charts;

        $url = API_BASE_URL . 'overview-counter';
        $result = json_decode($this->custom->run_curl_get($url));
        $counter = $result->data;
        $data['counter'] = $counter;

        $data['page'] = 'pages/dashboard';
        $data['page_name'] = 'dashboard';

        $this->load->view('base', $data);
    } else {
        // Session not found, load login view
        $this->load->view('login');
    }
}


	public function expireCode($uni_id)
    {
		$uni_id=session_id();

        $this->load->model('loginModel');
        $this->loginModel->expireTheCode($uni_id);
    }

	


	public function checkExpiredCodes() {
		// Get current time
		$current_time = date('Y-m-d H:i:s');
		// Select codes that have expired
		$this->db->where('expiration_time <', $current_time);
		$expired_codes = $this->db->get('auth_code')->result();
		foreach ($expired_codes as $code) {
			// Mark code as expired
			$this->db->where('code', $code->code);
			$this->db->update('auth_code', array('is_expired' => TRUE));
		}
	}

	public function dashboard()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in') ) {
			redirect();
		}

		$url = API_BASE_URLS . 'server-disk-space';
		$result = json_decode($this->custom->run_curl_get($url));
		$storage_info = $result->data ?? [];
		$storage = $this->custom->storage_size($storage_info, ['/dev/sda1', '/dev/sdb']);

		$url = API_BASE_URL . 'charts';
		$result = json_decode($this->custom->run_curl_get($url));
		$charts = $result->data ?? [];

		$url = API_BASE_URLS . 'dasboard-overview-counter?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$counter = $result->data;

		$data['counter'] = $counter;
		$data['storage'] = $storage;
		$data['charts'] = $charts;
		$data['page'] = 'pages/dashboard';
		$data['page_name'] = 'dashboard';
		// $this->custom->print($data); die();	
		$this->load->view('base', $data);
	}


	public function dashboard_entry_edit($entry_id, $target)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URL . 'entry?response_id=' . $entry_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$entry = $result->data;
		$url = API_BASE_URLS . 'get-form?form_id=' . $entry->form_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$form = $result->data;

		$url = API_BASE_URL . 'app-lists?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$app_lists = $result->data;

		$data['app_lists'] = $app_lists;
		$data['form'] = $form;
		$data['entry'] = $entry;
		$data['target'] = $target;
		$data['page'] = 'pages/dashboard-edit';
		$data['page_name'] = 'dashboard-edit';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function submit_dashboard_edit($response_id, $target)
{
    // Check for form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Fetch form data from POST request
        $form_data = $this->input->post(NULL, TRUE);

        // Process form data for API submission
        $api_data = [
            '$response_id' => $response_id,
            'title' => $form_data->title,
            'is_geotagged' => $form_data['is_geotagged'] ?? 0,
            'is_photograph' => $form_data['is_photograph'] ?? 0,
            'is_followup' => $form_data['is_followup'] ?? 0,
            'followup_interval' => $form_data['followup_interval'] ?? NULL,
            'title_fields' => $this->processTitleFields($form_data),
            'followup_prefill' => $this->processFollowupPrefill($form_data),
            'is_publish' => $form_data['is_publish'] ?? 0,
        ];
		

        // Send API request for form editing
        $url = API_BASE_URLS . 'app/dashboard_entry_edit';
        $result = $this->custom->run_curl_post($url, $api_data);

        // Uncomment the following line for debugging purposes
        // $this->custom->print($result); die();

        // Redirect to the form page after editing
        redirect('dashboard_entry_edit' . $form_id);
    }
}

// Helper function to process title fields
private function processTitleFields($form_data)
{
    $entry_title = $form_data['entry_title'] ? explode(',', str_replace(' ', '', $form_data['entry_title'])) : [];
    $entry_subtitle = $form_data['entry_subtitle'] ? explode(',', str_replace(' ', '', $form_data['entry_subtitle'])) : [];

    return json_encode(['entry_title' => $entry_title, 'entry_sub_title' => $entry_subtitle]);
}

// Helper function to process follow-up prefill
private function processFollowupPrefill($form_data)
{
    $followup_prefill = $form_data['followup_prefill'] ? explode(',', str_replace(' ', '', $form_data['followup_prefill'])) : [];

    return json_encode($followup_prefill);
}

	public function forms()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-forms-basic?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$forms = $obj_array->data;
		$data['forms'] = $forms;

		$data['page'] = 'pages/forms';
		$data['page_name'] = 'forms';
		$this->load->view('base', $data);
	}

	public function form_builder($form_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URL . 'form?form_id=' . $form_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$result = json_decode($this->custom->run_curl_get($url));
		$data['form'] = $result->data;

		$url = API_BASE_URL . 'question?form_id=' . $form_id;
		$result = json_decode($this->custom->run_curl_get($url));
		$question_list = $result->data;

		$data['question_list'] = $question_list;
		$data['page'] = 'pages/form-builder';
		$data['page_name'] = 'forms';
		$this->load->view('base', $data);
	}

	public function form_settings($form_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URL . 'form?form_id=' . $form_id . '&settings=true&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['form'] = $result->data;
		$data['page'] = 'pages/form-settings';
		$data['page_name'] = 'form-settings';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function form($form_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-form?form_id=' . $form_id . '&format=json';
		// $url = API_BASE_URLS.'get-forms-basic?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['form'] = $result->data;
		$data['page'] = 'pages/form';
		$data['page_name'] = 'form';
		$this->load->view('base', $data);
	}



	public function maps()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-forms-basic?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$forms = $obj_array->data;
		$data['forms'] = $forms;

		$data['page'] = 'pages/form-maps';
		$data['page_name'] = 'form-maps';
		$this->load->view('base', $data);
	}



	public function map($form_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-form?form_id=' . $form_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$form = $result->data;
		$report_title = 'Maps > ' . $form->title;

		$url = API_BASE_URL . 'entry/showmaps?form_id=' . $form_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));

		$url = API_BASE_URL . 'regions?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$regions = $result->data;

		$map_url = API_BASE_URL;
		$data['report_title'] = $report_title;
		$data['geodata'] = $result->data ?? [];
		$data['regions'] = $regions;
		$data['map_url'] = $map_url;
		$data['page'] = 'pages/map';
		$data['page_name'] = 'map';
		$data['form_id'] = $form_id;
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}



	public function entries()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URL . 'forms?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$forms = $obj_array->data;
		$data['forms'] = $forms;

		$data['page'] = 'pages/entries';
		$data['page_name'] = 'entries';
		$this->load->view('base', $data);
	}

	public function form_entries($form_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}


		$url = API_BASE_URL . 'forms?form_id=' . $form_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$form = $result->data;
		$report_title = 'Entries > ' . $form->title;

		$url = API_BASE_URL . 'regions?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$regions = $result->data;

		ini_set('memory_limit', '1024M');
		$query_param = $_SESSION['region_id'] != 0 ? '&region_id=' . $_SESSION['region_id'] : '';

		$url = API_BASE_URL . 'entry/getRegionalEntries?form_id=' . $form_id . $query_param . '&format=json';

		$result = json_decode($this->custom->run_curl_get($url));
		$data['report_title'] = $report_title;
		$data['regions'] = $regions ?? [];
		$data['entries'] = $result->data ?? [];
		$data['form_id'] = $form_id;
		$data['page'] = 'pages/form-entries';
		$data['page_name'] = 'form-entries';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}


	public function reports()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-forms-basic?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$forms = $obj_array->data;
		$data['forms'] = $forms ?? [];

		$data['page'] = 'pages/reports';
		$data['page_name'] = 'reports';
		$this->load->view('base', $data);
	}

	public function insights()
	{
		$baseline_url = API_BASE_URL . 'entries/group-by-region?data_type=baseline&form_id=11';
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_region = array_column($baseline_data, 'count');

		$followup_url = API_BASE_URL . 'entries/group-by-region?data_type=followup&form_id=11';
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_region = array_column($followup_data, 'count');

		$baseline_url = API_BASE_URL . 'entries/group-by-latrine-coverage?data_type=baseline&form_id=11';
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_latrine_coverage = array_column($baseline_data, 'count');
					
		$followup_url = API_BASE_URL . 'entries/group-by-latrine-coverage?data_type=followup&form_id=11';
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_latrine_coverage = array_column($followup_data, 'count');

		$baseline_url = API_BASE_URL . 'entries/group_by_sanitation_category?data_type=baseline&form_id=11';
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_sanitation_category = array_column($baseline_data, 'count');
		
		$followup_url = API_BASE_URL . 'entries/group_by_sanitation_category?data_type=followup&form_id=11';
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_sanitation_category = array_column($followup_data, 'count');

		$baseline_url = API_BASE_URL . 'entries/group-by-duration-of-water-collection?data_type=baseline&form_id=11';
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_water_collection = array_column($baseline_data, 'count');
		
		$followup_url = API_BASE_URL . 'entries/group-by-duration-of-water-collection?data_type=followup&form_id=11';
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_water_collection = array_column($followup_data, 'count');

		$baseline_url = API_BASE_URL . 'entries/group-by-water-treatment?data_type=baseline&form_id=11';
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_water_treatment = array_column($baseline_data, 'count');
		
		$followup_url = API_BASE_URL . 'entries/group-by-water-treatment?data_type=followup&form_id=11';
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_water_treatment = array_column($followup_data, 'count');

		$baseline_url = API_BASE_URL . 'entries/group-by-family-savings?data_type=baseline&form_id=11';
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_family_savings = array_column($baseline_data, 'count');

		$followup_url = API_BASE_URL . 'entries/group-by-family-savings?data_type=followup&form_id=11';
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_family_savings = array_column($followup_data, 'count');

		
		$region_and_district_url = API_BASE_URL . 'entries/group-by-region-and-districts?data_type=followup&form_id=11';
		$region_and_district_result = json_decode($this->custom->run_curl_get($region_and_district_url))->data;

		$data['baseline_region'] = json_encode($baseline_region);
		$data['followup_region'] = json_encode($followup_region);
		$data['baseline_latrine_coverage'] = json_encode($baseline_latrine_coverage);
		$data['followup_latrine_coverage'] = json_encode($followup_latrine_coverage);
		$data['baseline_sanitation_category'] = json_encode($baseline_sanitation_category);
		$data['followup_sanitation_category'] = json_encode($followup_sanitation_category);
		$data['baseline_water_collection'] = json_encode($baseline_water_collection);
		$data['followup_water_collection'] = json_encode($followup_water_collection);
		$data['baseline_water_treatment'] = json_encode($baseline_water_treatment);
		$data['followup_water_treatment'] = json_encode($followup_water_treatment);
		$data['baseline_family_savings'] = json_encode($baseline_family_savings);
		$data['followup_family_savings'] = json_encode($followup_family_savings);
		$data['region_and_district'] = json_encode($region_and_district_result);
		
		$data['page'] = 'pages/insights';
		$data['page_name'] = 'insights';
		// $this->custom->print($data); die();	
		//print json_encode($data);
		$this->load->view('base', $data);
	}


	public function entries_report($form_id, $entry_data)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-form?form_id=' . $form_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$form = $result->data;
		$report_title = 'Reports > ' . $form->title . ' > ' . ucfirst($entry_data);

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$regions = $result->data;

		$url = API_BASE_URLS . 'get-projects?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$projects = $result->data;

		// $url2 = API_BASE_URL.'entry/paginator?form_id='.$form_id.'&format=json';
//                 $result2 = json_decode($this->custom->run_curl_get($url2));
// //
// //$total=0;
//                 $total = $result2->data;
// //print($url2);
// 		$data['regions'] = $regions ?? [];
// 		$data['projects'] = $projects ?? [];

		// //
// $data['pages'] = ceil($total/PER_PAGE);
		$data['regions'] = $regions ?? [];
		$data['projects'] = $projects ?? [];
		$data['form_id'] = $form_id;
		$data['report_title'] = $report_title;
		$data['entry_data'] = $entry_data;
		$data['page'] = 'pages/report';
		$data['page_name'] = 'report';
		$this->load->view('base', $data);
	}

	public function aggregated_report($form_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-form?form_id=' . $form_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$form = $result->data;
		$report_title = 'Reports > ' . $form->title . ' > Aggregated';

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$regions = $obj_array->data;

		$url = API_BASE_URLS . 'get-projects?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$projects = $result->data;

		$data['regions'] = $regions;
		$data['projects'] = $projects ?? [];
		$data['report_title'] = $report_title;
		$data['form_id'] = $form_id;
		$data['page'] = 'pages/aggregated-report';
		$data['page_name'] = 'report';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}


	public function edit_entry($entry_id, $target)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URL . 'entry?response_id=' . $entry_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$entry = $result->data;
		$url = API_BASE_URLS . 'get-form?form_id=' . $entry->form_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$form = $result->data;

		$url = API_BASE_URL . 'app-lists?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$app_lists = $result->data;

		$data['app_lists'] = $app_lists;
		$data['form'] = $form;
		$data['entry'] = $entry;
		$data['target'] = $target;
		$data['page'] = 'pages/form-edit-entry';
		$data['page_name'] = 'edit-entry';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function entry_followups($entry_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		//$url = API_BASE_URLS.'get-clean-response?response_id='.$entry_id.'&followups=1&format=json';
		$url = API_BASE_URL . 'entry?response_id=' . $entry_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['entry'] = $result->data;
		$data['page'] = 'pages/entry-followups';
		$data['page_name'] = 'entry-followups';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function mobile_users()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}
		//$url = 'http://157.245.19.48/aws.api/public/users?format=json';
		$url = API_BASE_URLS . 'get-users?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['users'] = $result->data;
		$data['page'] = 'pages/mobile-users';
		$data['page_name'] = 'mobile-users';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function mobile_user($user_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}
		$url = 'http://157.245.19.48/aws.api/public/users?user_id=' . $user_id . '&format=json';
		//$url = API_BASE_URLS.'get-user?user_id='.$user_id.'&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['user'] = $result->data;
		$data['page'] = 'pages/mobile-user';
		$data['page_name'] = 'mobile-user';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function dashboard_users()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URL . 'admin-users?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['users'] = $result->data;
		$data['page'] = 'pages/dashboard-users';
		$data['page_name'] = 'dashboard-users';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function dashboard_user($user_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URL . 'admin-users?user_id=' . $user_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['user'] = $result->data;
		$data['page'] = 'pages/dashboard-user';
		$data['page_name'] = 'dashboard-user';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function settings()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$user_id = 1;
		$url = API_BASE_URLS . 'get-admin-user?user_id=' . $user_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['user'] = $result->data;
		$data['page'] = 'pages/settings';
		$data['page_name'] = 'settings';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}






	public function add_mobile_user()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$regions = $result->data;

		$data['regions'] = $regions;
		$data['form_action'] = 'data-form/add-mobile-user';
		$data['action'] = 'Add';
		$data['page'] = 'pages/form-mobile-user';
		$data['page_name'] = 'add-mobile-user';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}


	public function edit_mobile_user($user_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-user?user_id=' . $user_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$user = $result->data;

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$regions = $result->data;

		$data['user'] = $user;
		$data['regions'] = $regions;
		$data['form_action'] = 'data-form/edit-mobile-user/' . $user_id;
		$data['action'] = 'Edit';
		$data['page'] = 'pages/form-mobile-user';
		$data['page_name'] = 'edit-mobile-user';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function delete_mobile_user($user_id)
	{
		$params['user_id'] = $user_id;
		// $url = API_BASE_URLS.'soft-delete-admin-user/'.$params['user_id'];
		$url = API_BASE_URLS . 'delete-user/' . $params['user_id'];
		$result = $this->custom->run_curl_post($url, $params);
		// $user = json_decode($result);
		redirect('mobile-users');
	}




	public function add_dashboard_user()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-roles?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$roles = $result->data;

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$regions = $result->data;

		$data['regions'] = $regions;
		$data['roles'] = $roles;
		$data['form_action'] = 'data-form/add-dashboard-user';
		$data['action'] = 'Add';
		$data['page'] = 'pages/form-dashboard-user';
		$data['page_name'] = 'add-dashboard-user';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}


	public function edit_dashboard_user($user_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URL . 'admin-users?user_id=' . $user_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$user = $result->data;

		$url = API_BASE_URLS . 'get-roles?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$roles = $result->data;

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$regions = $result->data;

		$data['user'] = $user;
		$data['regions'] = $regions;
		$data['roles'] = $roles;
		$data['form_action'] = 'data-form/edit-dashboard-user/' . $user_id;
		$data['action'] = 'Edit';
		$data['page'] = 'pages/form-dashboard-user';
		$data['page_name'] = 'edit-dashboard-user';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}


	public function delete_dashboard_user($user_id)
	{
		$params['user_id'] = $user_id;
		// $url = API_BASE_URLS.'soft-delete-admin-user/'.$params['user_id'];
		$url = API_BASE_URL . 'admin-user/delete';
		var_dump($url);
		$result = $this->custom->run_curl_post($url, $params);
		// $user = json_decode($result);
		redirect('dashboard-users');
	}



	public function admin_roles()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-roles?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$roles = $result->data;

		$data['page_name'] = 'roles';
		$data['page'] = 'pages/admin-roles';
		$data['roles'] = $roles;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}


	public function add_chart()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-forms-basic?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$forms = $obj_array->data;

		foreach ($forms as $form) {
			$source[] = array('value' => $form->form_id, 'label' => $form->title);
		}
		$data['forms'] = $source ?? [];
		$data['form_action'] = 'create-chart';
		$data['action'] = 'Add';
		$data['page'] = 'pages/data-form-chart';
		$data['page_name'] = 'add-chart';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function edit_chart($chart_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-forms-basic?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$forms = $obj_array->data ?? [];

		$url = API_BASE_URL . 'charts?chart_id=' . $chart_id . '&format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$chart = $result->data;

		foreach ($forms as $form) {
			$source[] = array('value' => $form->form_id, 'label' => $form->title);
			if (in_array($form->form_id, json_decode($chart->form_list))) {
				$selected_forms[] = array('value' => $form->form_id, 'label' => $form->title);
			}
		}
		$data['forms'] = $source ?? [];
		$data['selected_forms'] = $selected_forms ?? [];
		$data['chart'] = $chart;
		$data['form_action'] = 'update-chart/' . $chart_id;
		$data['action'] = 'Edit';
		$data['page'] = 'pages/data-form-chart';
		$data['page_name'] = 'edit-chart';
		// $this->custom->print($data); die();
		$this->load->view('base', $data);
	}

	public function organisations()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-organisations?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$organisations = $result->data;

		$data['page_name'] = 'organisations';
		$data['page'] = 'pages/list-organisations';
		$data['organisations'] = $organisations;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function add_organisation()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$data['action'] = 'Add';
		$data['page_name'] = 'add-organisation';
		$data['page'] = 'pages/data-form-organisation';
		$data['form_action'] = 'create-organisation';
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function edit_organisation($organisation_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-organisation?organisation_id=' . $organisation_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$organisation = $obj_array->data;

		$data['action'] = 'Edit';
		$data['page_name'] = 'edit-organisation';
		$data['page'] = 'pages/data-form-organisation';
		$data['organisation'] = $organisation;
		$data['form_action'] = 'update-organisation/' . $region_id;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function regions()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$regions = $result->data;

		$data['page_name'] = 'regions';
		$data['page'] = 'pages/list-regions';
		$data['regions'] = $regions;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function add_region()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$data['action'] = 'Add';
		$data['page_name'] = 'add-region';
		$data['page'] = 'pages/data-form-region';
		$data['form_action'] = 'create-region';
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function edit_region($region_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-region?region_id=' . $region_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$region = $obj_array->data;

		$data['action'] = 'Edit';
		$data['page_name'] = 'edit-region';
		$data['page'] = 'pages/data-form-region';
		$data['region'] = $region;
		$data['form_action'] = 'update-region/' . $region_id;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}


	public function districts()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-districts?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$districts = $result->data;

		$data['page_name'] = 'districts';
		$data['page'] = 'pages/list-districts';
		$data['districts'] = $districts;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function add_district()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$regions = $obj_array->data;

		$data['action'] = 'Add';
		$data['page_name'] = 'add-district';
		$data['page'] = 'pages/data-form-district';
		$data['regions'] = $regions;
		$data['form_action'] = 'create-district';
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function edit_district($district_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-district?district_id=' . $district_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$district = $obj_array->data;

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$regions = $obj_array->data;

		$data['action'] = 'Edit';
		$data['page_name'] = 'edit-district';
		$data['page'] = 'pages/data-form-district';
		$data['district'] = $district;
		$data['regions'] = $regions;
		$data['form_action'] = 'update-district/' . $district_id;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function sub_counties()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-sub-counties?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$sub_counties = $result->data;

		$data['page_name'] = 'sub-counties';
		$data['page'] = 'pages/list-sub-counties';
		$data['sub_counties'] = $sub_counties;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function add_sub_county()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$regions = $obj_array->data;

		$data['action'] = 'Add';
		$data['page_name'] = 'add-sub-county';
		$data['page'] = 'pages/data-form-sub-county';
		$data['regions'] = $regions;
		$data['form_action'] = 'create-sub-county';
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function edit_sub_county($sub_county_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-sub-county?sub_county_id=' . $sub_county_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$sub_county = $obj_array->data;

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$regions = $obj_array->data;

		$url = API_BASE_URLS . 'get-districts?region_id=' . $sub_county->region_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$districts = $obj_array->data;

		$data['action'] = 'Edit';
		$data['page_name'] = 'edit-sub-county';
		$data['page'] = 'pages/data-form-sub-county';
		$data['sub_county'] = $sub_county;
		$data['regions'] = $regions;
		$data['districts'] = $districts;
		$data['form_action'] = 'update-sub-county/' . $sub_county_id;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}


	public function parishes()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-parishes?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$parishes = $result->data;

		$data['page_name'] = 'parishes';
		$data['page'] = 'pages/list-parishes';
		$data['parishes'] = $parishes;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function add_parish()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$regions = $obj_array->data;

		$data['action'] = 'Add';
		$data['page_name'] = 'add-parish';
		$data['page'] = 'pages/data-form-parish';
		$data['regions'] = $regions;
		$data['form_action'] = 'create-parish';
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function edit_parish($parish_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-parish?parish_id=' . $parish_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$parish = $obj_array->data;

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$regions = $obj_array->data;

		$url = API_BASE_URLS . 'get-districts?region_id=' . $parish->region_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$districts = $obj_array->data;

		$url = API_BASE_URLS . 'get-sub-counties?district_id=' . $parish->district_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$sub_counties = $obj_array->data;

		$data['action'] = 'Edit';
		$data['page_name'] = 'edit-parish';
		$data['page'] = 'pages/data-form-parish';
		$data['parish'] = $parish;
		$data['regions'] = $regions;
		$data['districts'] = $districts;
		$data['sub_counties'] = $sub_counties;
		$data['form_action'] = 'update-parish/' . $parish_id;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function villages()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-villages?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$villages = $result->data;

		$data['page_name'] = 'villages';
		$data['page'] = 'pages/list-villages';
		$data['villages'] = $villages;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function add_village()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$regions = $obj_array->data;

		$data['action'] = 'Add';
		$data['page_name'] = 'add-village';
		$data['page'] = 'pages/data-form-village';
		$data['regions'] = $regions;
		$data['form_action'] = 'create-village';
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function edit_village($village_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-village?village_id=' . $village_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$village = $obj_array->data;

		$url = API_BASE_URLS . 'get-regions?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$regions = $obj_array->data;

		$url = API_BASE_URLS . 'get-districts?region_id=' . $village->region_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$districts = $obj_array->data;

		$url = API_BASE_URLS . 'get-sub-counties?district_id=' . $village->district_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$sub_counties = $obj_array->data;

		$url = API_BASE_URLS . 'get-parishes?sub_county_id=' . $village->sub_county_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$parishes = $obj_array->data;

		$data['action'] = 'Edit';
		$data['page_name'] = 'edit-village';
		$data['page'] = 'pages/data-form-village';
		$data['village'] = $village;
		$data['regions'] = $regions;
		$data['districts'] = $districts;
		$data['sub_counties'] = $sub_counties;
		$data['parishes'] = $parishes;
		$data['form_action'] = 'update-village/' . $village_id;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function projects()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-projects?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$projects = $result->data ?? [];

		$data['page_name'] = 'projects';
		$data['page'] = 'pages/list-projects';
		$data['projects'] = $projects;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function add_project()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$data['action'] = 'Add';
		$data['page_name'] = 'add-project';
		$data['page'] = 'pages/data-form-project';
		$data['form_action'] = 'create-project';
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}

	public function edit_project($project_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect();
		}

		$url = API_BASE_URLS . 'get-project?project_id=' . $project_id . '&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$project = $obj_array->data;

		$data['action'] = 'Edit';
		$data['page_name'] = 'edit-project';
		$data['page'] = 'pages/data-form-project';
		$data['project'] = $project;
		$data['form_action'] = 'update-project/' . $project_id;
		// $data['permissions'] = $this->session->userdata('permissions');
		$this->load->view('base', $data);
	}







	public function sms()
	{
		// $url = 'https://api.sandbox.africastalking.com/version1/messaging';
		$url = 'https://api.tunzaApp.africastalking.com/version1/messaging';
		$data['username'] = 'tunzaApp';
		$data['to'] = '+256782120367';
		$data['message'] = 'Testing this api';
		// $data['from'] = 'Tunza';
		print_r($this->custom->at_curl_post($url, $data));
	}
















}