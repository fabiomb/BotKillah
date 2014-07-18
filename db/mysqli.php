<?php
/***************************************************************************
 *                                 mysqli.php
 *                            -------------------
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : supportphpbb.com
 *
 * @package dbal
* @version $Id: mysqli.php,v 1.19 2006/08/11 21:52:45 davidmj Exp $
* @copyright (c) 2005 phpBB Group 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if(!defined("SQL_LAYER"))
{

define("SQL_LAYER","mysqli");

class sql_db
{

	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $in_transaction = 0;

	//
	// Constructor
	//
	function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $port = false, $persistency = true)
	{
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;
		$port = (!$port) ? NULL : $port;

		// Persistant connections not supported by the mysqli extension?
		$this->db_connect_id = @mysqli_connect($this->server, $this->user, $sqlpassword, $this->dbname, $port);
		
		if ($this->db_connect_id && $this->dbname != '')
		{
			if (@mysqli_select_db($this->db_connect_id, $this->dbname))
			{
                                mysqli_set_charset($this->db_connect_id, "utf8");
				return $this->db_connect_id;
			}
		}         
                mysqli_set_charset($this->db_connect_id, "utf8");
		return $this->sql_error('');
	}

	function sql_query($query = '', $cache_ttl = 0)
	{
            
		if ($query != '')
		{
			global $cache;
                        
			// EXPLAIN only in extra debug mode
			if (defined('DEBUG_EXTRA'))
			{
				$this->sql_report('start', $query);
			}

			$this->query_result = ($cache_ttl && method_exists($cache, 'sql_load')) ? $cache->sql_load($query) : false;
			//$this->sql_add_num_queries($this->query_result);

			if (!$this->query_result)
			{
                            $this->db_connect_id->set_charset("utf8");
                            //echo $this->db_connect_id->character_set_name();
                            
				if (($this->query_result = @mysqli_query($this->db_connect_id, $query)) === false)
				{
					$this->sql_error($query);
				}

				if (defined('DEBUG_EXTRA'))
				{
					$this->sql_report('stop', $query);
				}

				if ($cache_ttl && method_exists($cache, 'sql_save'))
				{
					$cache->sql_save($query, $this->query_result, $cache_ttl);
				}
			}
			else if (defined('DEBUG_EXTRA'))
			{
				$this->sql_report('fromcache', $query);
			}
		}
		else
		{
			return false;
		}

		return ($this->query_result) ? $this->query_result : false;
	}

	/**
	* Build LIMIT query
	*/
	function sql_query_limit($query, $total, $offset = 0, $cache_ttl = 0) 
	{
		if ($query != '')
		{
			$this->query_result = false;

			// if $total is set to 0 we do not want to limit the number of rows
			if ($total == 0)
			{
				// MySQL 4.1+ no longer supports -1 in limit queries
				$total = '18446744073709551615';
			}

			$query .= "\n LIMIT " . ((!empty($offset)) ? $offset . ', ' . $total : $total);

			return $this->sql_query($query, $cache_ttl);
		}
		else
		{
			return false;
		}
	}

	/**
	* Return number of rows
	* Not used within core code
	*/
	function sql_numrows($query_id = false)
	{
		global $cache;

		if (!$query_id)
		{
			$query_id = $this->query_result;
		}

		if (!is_object($query_id) && isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_numrows($query_id);
		}

		return ($query_id) ? @mysqli_num_rows($query_id) : false;
	}

	/**
	* Return number of affected rows
	*/
	function sql_affectedrows()
	{
		return ($this->db_connect_id) ? @mysqli_affected_rows($this->db_connect_id) : false;
	}

	/**
	* Fetch current row
	*/
	function sql_fetchrow($query_id = false)
	{
		global $cache;

		if (!$query_id)
		{
			$query_id = $this->query_result;
		}

		if (!is_object($query_id) && isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_fetchrow($query_id);
		}

		return ($query_id) ? @mysqli_fetch_assoc($query_id) : false;
	}

	/**
	* Fetch field
	* if rownum is false, the current row is used, else it is pointing to the row (zero-based)
	*/
	function sql_fetchfield($field, $rownum = false, $query_id = false)
	{
		global $cache;

		if (!$query_id)
		{
			$query_id = $this->query_result;
		}

		if ($query_id)
		{
			if ($rownum !== false)
			{
				$this->sql_rowseek($rownum, $query_id);
			}

			if (!is_object($query_id) && isset($cache->sql_rowset[$query_id]))
			{
				return $cache->sql_fetchfield($query_id, $field);
			}

			$row = $this->sql_fetchrow($query_id);
			return isset($row[$field]) ? $row[$field] : false;
		}

		return false;
	}

	/**
	* Seek to given row number
	* rownum is zero-based
	*/
	function sql_rowseek($rownum, $query_id = false)
	{
		global $cache;

		if (!$query_id)
		{
			$query_id = $this->query_result;
		}

		if (!is_object($query_id) && isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_rowseek($query_id, $rownum);
		}

		return ($query_id) ? @mysqli_data_seek($query_id, $rownum) : false;
	}

	/**
	* Get last inserted id after insert statement
	*/
	function sql_nextid()
	{
		return ($this->db_connect_id) ? @mysqli_insert_id($this->db_connect_id) : false;
	}

	/**
	* Free sql result
	*/
	function sql_freeresult($query_id = false)
	{
		global $cache;

		if (!$query_id)
		{
			$query_id = $this->query_result;
		}

		if (!is_object($query_id) && isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_freeresult($query_id);
		}

		return @mysqli_free_result($query_id);
	}

	/**
	* Escape string used in sql query
	*/
	function sql_escape($msg)
	{
		return @mysqli_real_escape_string($this->db_connect_id, $msg);
	}

	/**
	* Build db-specific query data
	* @access: private
	*/
	function _sql_custom_build($stage, $data)
	{
		switch ($stage)
		{
			case 'FROM':
				$data = '(' . $data . ')';
			break;
		}

		return $data;
	}

	/**
	* return sql error array
	* @access: private
	*/
	function sql_error()
	{
		if (!$this->db_connect_id)
		{
			return array(
				'message'	=> @mysqli_connect_error(),
				'code'		=> @mysqli_connect_errno()
			);
		}

		return array(
			'message'	=> @mysqli_error($this->db_connect_id),
			'code'		=> @mysqli_errno($this->db_connect_id)
		);
	}

	/**
	* Close sql connection
	* @access: private
	*/
	function _sql_close()
	{
		return @mysqli_close($this->db_connect_id);
	}

	/**
	* Build db-specific report
	* @access: private
	*/
	function _sql_report($mode, $query = '')
	{
		switch ($mode)
		{
			case 'start':

				$explain_query = $query;
				if (preg_match('/UPDATE ([a-z0-9_]+).*?WHERE(.*)/s', $query, $m))
				{
					$explain_query = 'SELECT * FROM ' . $m[1] . ' WHERE ' . $m[2];
				}
				else if (preg_match('/DELETE FROM ([a-z0-9_]+).*?WHERE(.*)/s', $query, $m))
				{
					$explain_query = 'SELECT * FROM ' . $m[1] . ' WHERE ' . $m[2];
				}

				if (preg_match('/^SELECT/', $explain_query))
				{
					$html_table = false;

					if ($result = @mysqli_query($this->db_connect_id, "EXPLAIN $explain_query"))
					{
						while ($row = @mysqli_fetch_assoc($result))
						{
							$html_table = $this->sql_report('add_select_row', $query, $html_table, $row);
						}
					}
					@mysqli_free_result($result);

					if ($html_table)
					{
						$this->html_hold .= '</table>';
					}
				}

			break;

			case 'fromcache':
				$endtime = explode(' ', microtime());
				$endtime = $endtime[0] + $endtime[1];

				$result = @mysqli_query($this->db_connect_id, $query);
				while ($void = @mysqli_fetch_assoc($result))
				{
					// Take the time spent on parsing rows into account
				}
				@mysqli_free_result($result);

				$splittime = explode(' ', microtime());
				$splittime = $splittime[0] + $splittime[1];

				$this->sql_report('record_fromcache', $query, $endtime, $splittime);

			break;
		}
	}
        
        	/**
	* Add to query count
	*/
	function sql_add_num_queries($cached = false)
	{
		$this->num_queries['cached'] += ($cached !== false) ? 1 : 0;
		$this->num_queries['normal'] += ($cached !== false) ? 0 : 1;
		$this->num_queries['total'] += 1;
	}
}

} // if ... define

?>
