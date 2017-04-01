<?php
<<<<<<< HEAD

=======
//// 
>>>>>>> master
	class db_sql
	{
		var $database = "";
		var $server   = "";
		var $user     = "";
		var $password = "";
		var $link_id  = 0;
		var $query_id = 0;
		var $q_cache = array();
		
		function myconnect()
		{
			$this->link_id = @MYSQL_CONNECT($this->server, $this->user, $this->password);
			if (!$this->link_id)
			{
				die("Ошибка соединения с базой данных!");
			}
			$db_select = @mysql_select_db($this->database,$this->link_id);
			if (!$db_select)
			{
				die("Ошибка базы данных: ".mysql_error());
			}
			return $this->link_id;
		}
		
		function sql_query($query_statement)
		{
			global $query_count;
			$this->query_id = mysql_query($query_statement,$this->link_id);
			if(!$this->query_id)
			{
				die ("Ошибка:<br />Запрос: $query_statement<br />".mysql_error());
			}
			$query_count++;
			$this->test['q_cache'][] = $query_statement;
			return $this->query_id;
		}
		
		function query_array($query_statement)
		{
			$query_id = $this->sql_query($query_statement);
			$return_array = $this->fetch_array($query_id);
			
			$this->free_result($query_id);
			return $return_array;
		}
		
		function fetch_array($query_id=-1)
		{
			if ($query_id!=-1)
			{
				$this->query_id = $query_id;
			}
			$this->result = mysql_fetch_array($this->query_id);
			return $this->result;
		}
		
		function insert_id()
		{
			return mysql_insert_id($this->link_id);
		}
		
		function sql_fetch_row($query_statement)
		{
			$this->result = mysql_fetch_row($this->sql_query($query_statement));
			return $this->result;
		}
		
		function num_rows($query_id=-1)
		{
			if ($query_id!=-1)
			{
				$this->query_id = $query_id;
			}
			return mysql_num_rows($this->query_id);
		}
		
		function free_result($query_id=-1)
		{
			if ($query_id!=-1)
			{
				$this->query_id=$query_id;
		    }
			return @mysql_free_result($this->query_id);
		}
		
 		function closeSQL()
		{
			@mysql_close($this->link_id);
		}
	}
?>