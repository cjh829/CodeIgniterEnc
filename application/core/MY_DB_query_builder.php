<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_DB_query_builder extends CI_DB_query_builder {

    /**
	 * Insert_Ignore_Batch
	 *
	 * Compiles batch insert ignore strings and runs the queries
	 *
	 * @param	string	$table	Table to insert into
	 * @param	array	$set 	An associative array of insert values
	 * @param	bool	$escape	Whether to escape values and identifiers
	 * @return	int	Number of rows inserted or FALSE on failure
	 */
	public function insert_ignore_batch($table, $set = NULL, $escape = NULL, $batch_size = 100)
	{
		if ($set === NULL)
		{
			if (empty($this->qb_set))
			{
				return ($this->db_debug) ? $this->display_error('db_must_use_set') : FALSE;
			}
		}
		else
		{
			if (empty($set))
			{
				return ($this->db_debug) ? $this->display_error('insert_ignore_batch() called with no data') : FALSE;
			}

			$this->set_insert_batch($set, '', $escape);
		}

		if (strlen($table) === 0)
		{
			if ( ! isset($this->qb_from[0]))
			{
				return ($this->db_debug) ? $this->display_error('db_must_set_table') : FALSE;
			}

			$table = $this->qb_from[0];
		}

		// Batch this baby
		$affected_rows = 0;
		for ($i = 0, $total = count($this->qb_set); $i < $total; $i += $batch_size)
		{
			if ($this->query($this->_replace_batch($this->protect_identifiers($table, TRUE, $escape, FALSE), $this->qb_keys, array_slice($this->qb_set, $i, $batch_size))))
			{
				$affected_rows += $this->affected_rows();
			}
		}

		$this->_reset_write();
		return $affected_rows;
	}

	// --------------------------------------------------------------------

	/**
	 * Insert ignore batch statement
	 *
	 * Generates a platform-specific insert ignore string from the supplied data.
	 *
	 * @param	string	$table	Table name
	 * @param	array	$keys	INSERT keys
	 * @param	array	$values	INSERT values
	 * @return	string
	 */
	protected function _insert_ignore_batch($table, $keys, $values)
	{
		return 'INSERT IGNORE INTO '.$table.' ('.implode(', ', $keys).') VALUES '.implode(', ', $values);
	}

    /**
	 * Replace_Batch
	 *
	 * Compiles batch replace strings and runs the queries
	 *
	 * @param	string	$table	Table to insert into
	 * @param	array	$set 	An associative array of insert values
	 * @param	bool	$escape	Whether to escape values and identifiers
	 * @return	int	Number of rows inserted or FALSE on failure
	 */
	public function replace_batch($table, $set = NULL, $escape = NULL, $batch_size = 100)
	{
		if ($set === NULL)
		{
			if (empty($this->qb_set))
			{
				return ($this->db_debug) ? $this->display_error('db_must_use_set') : FALSE;
			}
		}
		else
		{
			if (empty($set))
			{
				return ($this->db_debug) ? $this->display_error('replace_batch() called with no data') : FALSE;
			}

			$this->set_insert_batch($set, '', $escape);
		}

		if (strlen($table) === 0)
		{
			if ( ! isset($this->qb_from[0]))
			{
				return ($this->db_debug) ? $this->display_error('db_must_set_table') : FALSE;
			}

			$table = $this->qb_from[0];
		}

		// Batch this baby
		$affected_rows = 0;
		for ($i = 0, $total = count($this->qb_set); $i < $total; $i += $batch_size)
		{
			if ($this->query($this->_replace_batch($this->protect_identifiers($table, TRUE, $escape, FALSE), $this->qb_keys, array_slice($this->qb_set, $i, $batch_size))))
			{
				$affected_rows += $this->affected_rows();
			}
		}

		$this->_reset_write();
		return $affected_rows;
	}

	// --------------------------------------------------------------------

	/**
	 * Replace batch statement
	 *
	 * Generates a platform-specific replace string from the supplied data.
	 *
	 * @param	string	$table	Table name
	 * @param	array	$keys	INSERT keys
	 * @param	array	$values	INSERT values
	 * @return	string
	 */
	protected function _replace_batch($table, $keys, $values)
	{
		return 'REPLACE INTO '.$table.' ('.implode(', ', $keys).') VALUES '.implode(', ', $values);
	}

    public function paginate($table= '', $page=1 , $perpage= 20){
        if (empty($page) || $page < 1)
            $page = 1;
        if (empty($perpage))
            $perpage = 20;

        $total = $this->count_all_results($table, FALSE);

        $lastPage = intval(ceil($total/$perpage));

        $prevPage = $page-1;
        if ($prevPage <1)
            $prevPage = null;
        
        $nextPage = $page+1;
        if ($nextPage >= $lastPage)
            $nextPage = null;

        $from = ($page-1)*$perpage + 1;
        $to = $from + $perpage - 1; 

        $result = $this->limit($perpage, ($page-1)*$perpage)
                        ->get();
        
        $result->paginateInfo = [
                'total' => $total
                ,'current_page'=> $page
                ,'per_page' => $perpage
                ,'last_page' => $lastPage
                ,'prev_page' => $prevPage
                ,'next_page' => $nextPage
                ,'from' => $from
                ,'to' => $to
                ];
        return $result;
    }

	// --------------------------------------------------------------------

	//overwrite for sql logging

	/**
	 * Execute the query
	 *
	 * Accepts an SQL string as input and returns a result object upon
	 * successful execution of a "read" type query. Returns boolean TRUE
	 * upon successful execution of a "write" type query. Returns boolean
	 * FALSE upon failure, and if the $db_debug variable is set to TRUE
	 * will raise an error.
	 *
	 * @param	string	$sql
	 * @param	array	$binds = FALSE		An array of binding data
	 * @param	bool	$return_object = NULL
	 * @return	mixed
	 */
	public function query($sql, $binds = FALSE, $return_object = NULL)
	{
		if ($sql === '')
		{
			log_message('error', 'Invalid query: '.$sql);
			return ($this->db_debug) ? $this->display_error('db_invalid_query') : FALSE;
		}
		elseif ( ! is_bool($return_object))
		{
			$return_object = ! $this->is_write_type($sql);
		}

		// Verify table prefix and replace if necessary
		if ($this->dbprefix !== '' && $this->swap_pre !== '' && $this->dbprefix !== $this->swap_pre)
		{
			$sql = preg_replace('/(\W)'.$this->swap_pre.'(\S+?)/', '\\1'.$this->dbprefix.'\\2', $sql);
		}

		// Compile binds if needed
		if ($binds !== FALSE)
		{
			$sql = $this->compile_binds($sql, $binds);
		}

		// Is query caching enabled? If the query is a "read type"
		// we will load the caching class and return the previously
		// cached query if it exists
		if ($this->cache_on === TRUE && $return_object === TRUE && $this->_cache_init())
		{
			$this->load_rdriver();
			if (FALSE !== ($cache = $this->CACHE->read($sql)))
			{
				return $cache;
			}
		}

		// Save the query for debugging
		if ($this->save_queries === TRUE)
		{
			$this->queries[] = $sql;
			// TODO: log sql
		}

		// Start the Query Timer
		$time_start = microtime(TRUE);

		// Run the Query
		if (FALSE === ($this->result_id = $this->simple_query($sql)))
		{
			if ($this->save_queries === TRUE)
			{
				$this->query_times[] = 0;
			}

			// This will trigger a rollback if transactions are being used
			if ($this->_trans_depth !== 0)
			{
				$this->_trans_status = FALSE;
			}

			// Grab the error now, as we might run some additional queries before displaying the error
			$error = $this->error();

			// Log errors
			log_message('error', 'Query error: '.$error['message'].' - Invalid query: '.$sql);

			if ($this->db_debug)
			{
				// We call this function in order to roll-back queries
				// if transactions are enabled. If we don't call this here
				// the error message will trigger an exit, causing the
				// transactions to remain in limbo.
				while ($this->_trans_depth !== 0)
				{
					$trans_depth = $this->_trans_depth;
					$this->trans_complete();
					if ($trans_depth === $this->_trans_depth)
					{
						log_message('error', 'Database: Failure during an automated transaction commit/rollback!');
						break;
					}
				}

				// Display errors
				return $this->display_error(array('Error Number: '.$error['code'], $error['message'], $sql));
			}

			return FALSE;
		}

		// Stop and aggregate the query time results
		$time_end = microtime(TRUE);
		$this->benchmark += $time_end - $time_start;

		if ($this->save_queries === TRUE)
		{
			$this->query_times[] = $time_end - $time_start;
		}

		// Increment the query counter
		$this->query_count++;

		// Will we have a result object instantiated? If not - we'll simply return TRUE
		if ($return_object !== TRUE)
		{
			// If caching is enabled we'll auto-cleanup any existing files related to this particular URI
			if ($this->cache_on === TRUE && $this->cache_autodel === TRUE && $this->_cache_init())
			{
				$this->CACHE->delete();
			}

			return TRUE;
		}

		// Load and instantiate the result driver
		$driver		= $this->load_rdriver();
		$RES		= new $driver($this);

		// Is query caching enabled? If so, we'll serialize the
		// result object and save it to a cache file.
		if ($this->cache_on === TRUE && $this->_cache_init())
		{
			// We'll create a new instance of the result object
			// only without the platform specific driver since
			// we can't use it with cached data (the query result
			// resource ID won't be any good once we've cached the
			// result object, so we'll have to compile the data
			// and save it)
			$CR = new CI_DB_result($this);
			$CR->result_object	= $RES->result_object();
			$CR->result_array	= $RES->result_array();
			$CR->num_rows		= $RES->num_rows();

			// Reset these since cached objects can not utilize resource IDs.
			$CR->conn_id		= NULL;
			$CR->result_id		= NULL;

			$this->CACHE->write($sql, $CR);
		}

		return $RES;
	}

}