<?php
require(APPPATH'.libraries/REST_Controller.php');
 
class Example_Api extends REST_Controller {
    function users_get()
    {
        // respond with information all users
    }

    function user_get()
    {
        $data = array('returned: '. $this->get('id'));
        $this->response($data);
    }
     
    function user_post()
    {       
        $data = array('returned: '. $this->post('id'));
        $this->response($data);
    }
 
    function user_put()
    {       
        $data = array('returned: '. $this->put('id'));
        $this->response($data;
    }
 
    function user_delete()
    {
        $data = array('returned: '. $this->delete('id'));
        $this->response($data);
    }
}