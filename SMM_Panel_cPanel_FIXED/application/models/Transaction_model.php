<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Transaction Model for SMM Turk
 */
class Transaction_model extends SMM_Model {
    
    protected $table = 'transactions';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Create new transaction
     */
    public function create_transaction($user_id, $type, $amount, $description = '', $payment_method = null) {
        $transaction_data = array(
            'user_id' => $user_id,
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'payment_method' => $payment_method,
            'transaction_id' => $this->generate_transaction_id(),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        );
        
        return $this->insert($transaction_data);
    }
    
    /**
     * Generate unique transaction ID
     */
    private function generate_transaction_id() {
        return 'TXN_' . time() . '_' . rand(1000, 9999);
    }
    
    /**
     * Get user transactions
     */
    public function get_user_transactions($user_id, $limit = 20, $offset = 0) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->get_all();
    }
    
    /**
     * Get transactions by type
     */
    public function get_by_type($type, $limit = 20) {
        $this->db->where('type', $type);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->get_all();
    }
    
    /**
     * Get transactions by status
     */
    public function get_by_status($status, $limit = 20) {
        $this->db->where('status', $status);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->get_all();
    }
    
    /**
     * Update transaction status
     */
    public function update_status($transaction_id, $status) {
        $this->db->where('transaction_id', $transaction_id);
        return $this->db->update($this->table, array(
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }
    
    /**
     * Get transaction statistics
     */
    public function get_transaction_stats($user_id = null) {
        $this->db->select('
            COUNT(*) as total_transactions,
            SUM(CASE WHEN type = "deposit" THEN amount ELSE 0 END) as total_deposits,
            SUM(CASE WHEN type = "withdrawal" THEN amount ELSE 0 END) as total_withdrawals,
            SUM(CASE WHEN type = "order" THEN amount ELSE 0 END) as total_orders,
            SUM(CASE WHEN type = "refund" THEN amount ELSE 0 END) as total_refunds,
            SUM(CASE WHEN type = "bonus" THEN amount ELSE 0 END) as total_bonus,
            COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_transactions,
            COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_transactions,
            COUNT(CASE WHEN status = "failed" THEN 1 END) as failed_transactions
        ');
        $this->db->from('transactions');
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        
        return $this->db->get()->row();
    }
    
    /**
     * Get recent transactions
     */
    public function get_recent_transactions($limit = 10) {
        $this->db->select('
            transactions.*,
            users.username
        ');
        $this->db->from('transactions');
        $this->db->join('users', 'users.id = transactions.user_id');
        $this->db->order_by('transactions.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get daily revenue
     */
    public function get_daily_revenue($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $this->db->select('SUM(amount) as daily_revenue');
        $this->db->from('transactions');
        $this->db->where('type', 'deposit');
        $this->db->where('status', 'completed');
        $this->db->like('created_at', $date);
        
        $result = $this->db->get()->row();
        return $result ? $result->daily_revenue : 0;
    }
    
    /**
     * Get monthly revenue
     */
    public function get_monthly_revenue($year = null, $month = null) {
        if (!$year) $year = date('Y');
        if (!$month) $month = date('m');
        
        $this->db->select('SUM(amount) as monthly_revenue');
        $this->db->from('transactions');
        $this->db->where('type', 'deposit');
        $this->db->where('status', 'completed');
        $this->db->where('YEAR(created_at)', $year);
        $this->db->where('MONTH(created_at)', $month);
        
        $result = $this->db->get()->row();
        return $result ? $result->monthly_revenue : 0;
    }
    
    /**
     * Search transactions
     */
    public function search_transactions($search_term, $user_id = null) {
        $this->db->select('
            transactions.*,
            users.username
        ');
        $this->db->from('transactions');
        $this->db->join('users', 'users.id = transactions.user_id');
        $this->db->like('transactions.transaction_id', $search_term);
        $this->db->or_like('transactions.description', $search_term);
        $this->db->or_like('users.username', $search_term);
        
        if ($user_id) {
            $this->db->where('transactions.user_id', $user_id);
        }
        
        $this->db->order_by('transactions.created_at', 'DESC');
        
        return $this->db->get()->result();
    }
}
