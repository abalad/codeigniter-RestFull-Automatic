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
        //$this->some_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }

    function api_put()
    {
        //$this->some_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'EDITED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }    
    
    function api_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
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