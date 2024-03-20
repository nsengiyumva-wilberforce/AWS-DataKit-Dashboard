<?php
class loginModel extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
    }

    public function saveLoginInfo($data)
    {
       
        $this->db->insert('activity_log', $data);
    
        if ($this->db->affected_rows() == 1) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

        
    public function getUserLoginInfo()
	{
		$this->db->select('*');
		$this->db->from('activity_log');
		//$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

    public function getUserLoginInfoByID($id)
	{
		$this->db->select('*');
		$this->db->from('activity_log');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}

    
    public function generateCode($uni_id)
    {
        //set default time
        date_default_timezone_set('Africa/Nairobi');

        $uni_id = session_id();
        $code=rand(100000,999999);

        $item=array(
            'uni_id'   => $uni_id,
            'is_expired' => '0',
            //'expiration_time' => $expiration_time,
            'code'      =>$code
        );
        $this->db->insert('auth_code', $item);
        return $code;

    }


        public function authenticateCode($uni_id, $code)
    {
        $code_row = $this->db->select('*')
                        ->from('auth_code')
                        ->where('uni_id', $uni_id)
                        ->where('code', $code)
                        ->where('is_expired', '0')
                        ->get()
                        ->row_array(); 

        return !empty($code_row); 
    }

    public function expireTheCode($uni_id)
    {
        // Load the query builder
        $this->load->database(); 
    
        // Use the query builder to update the record
        $this->db->where('uni_id', $uni_id);
        $this->db->update('auth_code', ['is_expired' => TRUE]);
    
        // Check if the update was successful
        if ($this->db->affected_rows() > 0) {
            echo "The code has been successfully expired.";
            return true;
        } else {
            echo "Failed to expire the code.";
            return false;
        }
    }
    
    

    public function updateLogoutTime($uni_id)
    {
        date_default_timezone_set('Africa/Nairobi');
    
        // Load the database if not already loaded
        $this->load->database();
    
        // Use the query builder to update the record
        $this->db->where('uni_id', $uni_id);
        $this->db->update('activity_log', ['logout_time' => date('Y-m-d H:i:s')]);
    
        // Check if the update was successful
        if ($this->db->affected_rows() > 0) {
            echo "Logout time has been updated";
            return true;
        } else {
            echo "Logout time failed to update";
            return false;
        }
    }
    

    public function expireAllCodes() {
		// Get the current time
		$current_time = date('Y-m-d H:i:s');
	
		// Update is_expired to '1' for codes that have expired
		$this->db->set('is_expired', '1');
		$this->db->where('expiration_time <', $current_time);
		$this->db->update('auth_code');
	
		if ($this->db->affected_rows() > 0) {
			echo "Successfully expired all codes.";
			return true;
		} else {
			echo "No codes were expired.";
			return false;
		}
	}
	
	
    
}
?>