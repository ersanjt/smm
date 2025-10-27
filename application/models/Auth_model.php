<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Register new user
     */
    public function register($data) {
        if ($this->db->insert('users', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Login user
     */
    public function login($username, $password) {
        $this->db->where('username', $username);
        $this->db->or_where('email', $username);
        $query = $this->db->get('users');
        
        if ($query->num_rows() > 0) {
            $user = $query->row_array();
            
            // Check if user is active
            if ($user['status'] != 'active') {
                return false;
            }
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                unset($user['password']);
                return $user;
            }
        }
        
        return false;
    }

    /**
     * Check if field exists
     */
    public function checkExists($field, $value) {
        $this->db->where($field, $value);
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }

    /**
     * Update last login
     */
    public function updateLastLogin($userId) {
        $this->db->where('id', $userId);
        $this->db->update('users', [
            'last_login' => date('Y-m-d H:i:s'),
            'login_attempts' => 0
        ]);
    }

    /**
     * Generate unique referral code
     */
    public function generateReferralCode() {
        do {
            $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            $exists = $this->db->where('referral_code', $code)->get('users')->num_rows() > 0;
        } while ($exists);
        
        return $code;
    }

    /**
     * Get user by referral code
     */
    public function getUserByReferralCode($code) {
        $this->db->where('referral_code', $code);
        $query = $this->db->get('users');
        
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        }
        
        return null;
    }

    /**
     * Get user by ID
     */
    public function getUser($userId) {
        $this->db->where('id', $userId);
        $query = $this->db->get('users');
        
        if ($query->num_rows() > 0) {
            $user = $query->row_array();
            unset($user['password']);
            return $user;
        }
        
        return false;
    }

    /**
     * Update user
     */
    public function updateUser($userId, $data) {
        $this->db->where('id', $userId);
        return $this->db->update('users', $data);
    }

    /**
     * Reset password
     */
    public function resetPassword($email, $token, $newPassword) {
        $this->db->where('email', $email);
        $this->db->where('email_verification_token', $token);
        $query = $this->db->get('users');
        
        if ($query->num_rows() > 0) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->db->where('email', $email);
            $this->db->update('users', [
                'password' => $hashedPassword,
                'email_verification_token' => null
            ]);
            
            return true;
        }
        
        return false;
    }
}

