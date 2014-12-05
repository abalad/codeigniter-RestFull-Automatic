<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

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
 * Firebird Database Adapter Class
 *
 * Note: _DB is an extender class that the app controller
 * creates dynamically based on whether the active record
 * class is being used or not.
 *
 * @package		CodeIgniter
 * @subpackage	Drivers
 * @category	Database
 * @author		ExpressionEngine Dev Team
 * @modified Carlos Garcia Trujilo <http://cgarcia.blogspot.com>
 * @link		http://codeigniter.com/user_guide/database/
 */
class CI_DB_Firebird_driver extends CI_DB {

    var $dbdriver = 'firebird';
    var $_escape_char = '';
    // clause and character used for LIKE escape sequences
    var $_like_escape_str = " ESCAPE '%s' ";
    var $_like_escape_chr = '!';

    /**
     * The syntax to count rows is slightly different across different
     * database engines, so this string appears in each driver and is
     * used for the count_all() and count_all_results() functions.
     */
    var $_count_string = "SELECT COUNT(*) AS ";
    var $_random_keyword = ' ASC';
    
    var $trans_active;

    // database specific random keyword
    /**
     * Connection String
     *
     * @access	private
     * @return	string
     */
    function _connect_string() {
        $components = array
            (
            'hostname' => 'host',
            'port' => 'port',
            'database' => 'dbname',
            'username' => 'user',
            'password' => 'password',
        );
        $connect_string = "";
        foreach ($components as $key => $val) {
            if (isset($this->$key) && $this->$key != '') {
                $connect_string .= " $val=" . $this->$key;
            }
        }
        return trim($connect_string);
    }

    // --------------------------------------------------------------------
    /**
     * Non-persistent database connection
     *
     * @access	private called by the base class
     * @return	resource
     */
    function db_connect() {
        $hoststring = $this->hostname . ':' . $this->database;
        return @ibase_connect($hoststring, $this->username, $this->password, $this->char_set,$this->dialect);
    }

    // --------------------------------------------------------------------
    /**
     * Persistent database connection
     *
     * @access	private called by the base class
     * @return	resource
     */
    function db_pconnect() {
        $hoststring = $this->hostname . ':' . $this->database;
        return @ibase_pconnect($hoststring, $this->username, $this->password, $this->char_set, $this->dialect);
    }

    // --------------------------------------------------------------------
    /**
     * Reconnect
     *
     * Keep / reestablish the db connection if no queries have been
     * sent for a length of time exceeding the server's idle timeout
     *
     * @access	public
     * @return	void
     */
    function reconnect() {
        if (ibase_ping($this->conn_id) === FALSE) {
            $this->conn_id = FALSE;
        }
    }

    // --------------------------------------------------------------------
    /**
     * Select the database
     *
     * @access	private called by the base class
     * @return	resource
     */
    function db_select() {
        // Not needed for firebird so we'll return TRUE
        return TRUE;
    }

    // --------------------------------------------------------------------
    /**
     * Set client character set
     *
     * @access	public
     * @param	string
     * @param	string
     * @return	resource
     */
    function db_set_charset($charset, $collation) {
        return $this->char_set;
    }

    // --------------------------------------------------------------------
    /**
     * Version number query string
     *
     * @access	public
     * @return	string
     */
    function _version() {
        if (($svc = ibase_service_attach($this->hostname, $this->username, $this->password)) != FALSE) {
            $ibase_info = ibase_server_info($svc, IBASE_SVC_SERVER_VERSION) . '/' . ibase_server_info($svc, IBASE_SVC_IMPLEMENTATION);
            ibase_service_detach($svc);
        } else {
            $ibase_info = 'Unable to Determine';
        }
        return $ibase_info;
    }

    // --------------------------------------------------------------------
    /**
     * Execute the query
     *
     * @access	private called by the base class
     * @param	string	an SQL query
     * @return	resource
     */
    function _execute($sql) {
        $sql = $this->_prep_query($sql);
        $res = @ibase_query(isset($this->trans_active) ? $this->trans_active : $this->conn_id, $sql);
        return $res;
    }

    // --------------------------------------------------------------------
    /**
     * Prep the query
     *
     * If needed, each database adapter can prep the query string
     *
     * @access	private called by execute()
     * @param	string	an SQL query
     * @return	string
     */
    function _prep_query($sql) {
        return $sql;
        //mainly here we are returning the same sql :-)
    }

    // --------------------------------------------------------------------
    /**
     * Begin Transaction
     *
     * @access	public
     * @return	bool
     */
    function trans_begin($test_mode = FALSE) {
        if (!$this->trans_enabled) {
            return TRUE;
        }
        // When transactions are nested we only begin/commit/rollback the outermost ones
        if ($this->_trans_depth > 0) {
            return TRUE;
        }
        // Reset the transaction failure flag.
        // If the $test_mode flag is set to TRUE transactions will be rolled back
        // even if the queries produce a successful result.
        $this->_trans_failure = ($test_mode === TRUE) ? TRUE : FALSE;
        
        if (isset($this->trans_active))
            return $this->trans_active;
        else {
            $this->trans_active = @ibase_trans($this->conn_id);
            return isset($this->trans_active);
        }
    }

    // --------------------------------------------------------------------
    /**
     * Commit Transaction
     *
     * @access	public
     * @return	bool
     */
    function trans_commit() {
        if (!$this->trans_enabled) {
            return TRUE;
        }
        // When transactions are nested we only begin/commit/rollback the outermost ones
        if ($this->_trans_depth > 0) {
            return TRUE;
        }
        @ibase_commit(isset($this->trans_active) ? $this->trans_active : $this->conn_id);
        unset($this->trans_active);
        return TRUE;
    }

    // --------------------------------------------------------------------
    /**
     * Rollback Transaction
     *
     * @access	public
     * @return	bool
     */
    function trans_rollback() {
        if (!$this->trans_enabled) {
            return TRUE;
        }
        // When transactions are nested we only begin/commit/rollback the outermost ones
        if ($this->_trans_depth > 0) {
            return TRUE;
        }
        @ibase_rollback(isset($this->trans_active) ? $this->trans_active : $this->conn_id);
        unset($this->trans_active);
        return TRUE;
    }

    // --------------------------------------------------------------------
    /**
     * Escape String
     *
     * @access	public
     * @param	string
     * @param	bool	whether or not the string will be used in a LIKE condition
     * @return	string
     */
    function escape_str($str, $like = FALSE) {
        return preg_replace('/[' . "'" . ']+/', "''", $str);
    }

    // --------------------------------------------------------------------
    /**
     * Affected Rows
     *
     * @access	public
     * @return	integer
     */
    function affected_rows() {
        return @ibase_affected_rows($this->result_id);
    }

    // --------------------------------------------------------------------
    /**
     * Insert ID
     *
     * @access	public
     * @return	integer
     */
    function insert_id() {
        // not supported in Firebird/Interbase
        return 0;
    }

    // --------------------------------------------------------------------
    /**
     * "Count All" query
     *
     * Generates a platform-specific query string that counts all records in
     * the specified database
     *
     * @access	public
     * @param	string
     * @return	string
     */
    function count_all($table = '') {
        if ($table == '') {
            return 0;
        }
        $s = $this->_count_string . ' NUMROWS ' . " FROM " . $this->_protect_identifiers($table, TRUE, NULL, FALSE);
        $query = $this->query($s);
        if ($query->num_rows() == 0) {
            return 0;
        }
        $row = $query->row();
        return (int) $row->NUMROWS;
    }

    /**
     * "Count All Results" query
     *
     * Generates a platform-specific query string that counts all records
     * returned by an Active Record query.
     *
     * @param	string
     * @return	string
     */
    function count_all_results($table = '') {
        if ($table == '') {
            return 0;
        }
        $s = $this->_compile_select($this->_count_string . $this->_protect_identifiers(' NUMROWS '). " FROM " . $this->_protect_identifiers($table, TRUE, NULL, FALSE));
        $query = $this->query($s);
        if ($query->num_rows() == 0) {
            return 0;
        }
        $row = $query->row();
        return (int) $row->NUMROWS;
    }

    // --------------------------------------------------------------------
    /**
     * Show table query
     *
     * Generates a platform-specific query string so that the table names can be fetched
     *
     * @access	private
     * @param	boolean
     * @return	string
     */
    function _list_tables($prefix_limit = FALSE) {
        $sql = 'SELECT RDB$RELATION_NAME FROM RDB$RELATIONS WHERE RDB$SYSTEM_FLAG = 0';
        if ($prefix_limit !== FALSE AND $this->dbprefix != '') {
            $sql .= ' AND RDB$RELATION_NAME LIKE "' . $this->escape_like_str($this->dbprefix) . '%" ' . sprintf($this->_like_escape_str, $this->_like_escape_chr);
        }
        return $sql;
    }

    // --------------------------------------------------------------------
    /**
     * Show column query
     *
     * Generates a platform-specific query string so that the column names can be fetched
     *
     * @access	public
     * @param	string	the table name
     * @return	string
     */
    function _list_columns($table = '') {
        $table = strtoupper($table);
        return 'SELECT rel_fld.rdb$field_name as FIELD_NAME FROM rdb$relations rel JOIN rdb$relation_fields rel_fld ON rel_fld.rdb$relation_name = rel.rdb$relation_name JOIN rdb$fields fld ON rel_fld.rdb$field_source = fld.rdb$field_name WHERE rel.rdb$relation_name = \'' . $table . '\' ORDER BY rel_fld.rdb$field_position, rel_fld.rdb$field_name';
    }

    // --------------------------------------------------------------------
    /**
     * Field data query
     *
     * Generates a platform-specific query so that the column data can be retrieved
     *
     * @access	public
     * @param	string	the table name
     * @return	object
     */
    function _field_data($table) {
        return "SELECT FIRST 1 * FROM " . $table;
    }

    // --------------------------------------------------------------------
    /**
     * The error message string
     *
     * @access	private
     * @return	string
     */
    function _error_message() {
        return ibase_errmsg();
    }

    // --------------------------------------------------------------------
    /**
     * The error message number
     *
     * @access	private
     * @return	integer
     */
    function _error_number() {
        return ibase_errcode();
    }

    // --------------------------------------------------------------------
    /**
     * Escape the SQL Identifiers
     *
     * This function escapes column and table names
     *
     * @access	private
     * @param	string
     * @return	string
     */
    function _escape_identifiers($item) {
        if ($this->_escape_char == '') {
            return $item;
        }
        $item = strtoupper($item);
        foreach ($this->_reserved_identifiers as $id) {
            if (strpos($item, '.' . $id) !== FALSE) {
                $str = $this->_escape_char . str_replace('.', $this->_escape_char . '.', $item);
                // remove duplicates if the user already included the escape
                return preg_replace('/[' . $this->_escape_char . ']+/', $this->_escape_char, $str);
            }
        }
        if (strpos($item, '.') !== FALSE) {
            $str = $this->_escape_char . str_replace('.', $this->_escape_char . '.' . $this->_escape_char, $item) . $this->_escape_char;
        } else {
            $str = $this->_escape_char . $item . $this->_escape_char;
        }
        // remove duplicates if the user already included the escape
        return preg_replace('/[' . $this->_escape_char . ']+/', $this->_escape_char, $str);
    }

    // --------------------------------------------------------------------
    /**
     * From Tables
     *
     * This function implicitly groups FROM tables so there is no confusion
     * about operator precedence in harmony with SQL standards
     *
     * @access	public
     * @param	type
     * @return	type
     */
    function _from_tables($tables) {
        if (!is_array($tables)) {
            $tables = array
                (
                $tables,
            );
        }
        return implode(', ', $tables);
    }

    // --------------------------------------------------------------------
    /**
     * Insert statement
     *
     * Generates a platform-specific insert string from the supplied data
     *
     * @access	public
     * @param	string	the table name
     * @param	array	the insert keys
     * @param	array	the insert values
     * @return	string
     */
    function _insert($table, $keys, $values) {
        return "INSERT INTO " . $table . " (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ")";
    }

    // --------------------------------------------------------------------
    /**
     * Update statement
     *
     * Generates a platform-specific update string from the supplied data
     *
     * @access	public
     * @param	string	the table name
     * @param	array	the update data
     * @param	array	the where clause
     * @param	array	the orderby clause
     * @param	array	the limit clause
     * @return	string
     */
    function _update($table, $values, $where, $orderby = array(), $limit = FALSE) {
        foreach ($values as $key => $val) {
            $valstr[] = $key . " = " . $val;
        }
        $limit = (!$limit) ? '' : ' ROWS ' . $limit;
        $orderby = (count($orderby) >= 1) ? ' ORDER BY ' . implode(", ", $orderby) : '';
        $sql = "UPDATE " . $table . " SET " . implode(', ', $valstr);
        $sql .= ($where != '' AND count($where) >= 1) ? " WHERE " . implode(" ", $where) : '';
        $sql .= $orderby . $limit;
        return $sql;
    }

    // --------------------------------------------------------------------
    /**
     * Truncate statement
     *
     * Generates a platform-specific truncate string from the supplied data
     * If the database does not support the truncate() command
     * This function maps to "DELETE FROM table"
     *
     * @access	public
     * @param	string	the table name
     * @return	string
     */
    function _truncate($table) {
        return "DELETE FROM " . $table;
    }

    // --------------------------------------------------------------------
    /**
     * Delete statement
     *
     * Generates a platform-specific delete string from the supplied data
     *
     * @access	public
     * @param	string	the table name
     * @param	array	the where clause
     * @param	string	the limit clause
     * @return	string
     */
    function _delete($table, $where = array(), $like = array(), $limit = FALSE) {
        $conditions = '';
        if (count($where) > 0 OR count($like) > 0) {
            $conditions = "\nWHERE ";
            $conditions .= implode("\n", $this->ar_where);
            if (count($where) > 0 && count($like) > 0) {
                $conditions .= " AND ";
            }
            $conditions .= implode("\n", $like);
        }
        $limit = (!$limit) ? '' : ' ROWS ' . $limit;
        return "DELETE FROM " . $table . $conditions . $limit;
    }

    // --------------------------------------------------------------------
    /**
     * Limit string
     *
     * Generates a platform-specific LIMIT clause
     *
     * @access	public
     * @param	string	the sql query string
     * @param	integer	the number of rows to limit the query to
     * @param	integer	the offset value
     * @return	string
     */
    function _limit($sql, $limit, $offset) {
        if ($offset == '') {
            $offset = 0;
        }
        $sql = substr_replace($sql, "select first $limit skip $offset ", stripos($sql, 'select'), 6);
        return $sql;
    }

    // --------------------------------------------------------------------
    /**
     * Close DB Connection
     *
     * @access	public
     * @param	resource
     * @return	void
     */
    function _close($conn_id) {
        @ibase_close($conn_id);
    }

    /**
     * Get the content of a blob field
     *
     * @access	public
     * @param	field
     * @return	string
     */
    function get_blob($field) {
        $s = '';
        if (isset($field)) {
            $blob_data = ibase_blob_info($field);
            $blob_hndl = ibase_blob_open($field);
            $s = ibase_blob_get($blob_hndl, $blob_data[0]);
            ibase_blob_close($blob_hndl);
        }
        return $s;
    }

}

/* End of file firebird_driver.php */
/* Location: ./system/database/drivers/firebird/firebird_driver.php */
