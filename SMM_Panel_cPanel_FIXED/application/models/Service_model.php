<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Service Model for SMM Turk
 */
class Service_model extends SMM_Model {
    
    protected $table = 'services';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get services by category
     */
    public function get_by_category($category_id, $status = 'active') {
        $this->db->where('category_id', $category_id);
        $this->db->where('status', $status);
        $this->db->order_by('name', 'ASC');
        
        return $this->get_all();
    }
    
    /**
     * Get service with category details
     */
    public function get_with_category($service_id) {
        $this->db->select('
            services.*,
            categories.name as category_name,
            categories.icon as category_icon
        ');
        $this->db->from('services');
        $this->db->join('categories', 'categories.id = services.category_id');
        $this->db->where('services.id', $service_id);
        
        return $this->db->get()->row();
    }
    
    /**
     * Get all services with categories
     */
    public function get_all_with_categories($status = 'active') {
        $this->db->select('
            services.*,
            categories.name as category_name,
            categories.icon as category_icon
        ');
        $this->db->from('services');
        $this->db->join('categories', 'categories.id = services.category_id');
        $this->db->where('services.status', $status);
        $this->db->order_by('categories.sort_order', 'ASC');
        $this->db->order_by('services.name', 'ASC');
        
        return $this->db->get()->result();
    }
    
    /**
     * Search services
     */
    public function search_services($search_term, $category_id = null) {
        $this->db->select('
            services.*,
            categories.name as category_name
        ');
        $this->db->from('services');
        $this->db->join('categories', 'categories.id = services.category_id');
        $this->db->like('services.name', $search_term);
        $this->db->or_like('services.description', $search_term);
        $this->db->where('services.status', 'active');
        
        if ($category_id) {
            $this->db->where('services.category_id', $category_id);
        }
        
        $this->db->order_by('services.name', 'ASC');
        
        return $this->db->get()->result();
    }
    
    /**
     * Get popular services
     */
    public function get_popular_services($limit = 10) {
        $this->db->select('
            services.*,
            categories.name as category_name,
            COUNT(orders.id) as order_count
        ');
        $this->db->from('services');
        $this->db->join('categories', 'categories.id = services.category_id');
        $this->db->join('orders', 'orders.service_id = services.id', 'left');
        $this->db->where('services.status', 'active');
        $this->db->group_by('services.id');
        $this->db->order_by('order_count', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get service statistics
     */
    public function get_service_stats($service_id) {
        $this->db->select('
            COUNT(orders.id) as total_orders,
            SUM(orders.charge) as total_revenue,
            COUNT(CASE WHEN orders.status = "completed" THEN 1 END) as completed_orders,
            COUNT(CASE WHEN orders.status = "pending" THEN 1 END) as pending_orders
        ');
        $this->db->from('orders');
        $this->db->where('service_id', $service_id);
        
        return $this->db->get()->row();
    }
    
    /**
     * Update service price
     */
    public function update_price($service_id, $new_price) {
        return $this->update($service_id, array(
            'price' => $new_price,
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }
    
    /**
     * Toggle service status
     */
    public function toggle_status($service_id) {
        $service = $this->get_by_id($service_id);
        $new_status = ($service->status === 'active') ? 'inactive' : 'active';
        
        return $this->update($service_id, array(
            'status' => $new_status,
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }
    
    /**
     * Get services by type
     */
    public function get_by_type($type, $status = 'active') {
        $this->db->where('type', $type);
        $this->db->where('status', $status);
        $this->db->order_by('name', 'ASC');
        
        return $this->get_all();
    }
    
    /**
     * Get service count by category
     */
    public function get_count_by_category($category_id, $status = 'active') {
        $this->db->where('category_id', $category_id);
        $this->db->where('status', $status);
        
        return $this->count();
    }
}
