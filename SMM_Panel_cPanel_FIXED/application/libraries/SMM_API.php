<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMM Turk API Library
 * Integration with SMMFollows API
 */
class SMM_API {
    
    /** API URL */
    public $api_url = 'https://smmfollows.com/api/v2';
    
    /** Your API key */
    public $api_key = '8b8e733ae816f10b6955250ace84c019';
    
    /** CI Instance */
    protected $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('curl');
    }
    
    /**
     * Add order
     */
    public function order($data) {
        $post = array_merge(['key' => $this->api_key, 'action' => 'add'], $data);
        return json_decode((string)$this->connect($post));
    }
    
    /**
     * Get order status
     */
    public function status($order_id) {
        return json_decode(
            $this->connect([
                'key' => $this->api_key,
                'action' => 'status',
                'order' => $order_id
            ])
        );
    }
    
    /**
     * Get orders status
     */
    public function multiStatus($order_ids) {
        return json_decode(
            $this->connect([
                'key' => $this->api_key,
                'action' => 'status',
                'orders' => implode(",", (array)$order_ids)
            ])
        );
    }
    
    /**
     * Get services
     */
    public function services() {
        return json_decode(
            $this->connect([
                'key' => $this->api_key,
                'action' => 'services',
            ])
        );
    }
    
    /**
     * Refill order
     */
    public function refill($orderId) {
        return json_decode(
            $this->connect([
                'key' => $this->api_key,
                'action' => 'refill',
                'order' => $orderId,
            ])
        );
    }
    
    /**
     * Refill orders
     */
    public function multiRefill($orderIds) {
        return json_decode(
            $this->connect([
                'key' => $this->api_key,
                'action' => 'refill',
                'orders' => implode(',', $orderIds),
            ]),
            true
        );
    }
    
    /**
     * Get refill status
     */
    public function refillStatus($refillId) {
        return json_decode(
            $this->connect([
                'key' => $this->api_key,
                'action' => 'refill_status',
                'refill' => $refillId,
            ])
        );
    }
    
    /**
     * Get refill statuses
     */
    public function multiRefillStatus($refillIds) {
        return json_decode(
            $this->connect([
                'key' => $this->api_key,
                'action' => 'refill_status',
                'refills' => implode(',', $refillIds),
            ]),
            true
        );
    }
    
    /**
     * Cancel orders
     */
    public function cancel($orderIds) {
        return json_decode(
            $this->connect([
                'key' => $this->api_key,
                'action' => 'cancel',
                'orders' => implode(',', $orderIds),
            ]),
            true
        );
    }
    
    /**
     * Get balance
     */
    public function balance() {
        return json_decode(
            $this->connect([
                'key' => $this->api_key,
                'action' => 'balance',
            ])
        );
    }
    
    /**
     * Connect to API
     */
    private function connect($post) {
        $_post = [];
        if (is_array($post)) {
            foreach ($post as $name => $value) {
                $_post[] = $name . '=' . urlencode($value);
            }
        }

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if (is_array($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($result)) {
            $result = false;
        }
        curl_close($ch);
        return $result;
    }
    
    /**
     * Test API connection
     */
    public function test_connection() {
        $balance = $this->balance();
        if ($balance && isset($balance->balance)) {
            return array(
                'status' => 'success',
                'message' => 'API connection successful',
                'balance' => $balance->balance
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'API connection failed'
            );
        }
    }
    
    /**
     * Get service by ID
     */
    public function get_service($service_id) {
        $services = $this->services();
        if ($services && is_array($services)) {
            foreach ($services as $service) {
                if ($service->service == $service_id) {
                    return $service;
                }
            }
        }
        return false;
    }
    
    /**
     * Create order with validation
     */
    public function create_order($service_id, $link, $quantity, $additional_params = array()) {
        // Validate service
        $service = $this->get_service($service_id);
        if (!$service) {
            return array(
                'status' => 'error',
                'message' => 'Service not found'
            );
        }
        
        // Prepare order data
        $order_data = array(
            'service' => $service_id,
            'link' => $link,
            'quantity' => $quantity
        );
        
        // Add additional parameters
        if (!empty($additional_params)) {
            $order_data = array_merge($order_data, $additional_params);
        }
        
        // Create order
        $result = $this->order($order_data);
        
        if ($result && isset($result->order)) {
            return array(
                'status' => 'success',
                'order_id' => $result->order,
                'charge' => $result->charge ?? 0,
                'message' => 'Order created successfully'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => $result->error ?? 'Failed to create order'
            );
        }
    }
    
    /**
     * Get order details with status
     */
    public function get_order_details($order_id) {
        $status = $this->status($order_id);
        
        if ($status && isset($status->status)) {
            return array(
                'status' => 'success',
                'order_id' => $order_id,
                'status' => $status->status,
                'charge' => $status->charge ?? 0,
                'start_count' => $status->start_count ?? 0,
                'remains' => $status->remains ?? 0,
                'currency' => $status->currency ?? 'USD'
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Order not found or API error'
            );
        }
    }
}
