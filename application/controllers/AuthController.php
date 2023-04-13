<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class AuthController extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->helper('verifyauthtoken_helper');
	}
	public function index()
	{
		echo 'this is index function';
	}



	public function login(){
		
		$jwt= new JWT();
		$jwtSecretKey ="mysecretkey";
	
		$email= $this->input->post('email');
		$password= $this->input->post('password');
		$result= $this->AuthModel->check_login($email, $password);
	
		if($result !== 'user not found' && !empty($result)){
			$token = $jwt -> encode($result, $jwtSecretKey, 'HS256');
			echo json_encode($token);
		} else {
			echo "Invalid email or password";
		}
	}
	




	public function signup(){
		$this->load->library('form_validation');
	
		// Set validation rules
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('phone', 'Phone', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('city', 'City', 'required');
		$this->form_validation->set_rules('state', 'State', 'required');
		$this->form_validation->set_rules('country', 'Country', 'required');
		$this->form_validation->set_rules('zip_code', 'Zip Code', 'required');
	
		if($this->form_validation->run() == FALSE){
			// Show validation errors
			echo validation_errors();
		}else{
			// Get form data
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$phone = $this->input->post('phone');
			$address = $this->input->post('address');
			$city = $this->input->post('city');
			$state = $this->input->post('state');
			$country = $this->input->post('country');
			$zip_code = $this->input->post('zip_code');
	
			// Create data array
			$data = array(
				'name' => $name,
				'email' => $email,
				'password' => $password,
				'phone' => $phone,
				'address' => $address,
				'city' => $city,
				'state' => $state,
				'country' => $country,
				'zip_code' => $zip_code
			);
	
			// Call signup function from AuthModel
			$user = $this->AuthModel->signup($data);
	
			if($user){
				// Show success message
				echo "user registered successfully";
			} else {
				// Show error message
				echo "something went wrong";
			}
		}
	}
	
	
	

			// $this->AuthModel->signup();	


	public function token(){

		$jwt= new JWT();
		$jwtSecretKey ="mysecretkey";

		// $email= $this->input->post('email');
		// $password= $this->input->post('password');
		
		$data = array(
			'userId'=>145,
			'email'=>'muhammed@gmail.com',
			'type'=>'admin'
			
		);
		$token = $jwt -> encode($data, $jwtSecretKey, 'HS256');
		echo $token;
	}





	public function decode_token(){
		$token = $this->uri->segment(3);
	
		$jwt= new JWT();
		$jwtSecretKey ="mysecretkey";
		$decode_token = $jwt -> decode($token, $jwtSecretKey, array('HS256'));
		echo "<pre>";
		print_r($decode_token);

			// 	// $token1 = $jwt ->jsonEncode($decode_token);
			// 	// echo $token1;
	}

	function getUsers(){
		$headerToken = $this->input->get_request_header('Authorization');
		$splitToken = explode(" ", $headerToken );
		$token = $splitToken[1];
		try{
			$token =verifyauthtoken_helper($token);
			if($token){
				$users =$this->AuthModel->getUsers();
				echo json_encode($users);
			}
		}
		catch(Exception $e){
			$error = Array(
				"status" =>401,
				"message"=>"Invalid token ",
				"success"=>false
			);
			echo json_encode($error);
		}

	}
	
}
