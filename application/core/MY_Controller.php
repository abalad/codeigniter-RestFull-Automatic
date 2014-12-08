<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class MY_Controller extends REST_Controller{


    /**
     * Name of Table DB
     * @var string
     */
	private $_name_table = '';


    /**
     * Constructor function
     */
    public function __construct($nameTable)
    { 
    	parent::__construct();
		$this->setNameTable($nameTable);
	}

    /**
     * Setter Name of Table 
     */
	private function setNameTable($name){
		$this->_name_table = $name;
	}

	public function getNameTable(){
		return $this->_name_table;
	}	


	public function _remap($method)
	{

		$methodHttp = strtolower($this->input->server('REQUEST_METHOD'));

		if (method_exists($this, $method.'_'.$methodHttp)){
			$this->{$method.'_'.$methodHttp}();
		}else{

			if ($method == 'index'){
				$this->{'apiAll_'.$methodHttp}();
			}		
		    	$this->{'api_'.$methodHttp}();
	    }	

	}

    function api_get()
    {
        if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        } 
    	$query = $this->db->get_where($this->_name_table, array('id' => $this->get('id')));

        if($query)
        {
            $this->response($query->result(), 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Registro não Encontrado'), 404);
        }

    }

    function api_post()
    {

        $query = $this->db->insert($this->_name_table, $this->post()); 

        if($query)
        {
            $this->response($query->result(), 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Não pode incluir este registro'), 404);
        }
    }

    function api_put()
    {
        $query = $this->db->update($this->_name_table, $this->put(), array('id' => $this->get('id')));

        if($query)
        {
            $this->response($query->result(), 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Não pode Alterar este registro'), 404);
        }
    }    
    
    function api_delete()
    {
        $query = $this->db->delete($this->_name_table, array('id' => $this->delete('id')));

        if($query)
        {
            $this->response($query->result(), 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Não pode Deletar este registro'), 404);
        }

    }
    
    function apiAll_get()
    {
    	$query = $this->db->get($this->_name_table);

        if($query)
        {
            $this->response($query->result(), 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Registros não Encontrado'), 404);
        }
	}
}