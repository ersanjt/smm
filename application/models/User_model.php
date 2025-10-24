<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Model for SMM Turk
 */
class User_model extends SMM_Model {
    
    protected $table = 'users';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get user by email
     */
    public function get_by_email($email) {
        return $this->get_by(array('email' => $email));
    }
    
    /**
     * Get user by username
     */
    public function get_by_username($username) {
        return $this->get_by(array('username' => $username));
    }
    
    /**
     * Create new user
     */
    public function create_user($data) {
        $user_data = array(
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'phone' => $data['phone'] ?? '',
            'balance' => 0.00,
            'points' => 0,
            'total_spent' => 0.00,
            'status' => 'active',
            'role' => 'user',
            'email_verified' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        return $this->insert($user_data);
    }
    
    /**
     * Verify user login
     */
    public function verify_login($email, $password) {
        $user = $this->get_by_email($email);
        
        if ($user && password_verify($password, $user->password)) {
            // Update last login
            $this->update($user->id, array('last_login' => date('Y-m-d H:i:s')));
            return $user;
        }
        
        return false;
    }
    
    /**
     * Update user balance
     */
    public function update_balance($user_id, $amount, $type = 'add') {
        $user = $this->get_by_id($user_id);
        
        if ($type === 'add') {
            $new_balance = $user->balance + $amount;
        } else {
            $new_balance = $user->balance - $amount;
        }
        
        return $this->update($user_id, array('balance' => $new_balance));
    }
    
    /**
     * Get user statistics
     */
    public function get_user_stats($user_id) {
        $this->db->select('
            COUNT(orders.id) as total_orders,
            SUM(orders.charge) as total_spent,
            COUNT(CASE WHEN orders.status = "completed" THEN 1 END) as completed_orders,
            COUNT(CASE WHEN orders.status = "pending" THEN 1 END) as pending_orders
        ');
        $this->db->from('orders');
        $this->db->where('orders.user_id', $user_id);
        
        return $this->db->get()->row();
    }
    
    /**
     * Get active users
     */
    public function get_active_users($limit = 10) {
        $this->db->where('status', 'active');
        $this->db->order_by('last_login', 'DESC');
        $this->db->limit($limit);
        
        return $this->get_all();
    }
    
    /**
     * Search users
     */
    public function search_users($search_term, $limit = 20) {
        $this->db->like('username', $search_term);
        $this->db->or_like('email', $search_term);
        $this->db->or_like('first_name', $search_term);
        $this->db->or_like('last_name', $search_term);
        $this->db->limit($limit);
        
        return $this->get_all();
    }
}