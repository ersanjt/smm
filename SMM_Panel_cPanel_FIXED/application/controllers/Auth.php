<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->library(array('form_validation', 'session'));
    }

    /**
     * Register User
     * Supports: Individual, Agency, Company
     */
    public function register() {
        // Set JSON header
        header('Content-Type: application/json');
        
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validation rules
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[50]|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('user_type', 'User Type', 'required|in_list[individual,agency,company]');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        
        // Additional validation for Agency/Company
        if (isset($input['user_type']) && in_array($input['user_type'], ['agency', 'company'])) {
            $this->form_validation->set_rules('company_name', 'Company Name', 'required');
            $this->form_validation->set_rules('phone', 'Phone Number', 'required');
        }
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors(),
                'errors' => $this->form_validation->error_array()
            ]);
            return;
        }
        
        // Prepare user data
        $userData = [
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'phone' => isset($input['phone']) ? $input['phone'] : null,
            'user_type' => $input['user_type'],
            'company_name' => isset($input['company_name']) ? $input['company_name'] : null,
            'tax_id' => isset($input['tax_id']) ? $input['tax_id'] : null,
            'website' => isset($input['website']) ? $input['website'] : null,
            'address' => isset($input['address']) ? $input['address'] : null,
            'city' => isset($input['city']) ? $input['city'] : null,
            'state' => isset($input['state']) ? $input['state'] : null,
            'zip_code' => isset($input['zip_code']) ? $input['zip_code'] : null,
            'country' => isset($input['country']) ? $input['country'] : null,
            'referral_code' => $this->Auth_model->generateReferralCode(),
            'referred_by' => isset($input['referral_code']) ? $this->Auth_model->getUserByReferralCode($input['referral_code']) : null,
            'email_verification_token' => bin2hex(random_bytes(32)),
            'status' => 'active',
            'role' => 'user',
            'balance' => 0.00
        ];
        
        // Register user
        $userId = $this->Auth_model->register($userData);
        
        if ($userId) {
            // Send welcome email
            // $this->load->library('email');
            // ... send email code ...
            
            echo json_encode([
                'success' => true,
                'message' => 'Registration successful! Please check your email to verify your account.',
                'user_id' => $userId,
                'redirect' => base_url('login')
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ]);
        }
    }
    
    /**
     * Login User
     */
    public function login() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }
        
        $user = $this->Auth_model->login($input['username'], $input['password']);
        
        if ($user) {
            // Update last login
            $this->Auth_model->updateLastLogin($user['id']);
            
            // Set session
            $this->session->set_userdata([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'user_type' => $user['user_type'],
                'role' => $user['role'],
                'logged_in' => true
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Login successful!',
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'user_type' => $user['user_type'],
                    'role' => $user['role']
                ],
                'redirect' => $user['role'] == 'admin' ? base_url('admin') : base_url('panel')
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid username or password'
            ]);
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        $this->session->sess_destroy();
        echo json_encode([
            'success' => true,
            'message' => 'Logged out successfully',
            'redirect' => base_url()
        ]);
    }
    
    /**
     * Check if username/email exists
     */
    public function checkExists() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $field = $input['field'];
        $value = $input['value'];
        
        $exists = $this->Auth_model->checkExists($field, $value);
        
        echo json_encode([
            'exists' => $exists,
            'available' => !$exists
        ]);
    }
}

