## CodeIgniter RestFull Automatic


A full implementation Restfull GET, PUT, POST, DELETE, automatic

## Requirements

1. PHP 5.2 or greater
2. CodeIgniter 2.1.0 to 3.0-dev

_Note: for 1.7.x support download v2.2 from Downloads tab_

## Installation

Drag and drop the **application/libraries/Format.php** and **application/libraries/REST_Controller.php** files into your application's directories. To use `require_once` it at the top of your controllers to load it into the scope. Additionally, copy the **rest.php** file from **application/config** in your application's configuration directory. More information visit 

The author chriskacerguis: https://github.com/chriskacerguis/codeigniter-restserver

## Handling Requests

To make requests, you must create within the Implementation Legislation application / controllers / api / a file with the name of the Table, the database to which you want to capture or handle the data.

Once this is done, create a class following the pattern of CodeIgniter, with the following code:

	class NameTable extends REST_Controller
	{
	    //Constructor Default
	    public function __construct()
	    {
	    	parent::__construct( get_class($this) );

	    }
	}

There, only with this code, you already will have a Restfull API, with GET, INSERT, DELETE, UPDATE.

You can create new methods in this class following the pattern of our friend chriskacerguis, where it has a very good API, where it was developed using his work.	
