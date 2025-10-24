<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order Model for SMM Turk
 */
class Order_model extends SMM_Model {
    
    protected $table = 'orders';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Create new order
     */
    public function create_order($user_id, $service_id, $link, $quantity) {
        // Get service details
        $this->db->select('price, name');
        $this->db->from('services');
        $this->db->where('id', $service_id);
        $service = $this->db->get()->row();
        
        if (!$service) {
            return false;
        }
        
        $charge = $service->price * $quantity;
        
        $order_data = array(
            'user_id' => $user_id,
            'service_id' => $service_id,
            'link' => $link,
            'quantity' => $quantity,
            'charge' => $charge,
            'start_count' => 0,
            'remains' => $quantity,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        );
        
        return $this->insert($order_data);
    }
    
    /**
     * Get user orders
     */
    public function get_user_orders($user_id, $limit = 20, $offset = 0) {
        $this->db->select('
            orders.*,
            services.name as service_name,
            services.type as service_type,
            categories.name as category_name
        ');
        $this->db->from('orders');
        $this->db->join('services', 'services.id = orders.service_id');
        $this->db->join('categories', 'categories.id = services.category_id');
        $this->db->where('orders.user_id', $user_id);
        $this->db->order_by('orders.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get order with details
     */
    public function get_order_details($order_id) {
        $this->db->select('
            orders.*,
            services.name as service_name,
            services.type as service_type,
            services.description as service_description,
            categories.name as category_name,
            users.username,
            users.email
        ');
        $this->db->from('orders');
        $this->db->join('services', 'services.id = orders.service_id');
        $this->db->join('categories', 'categories.id = services.category_id');
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->where('orders.id', $order_id);
        
        return $this->db->get()->row();
    }
    
    /**
     * Update order status
     */
    public function update_status($order_id, $status, $api_order_id = null) {
        $update_data = array(
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        if ($api_order_id) {
            $update_data['api_order_id'] = $api_order_id;
        }
        
        if ($status === 'in_progress') {
            $update_data['started_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'completed') {
            $update_data['completed_at'] = date('Y-m-d H:i:s');
            $update_data['remains'] = 0;
        }
        
        return $this->update($order_id, $update_data);
    }
    
    /**
     * Get orders by status
     */
    public function get_by_status($status, $limit = 20) {
        $this->db->select('
            orders.*,
            services.name as service_name,
            users.username
        ');
        $this->db->from('orders');
        $this->db->join('services', 'services.id = orders.service_id');
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->where('orders.status', $status);
        $this->db->order_by('orders.created_at', 'ASC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get order statistics
     */
    public function get_order_stats($user_id = null) {
        $this->db->select('
            COUNT(*) as total_orders,
            SUM(charge) as total_revenue,
            COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_orders,
            COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_orders,
            COUNT(CASE WHEN status = "in_progress" THEN 1 END) as in_progress_orders,
            COUNT(CASE WHEN status = "cancelled" THEN 1 END) as cancelled_orders
        ');
        $this->db->from('orders');
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        
        return $this->db->get()->row();
    }
    
    /**
     * Get recent orders
     */
    public function get_recent_orders($limit = 10) {
        $this->db->select('
            orders.*,
            services.name as service_name,
            users.username
        ');
        $this->db->from('orders');
        $this->db->join('services', 'services.id = orders.service_id');
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->order_by('orders.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Search orders
     */
    public function search_orders($search_term, $user_id = null) {
        $this->db->select('
            orders.*,
            services.name as service_name,
            users.username
        ');
        $this->db->from('orders');
        $this->db->join('services', 'services.id = orders.service_id');
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->like('orders.link', $search_term);
        $this->db->or_like('services.name', $search_term);
        $this->db->or_like('users.username', $search_term);
        
        if ($user_id) {
            $this->db->where('orders.user_id', $user_id);
        }
        
        $this->db->order_by('orders.created_at', 'DESC');
        
        return $this->db->get()->result();
    }
}