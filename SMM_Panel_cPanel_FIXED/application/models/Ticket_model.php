<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ticket Model for SMM Turk
 */
class Ticket_model extends SMM_Model {
    
    protected $table = 'tickets';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Create new ticket
     */
    public function create_ticket($user_id, $subject, $priority = 'medium') {
        $ticket_data = array(
            'user_id' => $user_id,
            'subject' => $subject,
            'priority' => $priority,
            'status' => 'open',
            'created_at' => date('Y-m-d H:i:s')
        );
        
        return $this->insert($ticket_data);
    }
    
    /**
     * Get user tickets
     */
    public function get_user_tickets($user_id, $limit = 20, $offset = 0) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->get_all();
    }
    
    /**
     * Get ticket with messages
     */
    public function get_ticket_with_messages($ticket_id) {
        // Get ticket details
        $this->db->select('
            tickets.*,
            users.username,
            users.email
        ');
        $this->db->from('tickets');
        $this->db->join('users', 'users.id = tickets.user_id');
        $this->db->where('tickets.id', $ticket_id);
        $ticket = $this->db->get()->row();
        
        if (!$ticket) {
            return false;
        }
        
        // Get messages
        $this->db->select('
            ticket_messages.*,
            users.username,
            users.role
        ');
        $this->db->from('ticket_messages');
        $this->db->join('users', 'users.id = ticket_messages.user_id');
        $this->db->where('ticket_messages.ticket_id', $ticket_id);
        $this->db->order_by('ticket_messages.created_at', 'ASC');
        $messages = $this->db->get()->result();
        
        $ticket->messages = $messages;
        
        return $ticket;
    }
    
    /**
     * Add message to ticket
     */
    public function add_message($ticket_id, $user_id, $message, $is_admin = false) {
        $message_data = array(
            'ticket_id' => $ticket_id,
            'user_id' => $user_id,
            'message' => $message,
            'is_admin' => $is_admin ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        // Update ticket status and timestamp
        $this->update($ticket_id, array(
            'status' => $is_admin ? 'in_progress' : 'open',
            'updated_at' => date('Y-m-d H:i:s')
        ));
        
        return $this->db->insert('ticket_messages', $message_data);
    }
    
    /**
     * Update ticket status
     */
    public function update_status($ticket_id, $status) {
        return $this->update($ticket_id, array(
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }
    
    /**
     * Get tickets by status
     */
    public function get_by_status($status, $limit = 20) {
        $this->db->select('
            tickets.*,
            users.username,
            users.email
        ');
        $this->db->from('tickets');
        $this->db->join('users', 'users.id = tickets.user_id');
        $this->db->where('tickets.status', $status);
        $this->db->order_by('tickets.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get tickets by priority
     */
    public function get_by_priority($priority, $limit = 20) {
        $this->db->select('
            tickets.*,
            users.username,
            users.email
        ');
        $this->db->from('tickets');
        $this->db->join('users', 'users.id = tickets.user_id');
        $this->db->where('tickets.priority', $priority);
        $this->db->order_by('tickets.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get ticket statistics
     */
    public function get_ticket_stats() {
        $this->db->select('
            COUNT(*) as total_tickets,
            COUNT(CASE WHEN status = "open" THEN 1 END) as open_tickets,
            COUNT(CASE WHEN status = "in_progress" THEN 1 END) as in_progress_tickets,
            COUNT(CASE WHEN status = "resolved" THEN 1 END) as resolved_tickets,
            COUNT(CASE WHEN status = "closed" THEN 1 END) as closed_tickets,
            COUNT(CASE WHEN priority = "urgent" THEN 1 END) as urgent_tickets,
            COUNT(CASE WHEN priority = "high" THEN 1 END) as high_priority_tickets
        ');
        $this->db->from('tickets');
        
        return $this->db->get()->row();
    }
    
    /**
     * Get recent tickets
     */
    public function get_recent_tickets($limit = 10) {
        $this->db->select('
            tickets.*,
            users.username
        ');
        $this->db->from('tickets');
        $this->db->join('users', 'users.id = tickets.user_id');
        $this->db->order_by('tickets.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Search tickets
     */
    public function search_tickets($search_term) {
        $this->db->select('
            tickets.*,
            users.username,
            users.email
        ');
        $this->db->from('tickets');
        $this->db->join('users', 'users.id = tickets.user_id');
        $this->db->like('tickets.subject', $search_term);
        $this->db->or_like('users.username', $search_term);
        $this->db->or_like('users.email', $search_term);
        $this->db->order_by('tickets.created_at', 'DESC');
        
        return $this->db->get()->result();
    }
    
    /**
     * Get unread ticket count for user
     */
    public function get_unread_count($user_id) {
        $this->db->select('COUNT(*) as unread_count');
        $this->db->from('tickets');
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 'open');
        
        $result = $this->db->get()->row();
        return $result ? $result->unread_count : 0;
    }
}
