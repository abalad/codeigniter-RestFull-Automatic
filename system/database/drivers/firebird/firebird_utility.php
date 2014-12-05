<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2010, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Firebird Utility Class
 *
 * @category	Database
 * @author		ExpressionEngine Dev Team
 * @modified Carlos Garcia Trujilo <http://cgarcia.blogspot.com>
 * @link		http://codeigniter.com/user_guide/database/
 */
class CI_DB_Firebird_utility extends CI_DB_utility {

    /**
     * List databases
     *
     * @access	private
     * @return	bool
     */
    function _list_databases() {
        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Optimize table query
     *
     * Is table optimization supported in firebird?
     *
     * @access	private
     * @param	string	the table name
     * @return	object
     */
    function _optimize_table($table) {
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Repair table query
     *
     * Are table repairs supported in firebird?
     *
     * @access	private
     * @param	string	the table name
     * @return	object
     */
    function _repair_table($table) {
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * firebird Export
     *
     * @access	private
     * @param	array	Preferences
     * @return	mixed
     */
    function _backup($params = array()) {
        // Currently unsupported
        return $this->db->display_error('db_unsuported_feature');
    }

    /**
     *
     * The functions below have been deprecated as of 1.6, and are only here for backwards
     * compatibility.  They now reside in dbforge().  The use of dbutils for database manipulation
     * is STRONGLY discouraged in favour if using dbforge.
     *
     */

    /**
     * Create database
     *
     * @access	private
     * @param	string	the database name
     * @return	bool
     */
    function _create_database($name) {
        return "CREATE DATABASE " . $name;
    }

    // --------------------------------------------------------------------

    /**
     * Drop database
     *
     * @access	private
     * @param	string	the database name
     * @return	bool
     */
    function _drop_database($name) {
        return "DROP DATABASE " . $name;
    }

}

/* End of file firebird_utility.php */
/* Location: ./system/database/drivers/firebird/firebird_utility.php */