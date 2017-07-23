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

}