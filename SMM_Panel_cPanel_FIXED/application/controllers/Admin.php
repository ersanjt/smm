<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Service_model');
        $this->load->model('Ticket_model');
    }
    
    /**
     * Admin Dashboard API
     */
    public function stats() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        try {
            // Get stats from database
            $stats = [
                'total_users' => $this->User_model->count_all(),
                'active_users' => $this->User_model->count_active(),
                'total_orders' => $this->Order_model->count_all(),
                'pending_orders' => $this->Order_model->count_by_status('pending'),
                'completed_orders' => $this->Order_model->count_by_status('completed'),
                'total_revenue' => $this->Order_model->get_total_revenue(),
                'success_rate' => $this->calculate_success_rate()
            ];
            
            echo json_encode([
                'success' => true,
                'data' => $stats
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get Users List
     */
    public function users() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $page = $this->input->get('page') ?? 1;
        $limit = $this->input->get('limit') ?? 10;
        $search = $this->input->get('search') ?? '';
        $status = $this->input->get('status') ?? 'all';
        
        try {
            $users = $this->User_model->get_all($page, $limit, $search, $status);
            $total = $this->User_model->count_filtered($search, $status);
            
            echo json_encode([
                'success' => true,
                'data' => $users,
                'pagination' => [
                    'page' => intval($page),
                    'limit' => intval($limit),
                    'total' => $total,
                    'pages' => ceil($total / $limit)
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get Orders List
     */
    public function orders() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $page = $this->input->get('page') ?? 1;
        $limit = $this->input->get('limit') ?? 10;
        $status = $this->input->get('status') ?? 'all';
        
        try {
            $orders = $this->Order_model->get_all_admin($page, $limit, $status);
            $total = $this->Order_model->count_filtered($status);
            
            echo json_encode([
                'success' => true,
                'data' => $orders,
                'pagination' => [
                    'page' => intval($page),
                    'limit' => intval($limit),
                    'total' => $total,
                    'pages' => ceil($total / $limit)
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get Services List
     */
    public function services() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        try {
            $services = $this->Service_model->get_all_admin();
            
            echo json_encode([
                'success' => true,
                'data' => $services
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get Tickets List
     */
    public function tickets() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $page = $this->input->get('page') ?? 1;
        $limit = $this->input->get('limit') ?? 10;
        $status = $this->input->get('status') ?? 'all';
        $priority = $this->input->get('priority') ?? 'all';
        
        try {
            $tickets = $this->Ticket_model->get_all_admin($page, $limit, $status, $priority);
            $total = $this->Ticket_model->count_filtered($status, $priority);
            
            echo json_encode([
                'success' => true,
                'data' => $tickets,
                'pagination' => [
                    'page' => intval($page),
                    'limit' => intval($limit),
                    'total' => $total,
                    'pages' => ceil($total / $limit)
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update User
     */
    public function update_user() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $user_id = $this->input->post('user_id');
        $action = $this->input->post('action');
        
        if (!$user_id || !$action) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required parameters'
            ]);
            return;
        }
        
        try {
            $result = $this->User_model->admin_update($user_id, $action, $this->input->post());
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => "User {$user_id} {$action} successfully"
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update user'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update Order
     */
    public function update_order() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $order_id = $this->input->post('order_id');
        $action = $this->input->post('action');
        
        if (!$order_id || !$action) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required parameters'
            ]);
            return;
        }
        
        try {
            $result = $this->Order_model->admin_update($order_id, $action, $this->input->post());
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => "Order {$order_id} {$action} successfully"
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update order'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update Service
     */
    public function update_service() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $service_id = $this->input->post('service_id');
        $action = $this->input->post('action');
        
        if (!$service_id || !$action) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required parameters'
            ]);
            return;
        }
        
        try {
            $result = $this->Service_model->admin_update($service_id, $action, $this->input->post());
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => "Service {$service_id} {$action} successfully"
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update service'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Calculate Success Rate
     */
    private function calculate_success_rate() {
        $total = $this->Order_model->count_all();
        $completed = $this->Order_model->count_by_status('completed');
        
        if ($total == 0) return 100;
        
        return round(($completed / $total) * 100, 2);
    }
}
