<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMM Turk Main Model
 * Base model for all SMM operations
 */
class SMM_Model extends CI_Model {
    
    protected $table;
    protected $primary_key = 'id';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all records
     */
    public function get_all($where = array(), $limit = NULL, $offset = NULL) {
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Get single record by ID
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, array($this->primary_key => $id))->row();
    }
    
    /**
     * Get single record by conditions
     */
    public function get_by($where) {
        return $this->db->get_where($this->table, $where)->row();
    }
    
    /**
     * Insert new record
     */
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    
    /**
     * Update record
     */
    public function update($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Delete record
     */
    public function delete($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
    }
    
    /**
     * Count records
     */
    public function count($where = array()) {
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($this->table);
    }
    
    /**
     * Check if record exists
     */
    public function exists($where) {
        return $this->count($where) > 0;
    }
}
