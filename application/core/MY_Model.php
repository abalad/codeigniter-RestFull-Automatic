<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model{

    /**
     * Name of Table
     *
     * @access	public
     * @var string
     */
    public $table_name = '';


    /**
     * Primary Key of Table
     *
     * @access	public
     * @var string
     *
     */
    public $table_id = 'ID';


    /**
     * Constructor
     *
     * @access public
     */
    function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    function query(){
       return $this->db->get($this->table_name);
    }

    // --------------------------------------------------------------------

    function insert($data){
        return $this->db->insert($this->table_name, $data);
    }

    // --------------------------------------------------------------------

    function update($where,$data){
       return $this->db->update($this->table_name, $data,$where);
    }

    // --------------------------------------------------------------------
    
    function delete($where){
        return $this->db->delete($this->table_name, $where);
    }

    // --------------------------------------------------------------------
    
    function get_where(array $where){
        return $this->db->get_where($this->table_name,$where);
    }

    // --------------------------------------------------------------------

    function select($campos,array $where = NULL, $order_by = NULL){
        $this->db->select($campos);
        $this->db->from($this->table_name);

        if ((isset($where)) &&  (!$where == NULL)){
            $this->db->where($where);
        }

        if ((isset($order_by)) &&  (!$order_by == '')){
            $this->db->order_by($order_by);
        }else{
            $this->db->order_by($this->table_id);
        }

        return $this->db->get();
    }
       
    // --------------------------------------------------------------------

    function select_count($campo, $where = NULL){
        return $this->db->query('SELECT COUNT('.$campo.') FROM '.$this->table_name);
    }
}