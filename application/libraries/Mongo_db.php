<?php
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	 * CodeIgniter MongoDB Active Record Access
	 *
	 * @author		Ting-Yu, Lyu , b790113g@gmail.com
	 * @copyright	Copyright (c) 2013, Ting-Yu Lyu. (Evan)
	 * @license		http://codeigniter.com/user_guide/license.html
	 * @link		
	 * @version		Version 1.0
	 */

class Mongo_db {
	
	private $CI;
	private $config_file = 'mongo_db';
	
	private $connection;
	private $connection_string;
	
	private $db;
	private $dbname ;
	private $collection;
	private $collectionname ;
	
	private $host;
	private $port;
	private $user;
	private $pass;
	
	private $persist;
	private $persist_key;
	
	private $selects = array();
	private $wheres = array();
	private $sorts = array();
	
	private $limit = 1000000;
	private $offset = 0;
	
	public function __construct() {
		if(!class_exists('Mongo')):
			show_error("The MongoDB PECL extension has not been installed or enabled", 500);
		endif;
		$this->CI =& get_instance();
		$this->connection_string();
		$this->connect();
		
		$this->dbname = 'admin' ;
		//$this->list_db();
	}
	
	public function list_db() {
		try {
			$return = array();
			$dbs = $this->connection->selectDB('admin')->command(array('listDatabases' => 1));
			foreach ($dbs['databases'] as $db) {
					$return[] = array(
						'dbname' => $db['name'] ,
						'sizeOnDisk' => (!$db['empty']) ? round($db['sizeOnDisk']/1000000) : 0 
					);
			}
			sort($return);
			return $return ;
		} catch(Exception $e) {
			show_error("Unable to list Mongo Databases: {$e->getMessage()}", 500);
		}
	}
	
	public function list_db_collection($dbname = '' ) {
		try {
			if($dbname=='') $dbname = $this->dbname ;
			$collection = array();
			$exc = $this->connection->selectDB($dbname)->execute('function (){ return db.getCollectionNames(); }');
			foreach($exc['retval'] as $table):
				$tableinfo = $this->connection->selectCollection($dbname, $table);
				$collection[] = array(
					"table_obj" => $tableinfo ,
					"table_name" => $table ,
					"table_count" => $tableinfo->count()	
				);
			endforeach;
			return $collection ;
		} catch(Exception $e) {
			show_error("Unable to list Collection: {$e->getMessage()}", 500);
		}
	}
	
	public function switch_db($database = '') {
		if(empty($database)):
			show_error("To switch MongoDB databases, a new database name must be specified", 500);
		endif;
		try {
			$this->dbname = $database;
			$this->db = $this->connection->{$this->dbname};
			return(TRUE);
		} catch(Exception $e) {
			show_error("Unable to switch Mongo Databases: {$e->getMessage()}", 500);
		}
	}
	
	public function switch_Collection($collection = '') {
		if(empty($collection)):
			show_error("To switch MongoDB collection, a new collection name must be specified", 500);
		endif;
		if($this->db==NULL):
			show_error("To switch MongoDB collection, please select database first.", 500);
		endif;
		try {
			$this->collectionname = $collection ;
			$this->collection = $this->connection->selectCollection($this->dbname,$this->collectionname);
			return(TRUE);
		} catch(Exception $e) {
			show_error("Unable to switch Mongo Collection: {$e->getMessage()}", 500);
		}
	}
	 
	public function select($includes = array(), $excludes = array()) {
	 	if(!is_array($includes))
	 		$includes = array();
	 	if(!is_array($excludes))
	 		$excludes = array();
	 	if(!empty($includes)):
	 		foreach($includes as $col):
	 			$this->selects[$col] = 1;
	 		endforeach;
	 	else:
	 		foreach($excludes as $col):
	 			$this->selects[$col] = 0;
	 		endforeach;
	 	endif;
		return($this);
	}
	 
	 public function where($wheres = array()) {
	 	foreach($wheres as $wh => $val):
	 		$this->wheres[$wh] = $val;
	 	endforeach;
	 	return($this);
	 }
	 
	 public function where_in($field = "", $in = array()) {
	 	$this->where_init($field);
	 	$this->wheres[$field]['$in'] = $in;
	 	return($this);
	 }
	 
	 public function where_not_in($field = "", $in = array()) {
	 	$this->where_init($field);
	 	$this->wheres[$field]['$nin'] = $in;
	 	return($this);
	 }
	 
	 public function where_gt($field = "", $x) {
	 	$this->where_init($field);
	 	$this->wheres[$field]['$gt'] = $x;
	 	return($this);
	 }

	 public function where_gte($field = "", $x) {
	 	$this->where_init($field);
	 	$this->wheres[$field]['$gte'] = $x;
	 	return($this);
	 }
	 
	 public function where_lt($field = "", $x) {
	 	$this->where_init($field);
	 	$this->wheres[$field]['$lt'] = $x;
	 	return($this);
	 }

	 public function where_lte($field = "", $x) {
	 	$this->where_init($field);
	 	$this->wheres[$field]['$lte'] = $x;
	 	return($this);
	 }
	 
	 public function where_between($field = "", $x, $y) {
	 	$this->where_init($field);
	 	$this->wheres[$field]['$gte'] = $x;
	 	$this->wheres[$field]['$lte'] = $y;
	 	return($this);
	 }
	 
	 public function where_between_ne($field = "", $x, $y) {
	 	$this->where_init($field);
	 	$this->wheres[$field]['$gt'] = $x;
	 	$this->wheres[$field]['$lt'] = $y;
	 	return($this);
	 }
	 
	 public function where_ne($field = "", $x) {
	 	$this->where_init($field);
	 	$this->wheres[$field]['$ne'] = $x;
	 	return($this);
	 }
	 
	 public function like($field = "", $value = "", $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE) {
	 	$field = (string) trim($field);
	 	$this->where_init($field);
	 	$value = (string) trim($value);
	 	$value = quotemeta($value);
	 	if($enable_start_wildcard !== TRUE):
	 		$value = "^" . $value;
	 	endif;
	 	if($enable_end_wildcard !== TRUE):
	 		$value .= "$";
	 	endif;
	 	$regex = "/$value/$flags";
	 	$this->wheres[$field] = new MongoRegex($regex);
	 	return($this);
	 }
	 
	 public function order_by($fields = array()) {
	 	foreach($fields as $col => $val):
	 		if($val == -1 || $val === FALSE || strtolower($val) == 'desc'):
	 			$this->sorts[$col] = -1; 
	 		else:
	 			$this->sorts[$col] = 1;
	 		endif;
	 	endforeach;
	 	return($this);
	 }
	 
	 public function limit($x = 100000) {
	 	if($x !== NULL && is_numeric($x) && $x >= 1)
	 		$this->limit = (int) $x;
	 	return($this);
	 }
	 
	 public function offset($x = 0) {
	 	if($x !== NULL && is_numeric($x) && $x >= 1)
	 		$this->offset = (int) $x;
	 	return($this);
	 }
	
	 public function get_where($where = array(), $limit = 99999) {
	 	return($this->where($where)->limit($limit)->get($this->collection));
	 }
	
	 public function get() {
	 	$results = array();
	 	$documents = $this->collection->find($this->wheres, $this->selects)->limit((int) $this->limit)->skip((int) $this->offset)->sort($this->sorts);
	 	
	 	$returns = array();
	 	foreach($documents as $doc):
	 		$returns[] = $doc;
	 	endforeach;
	 	return($returns);
	 }
	 
	 public function count() {
	 	$count = $this->collection->find($this->wheres)->limit((int) $this->limit)->skip((int) $this->offset)->count();
	 	$this->clear();
	 	return($count);
	 }
	
	 public function insert( $data = array()) {
	 	if(count($data) == 0 || !is_array($data))
	 		return "Nothing to insert into Mongo collection or insert is not an json";
	 	
	 	try {
	 		$insert = $this->db->{$this->collectionname}->insert($data, array('safe' => TRUE));
	 		if(isset($insert['ok']))
	 			return TRUE ;
	 		else
	 			return 'Something error.' ;
	 	} catch(MongoCursorException $e) {
	 		return "Insert of data into MongoDB failed: {$e->getMessage()}" ;
	 	}
	 	
	 }
	
	 public function update($setData = array()) {
	 	if(count($setData) == 0 || !is_array($setData))
	 		return 'Nothing to update in Mongo collection or update is not an array';
	 	try {
	 		$this->collection->update($this->wheres,$setData, array("upsert" => FALSE, 'safe' => TRUE, 'multiple' => FALSE));
	 		return(TRUE);
	 	} catch(MongoCursorException $e) {
	 		return "Update of data into MongoDB failed: {".$e->getMessage()."}";
	 	}
	 	
	 }
	
	 public function update_all($collection = "", $data = array()) {
	 	if(empty($collection))
	 		show_error("No Mongo collection selected to update", 500);
	 	if(count($data) == 0 || !is_array($data))
	 		show_error("Nothing to update in Mongo collection or update is not an array", 500);
	 	try {
	 		$this->db->{$collection}->update($this->wheres, array('$set' => $data), array('safe' => TRUE, 'multiple' => TRUE));
	 		return(TRUE);
	 	} catch(MongoCursorException $e) {
	 		show_error("Update of data into MongoDB failed: {$e->getMessage()}", 500);
	 	}
	 }
	 
	public function delete() {
		try {
	 		$this->collection->remove($this->wheres, array('safe' => TRUE, 'justOne' => TRUE));
	 		return(TRUE);
	 	} catch(MongoCursorException $e) {
	 		show_error("Delete of data into MongoDB failed: {$e->getMessage()}", 500);
	 	}
	 	
	}
	
	public function delete_all() {
	 	try {
	 		$this->collection->remove($this->wheres, array('safe' => TRUE, 'justOne' => FALSE));
	 		return(TRUE);
	 	} catch(MongoCursorException $e) {
	 		show_error("Delete of data into MongoDB failed: {$e->getMessage()}", 500);
	 	}
	 	
	}
	
	public function add_index($collection = "", $keys = array(), $options = array()) {
		if(empty($collection))
	 		show_error("No Mongo collection specified to add index to", 500);
	 	if(empty($keys) || !is_array($keys))
	 		show_error("Index could not be created to MongoDB Collection because no keys were specified", 500);

	 	foreach($keys as $col => $val):
	 		if($val == -1 || $val === FALSE || strtolower($val) == 'desc'):
	 			$keys[$col] = -1; 
	 		else:
	 			$keys[$col] = 1;
	 		endif;
	 	endforeach;
	 	
	 	if($this->db->{$collection}->ensureIndex($keys, $options) == TRUE):
	 		$this->clear();
	 		return($this);
	 	else:
	 		show_error("An error occured when trying to add an index to MongoDB Collection", 500);
		endif;
	}
	
	public function remove_index($collection = "", $keys = array()) {
		if(empty($collection))
	 		show_error("No Mongo collection specified to remove index from", 500);
	 	if(empty($keys) || !is_array($keys))
	 		show_error("Index could not be removed from MongoDB Collection because no keys were specified", 500);
	 	if($this->db->{$collection}->deleteIndex($keys, $options) == TRUE):
	 		$this->clear();
	 		return($this);
	 	else:
	 		show_error("An error occured when trying to remove an index from MongoDB Collection", 500);
		endif;
	}

	public function remove_all_indexes($collection = "") {
		if(empty($collection))
	 		show_error("No Mongo collection specified to remove all indexes from", 500);
	 	$this->db->{$collection}->deleteIndexes();
	 	$this->clear();
	 	return($this);
	}
	
	public function list_indexes($collection = "") {
		if(empty($collection))
	 		show_error("No Mongo collection specified to remove all indexes from", 500);
	 	return($this->db->{$collection}->getIndexInfo());
	}
	
	private function connect() {
		$options = array();
		if($this->persist === TRUE):
			$options['persist'] = isset($this->persist_key) && !empty($this->persist_key) ? $this->persist_key : 'ci_mongo_persist';
		endif;
		try {
			$this->connection = new Mongo($this->connection_string, $options);
			return($this);	
		} catch(MongoConnectionException $e) {
			show_error("Unable to connect to MongoDB: {$e->getMessage()}", 500);
		}
	}
	
	private function connection_string() {
		$this->CI->config->load($this->config_file);
		
		$this->host = trim($this->CI->config->item('mongo_host'));
		$this->port = trim($this->CI->config->item('mongo_port'));
		$this->user = trim($this->CI->config->item('mongo_user'));
		$this->pass = trim($this->CI->config->item('mongo_pass'));
		$this->dbname = trim($this->CI->config->item('mongo_db'));
		$this->persist = trim($this->CI->config->item('mongo_persist'));
		$this->persist_key = trim($this->CI->config->item('mongo_persist_key'));
		
		$connection_string = "mongodb://";
		
		if(empty($this->host)):
			show_error("The Host must be set to connect to MongoDB", 500);
		endif;
		/*
		if(empty($this->dbname)):
			show_error("The Database must be set to connect to MongoDB", 500);
		endif;
		*/
		if(!empty($this->user) && !empty($this->pass)):
			$connection_string .= "{$this->user}:{$this->pass}@";
		endif;
		
		if(isset($this->port) && !empty($this->port)):
			$connection_string .= "{$this->host}:{$this->port}";
		else:
			$connection_string .= "{$this->host}";
		endif;
		
		$this->connection_string = trim($connection_string);
	}
	
	private function clear() {
		$this->selects = array();
		$this->wheres = array();
		$this->limit = NULL;
		$this->offset = NULL;
		$this->sorts = array();
	}

	private function where_init($param) {
		if(!isset($this->wheres[$param]))
			$this->wheres[$param] = array();
	}
	
	public function getServerInfo()
	{
		$info = array() ;
		
		$db = $this->connection->selectDB("admin");
		
		//CMD
		$query = $db->command(array("getCmdLineOpts" => 1));
		$info[] = array(
			"name"=> "Command Line (db.serverCmdLineOpts())",
			"value"=> array(
				"Command"=> (isset($query["argv"])?implode(" ", $query["argv"]) : "" )
			)
		);
		
		
		//Web Server Info
		list($webServer) = explode(" ", $_SERVER["SERVER_SOFTWARE"]);
		$info[] = array(
			"name"=> "Web Server",
			"value"=> array(
				"Web Server"=> $webServer,
				"PHP Version"=>PHP_VERSION,
				"PHP extionsion"=>MongoClient::VERSION
			)
		);
		
		//build info
		$ret = $db->command(array("buildinfo" => 1));
		$info[] = array(
			"name"=>"build Info",
			"value"=>$ret
		);
		
		return $info;
	}
	
	public function getStatusInfo()
	{
		$info = array();
		$db = $this->connection->selectDB("admin");
		$svrStatus = $db->command(array("serverStatus" => 1));
		if ($svrStatus["ok"]) {
			unset($svrStatus['ok']);
			foreach ($svrStatus as $key=>$status) {
				$info[] = array(
					"name"=>$key,
					"value"=>$status
				);
			}
		}
		return $info;
	}
	
	public function getDatabasesInfo()
	{
		$info = array();
		$dbList = $this->list_db();
		foreach ($dbList as $key => $valar) {
			$ret = array();
			$mongodb = $this->connection->selectDB($valar["dbname"]);
			$ret = $mongodb->command(array("dbstats" => 1));
			$ret["collections"] = count($this->list_db_collection($valar['dbname']));
			$ret["diskSize"] = $valar["sizeOnDisk"];
			$ret["dataSize"] = $ret["dataSize"];
			$ret["storageSize"] = $ret["storageSize"];
			$ret["indexSize"] = $ret["indexSize"];
			unset($ret['ok']);
			$info[] = $ret ;
		}
		return $info;
	}
	
	public function getCurrentDatabasesInfo()
	{
		$ret = array();
		$mongodb = $this->connection->selectDB($this->dbname);
		$ret = $mongodb->command(array("dbstats" => 1));
		$ret["collections"] = $this->list_db_collection($this->dbname);
		$ret["dataSize"] = $ret["dataSize"];
		$ret["storageSize"] = $ret["storageSize"];
		$ret["indexSize"] = $ret["indexSize"];
		unset($ret['ok']);
		return $ret;
	}
	
	public function getProcessList()
	{
		$query = $this->connection->selectDB("admin")->execute('function (){
			return db.$cmd.sys.inprog.find({ $all:1 }).next();
		}');
		
		$progs = array();
		if ($query["ok"]) {
			$progs = $query["retval"]["inprog"];
		}
		foreach ($progs as $pgskey => $prog) {
			foreach ($prog as $pgkey=>$value) {
				if (is_array($pgskey)) {
					$progs[$pgsindex][$pgkey] = $value ;
				}
			}
		}
		return $progs;
	}
	
	public function runCommand($source,$dbs)
	{
		$ncmd = array();
		
		if($source==NULL) return 'Command(JSON format) is not correct.<br />Please check.';
		
		foreach ($source as $key=>$value)
			foreach((array)$value as $key2=>$value2)
				$ncmd[$key2] = $value2 ;
		
		$db = $this->connection->selectDB($dbs);
		$ret = $db->command($ncmd);
		if ($ret["ok"]) {
			return $ret;
		}
		return 'NULL';
	}
	
	public function runExecute($source,$dbs)
	{
		$db = $this->connection->selectDB($dbs);
		$ret = $db->execute($source);
		if ($ret["ok"]) {
			return $ret;
		}
		return 'NULL';
	}
	
	public function runRepairDatabase()
	{
		$db = $this->connection->selectDB($this->dbname);
		$ret = $db->command(array( "repairDatabase" => 1 ));
		if ($ret['ok'])
			return 'Repair database success.';
		return 'Repair database failed.';
	}
	
	public function getCurrentDBName()
	{
		return $this->dbname ;
	}
	
	public function getCurrentCollectionName()
	{
		return $this->collectionname ;
	}
	
	public function createCollection($_input){
		$name = $_input->post('collectionname');
		$isCapped = $_input->post('capped');
		$size = $_input->post('size');
		$max = $_input->post('max');
		
		if($name==''||!is_numeric($size)||!is_numeric($max))
			return 'Field must be checked.';
		
		if($isCapped=='1' && $size==0 && $max==0)
			return 'Capped Enable, Size and Max must > 0';
	
		$exc = @$this->db->createCollection($name, $isCapped, $size, $max);
		//add index
		if ($isCapped!='1')
			$this->db->selectCollection($name)->ensureIndex(array( "_id" => 1 ));
		
		if($exc)
			return 'New collection is created.';
		else
			return 'New collection is not created.';
	}
	
	public function clearDatabaseCollections()
	{
		foreach ($this->db->listCollections() as $collection) {
			$collection->remove();
		}
		return 'Clear database all collections success.' ;
	}
	
	public function dropDatabase()
	{
		$this->connection->dropDB($this->db);
	}
	
	public function newDatabase($_input)
	{
		$ndbname = $_input->post("ndbname");
		if(empty($ndbname))
			return 'Please input a valid database name.';
		
		$this->connection->selectDb($ndbname)->execute("function(){}");
		return 'New database created.';
	}
	
	public function addUser($_input)
	{
		$uname = $_input->post('uname');
		$upass = $_input->post('upass');
		$ucpass= $_input->post('ucpass');
		$dbsel = $_input->post('dbsel');
		
		if( empty($uname) )
			return 'You must supply a username for user.';
	
		if( empty($upass) )
			return 'You must supply a password for user.';
		
		if( $upass!=$ucpass )
			return 'Passwords you typed twice is not same.' ;
		
		$db = $this->connection->selectDB('admin');
		$db->execute("function (username, pass, readonly){ db.addUser(username, pass, readonly); }", array(
			$uname,
			$upass,
			false
		));
		return 'Add a new user success.';
	}
	
	public function getUserAuth()
	{
		$db = $this->connection->selectDB('admin');
		$collection = $db->selectCollection("system.users");
		$cursor = $collection->find();
		$users= array();
		while($cursor->hasNext()) {
			$users[] = $cursor->getNext();
		}
		return $users;
	}
	
	public function delUserAuth($username)
	{
		$db = $this->connection->selectDB('admin');
		$db->execute("function (username){ db.removeUser(username); }", array($username));
	}
	
	public function duplicateDatabase($_input)
	{
		$ndbname = $_input->post('ndbname');
		$this->db->execute("function(source,target,host){ db.copyDatabase(source,target,host); }",array(
			$this->dbname,
			$ndbname,
			'localhost'
		));
		return 'Duplicate database success.';
	}
	
	public function getDatabaseExport($_input)
	{
		$coll = $_input->post('coll') ;
		
		if( $coll===false ) return 'Please choose collection first.'; 
		
		$selectedCollections = array() ;
		$collections = $this->list_db_collection();
		
		foreach ($collections as $cval):
			if( in_array($cval['table_name'] ,$coll))
				array_push($selectedCollections, $cval['table_obj']);
		endforeach;
		sort($selectedCollections);
		
		$contents = '' ;
		
		foreach ($selectedCollections as $collection) {
			$collObj = $collection;
			$infos = $collObj->getIndexInfo();
			foreach ($infos as $info) {
				$options = array();
				if (isset($info["unique"])) $options["unique"] = $info["unique"];
				$k = $this->_getExport($info['key']) ;
				$o = $this->_getExport($options) ;
				$contents .= "\n/** {$collection} indexes **/\ndb.getCollection(\"" . addslashes($collection) . "\").ensureIndex(" . $k . "," . $o . ");\n";
			}
		}
		foreach ($selectedCollections as $collection) {
			$cursor = $collection->find();
			$contents .= "\n/** " . $collection  . " records **/\n";
			foreach ($cursor as $one) {
				$e = $this->_getExport($one);
				$contents .= "db.getCollection(\"" . addslashes($collection) . "\").insert(" . $e . ");\n";
				unset($exportor);
			}
			unset($cursor);
		}
	
		$filedt = date("YmdHis").".js";
		$url = "<center><a href='".base_url()."export/{$filedt}' target='_new'>Download!</a></center><br>";
		@file_put_contents('export/'.$filedt , $contents);
		
		return $url.$contents;
	}
	
	public function setDatabaseImport($uploadData)
	{
		if($uploadData['file_ext']!='.js')
			return 'file extension is not *.js';
		
		$body = file_get_contents($uploadData['full_path']);
		
		$body = str_replace($this->dbname.".",'',$body);
		
		$ret = $this->connection->selectDB($this->db)->execute('function (){ ' . $body . ' }');
			
		return "All data import successfully.";
		
	}
	
	public function _getExport($var)
	{
		$var = $this->_formatExportVar($var);
		$params = array();
		foreach ($var as $index => $value) {
			$params[$index] = $value;
		}
		return $this->array2json($params,JSON_FORCE_OBJECT);
	}
	
	public function _formatExportVar($var) {
		if (is_scalar($var) || is_null($var)) {
			switch (gettype($var)) {
				case "integer":
					return 'NumberInt(' . $var . ')';
				default:
					return $var;
			}
		}
		if (is_array($var)) {
			foreach ($var as $index => $value) {
				$var[$index] = $this->_formatExportVar($value);
			}
			return $var;
		}
		if (is_object($var)) {
			switch (get_class($var)) {
				case "MongoId":
					return 'ObjectId("' . $var->__toString() . '")';
				case "MongoInt32":
					return 'NumberInt(' . $var->__toString() . ')';
				case "MongoInt64":
					return 'NumberLong(' . $var->__toString() . ')';
				case "MongoDate":
					return "ISODate(\"".date("Y-m-d", $var->sec) . "T" . date("H:i:s.", $var->sec) . ($var->usec/1000) . "Z\")";
				case "MongoTimestamp":
					return json_encode( array("t" => $var->inc * 1000,"i" => $var->sec));
				case "MongoMinKey":
					return json_encode( array( '$minKey' => 1 ));
				case "MongoMaxKey":
					return json_encode( array( '$minKey' => 1 ));
				case "MongoCode":
					return $var->__toString();
				default:
					return '<unknown type>';
			}
		}
	}
	
	public function array2json($arr) {
		$parts = array();
		$is_list = false ;
	
		$keys = array_keys($arr);
		$max_length = count($arr)-1;
		if($max_length>0){
			if(($keys[0]==0)&&($keys[$max_length] == $max_length)) {
				$is_list = true;
				for($i=0; $i<count($keys); $i++) {
					if($i !== $keys[$i]) { 
						$is_list = false; 
						break;
					}
				}
			}
		}
	
		foreach($arr as $key=>$value) {
			if(is_array($value)) {
				if($is_list) $parts[] = $this->array2json($value); 
				else $parts[] = '"' . $key . '":' . $this->array2json($value);
			} else {
				$str = '';
				$str = '"' . $key . '":';
	
				if(is_numeric($value)) $str .= '"'.$value.'"'; 
				elseif($value === false)$str .= 'false';
				elseif($value === true) $str .= 'true';
				else $str .= $value ; 
				
				$parts[] = $str;
			}
		}
		$json = implode(',',$parts);
		 
		if($is_list) return '[' . $json . ']';
		return '{' . $json . '}';
	}
	
	public function collectionValidate()
	{
		return $this->collection->validate();
	}
	
	public function collectionStatistics()
	{
		$stat = array() ;
		$ret = $this->db->command(array( "collStats" => $this->collectionname ));
		if ($ret["ok"]) {
			unset($ret['ok']);
			$stat = $ret ;
		}
		
		$_dbname = $this->dbname ;
		$this->switch_db('admin');
		$ret = $this->db->command(array( "top" => 1 ));
		$top = array();
		$namespace = $_dbname . "." . $this->collectionname;
		if ($ret["ok"] && !empty($ret["totals"][$namespace])) {
			unset($ret['ok']);
			$top = $ret["totals"][$namespace];
		}
		$this->switch_db($_dbname);
		return array($stat,$top);
	}
	
	public function setCollectionName($_input)
	{
		$old_name = $_input->post('cololdname');
		$new_name = $_input->post('colnewname');
		$dropexist= $_input->post('dropexist');
		
		if ($new_name === "") {
			return "Please enter a new name.";
		}
		
		if (!$dropexist) {
			foreach ($this->list_db_collection() as $collection) {
				if ($collection['table_name'] == $new_name) {
					return "There is already a '{$new_name}' collection, you should drop it before renaming.";
				}
			}
		}
		
		$ret = $this->db->execute('function (collection, newname, dropExists) { db.getCollection(collection).renameCollection(newname, dropExists);}',
			array( $old_name, $new_name, (bool)$dropexist )
		);
		if ($ret["ok"]) {
			$this->switch_Collection($new_name);
			return "Operation success.";
		}
		else {
			return "Operation failure";
		}	
	}
	
	public function setCollectionDuplicate($_input)
	{
		$from_name = $_input->post('colfrom');
		$to_name = $_input->post('colto');
		$dptarget= $_input->post('droptarget');
		$cpindex= $_input->post('copyindex');
		
		if ($to_name === "") {
			return "Please enter a valid target.";
		}
		
		if ($dptarget) {
			$this->db->selectCollection($to_name)->drop();
		}
		if($this->_copyCollection($this->collectionname, $to_name, $cpindex))
		 	return "Collection duplicated successfully.";
		else 
			return "Collection duplicated faild.";
	}
	
	protected function _copyCollection($from, $to, $index = true) {
		if ($index) {
			$indexes = $this->collection->getIndexInfo();
			foreach ($indexes as $index) {
				$options = array();
				if (isset($index["unique"])) {
					$options["unique"] = $index["unique"];
				}
				if (isset($index["name"])) {
					$options["name"] = $index["name"];
				}
				if (isset($index["background"])) {
					$options["background"] = $index["background"];
				}
				if (isset($index["dropDups"])) {
					$options["dropDups"] = $index["dropDups"];
				}
				$this->db->selectCollection($to)->ensureIndex($index["key"], $options);
			}
		}
		$ret = $this->db->execute('function (coll, coll2) { return db.getCollection(coll).copyTo(coll2);}', array( $from, $to ));
		return $ret["ok"];
	}
	
	public function clearCollections()
	{
		if($this->delete_all())
			return 'Clear collections successful.';
		else
			return 'Clear collections faild.';
	}
	
	public function dropCollections()
	{
		$this->db->dropCollection($this->collection);
	}
	
	public function getCollectionIndexes()
	{
		$indexes = $this->collection->getIndexInfo();
		return $indexes;
	}
	
	public function dropCollectionIndexes($_input)
	{
		$indexes = $this->collection->getIndexInfo();
		foreach ($indexes as $index) {
			if ($index["name"] == $_input->post('indexname') ) {
				$ret = $this->db->command(array("deleteIndexes" => $this->collectionname, "index" => $index["name"]));
				break;
			}
		}
	}
	
	public function addIndexes($_input)
	{
		$name = $_input->post('name');
		$fields = $_input->post('field');
		$orders = $_input->post('od');
		$is_unique = $_input->post('unique');
	
		if (!is_array($fields)) {
			return "Index contains one field at least.";
		}
		
		$attrs = array();
		foreach ($fields as $index => $field) {
			$field = trim($field);
			if (!empty($field)) {
				$attrs[$field] = ( strtolower($orders[$index]) == "asc") ? 1 : -1;
			}
		}
		
		if (empty($attrs)) {
			return "Index contains one field at least.";
		}
		
		//unique
		$options = array();
		if ($is_unique) {
			$options["unique"] = 1;
			$options["dropDups"] = 1;
		}
		$options["background"] = 1;
		$options["safe"] = 1;
		
		if (!empty($name)) 
			$options["name"] = $name;
			
		try{
			$this->collection->ensureIndex($attrs, $options);
			return '1';
		}catch(Exception $e){
			return 'Something error: '.$e->getMessage();
		}
	}
	
	public function setCollectionNewFields($_input)
	{
		$fieldname = $_input->post('fieldname');
		$keepexist = $_input->post('keepexists');
		$datatype = $_input->post('datatype');
		$data = $_input->post('val');
		
		if ($fieldname === "")
			return "New field name must not be empty";
		
		$realValue = $this->_convertValue($datatype, $data);
		
		$fieldType = "";
		if ($datatype == "integer")
			$fieldType = "integer";
		if ($datatype == "long")
			$fieldType = "long";
		
		if (!$keepexist) {
			$this->collection->update(array(), array( '$set' => array( $fieldname => $realValue ) ), array( "multiple" => 1 ));
		}
		
		$ret = $this->db->execute('function (coll, newname, fieldType, value, keep){
				if (typeof(value) != "object") {
					if (fieldType == "integer") {
						if (typeof(NumberInt) != "undefined") {
							value = NumberInt(value);
						}
					} else if (fieldType == "long") {
						value = NumberLong(value);
					}
				}
		
				var cursor = db.getCollection(coll).find();
				while(cursor.hasNext()) {
					var row = cursor.next();
					var newobj = { $set:{} };
					if (typeof(row[newname]) == "undefined" || !keep) {
						newobj["$set"][newname] = value;
					}
					if (typeof(row["_id"]) != "undefined") {
						db.getCollection(coll).update({ _id:row["_id"] }, newobj);
					}
					else {
						db.getCollection(coll).update(row, newobj);
					}
				}
			}', array($this->collectionname,$fieldname, $fieldType, $realValue, $keepexist ? true:false));
		
		if ($ret["ok"]) 
			return 'Apply new field successful.';
		else
			return 'Something Error.';
	}
	
	protected function _convertValue($dataType, $value) {
		$realValue = null;
		switch ($dataType) {
			case "integer":
				if (class_exists("MongoInt32"))
					$realValue = new MongoInt32($value);
				else 
					$realValue = intval($value);
				break;
			case "long":
				if (class_exists("MongoInt64"))
					$realValue = new MongoInt64($value);
				else
					$realValue = $value;
				break;
			case "float":
			case "double":
				$realValue = doubleval($value);
				break;
			case "string":
				$realValue = $value;
				break;
			case "boolean":
				$realValue = ($value == "true");
				break;
			case "null":
				$realValue = NULL;
				break;
			case "mixed":
				$realValue = (array)json_decode($this->_formatExportVar($value));
				break;
		}
		return $realValue;
	}
	
	public function updateCollectionRow($_input)
	{
		$_id = $_input->post('_id');
		$_data = $_input->post('_data');
		$row = (array)json_decode($_data) ;
		$obj = $this->where(array('_id'=>new MongoId($_id)));
		$sourceObj = $obj->get()[0] ;
		foreach ($sourceObj as $oldKey=>$oldVal):
			if( $oldKey=='_id') continue ;
			if(!array_key_exists($oldKey, $row))
				$obj->update(array('$unset'=>array($oldKey=>1)));
		endforeach;
		$obj->update(array('$set'=>$row));
	}
	
	public function getProfile()
	{
		$cname = $this->collectionname ;
		$this->switch_Collection("system.profile");
		$this->order_by(array('ts'=>-1));
		return $this->get();
	}
	
	public function clearProfile()
	{
		$query1 = $this->db->execute("function (){ return db.getProfilingLevel(); }");
		$oldLevel = $query1["retval"];
		$this->db->execute("function(level) { db.setProfilingLevel(level); }", array(0));
		$ret = $this->db->selectCollection("system.profile")->drop();
		$this->db->execute("function(level) { db.setProfilingLevel(level); }", array($oldLevel));
	}
	
	public function setProfileLevel($_input)
	{
		$sLevel = (int)$_input->post('level');
		$slowms = (int)$_input->post('slowms');
		$this->db->execute("function(level,slowms) { db.setProfilingLevel(level,slowms); }", array($sLevel,$slowms));
		return 'Setting saved. Now level is '.$sLevel;
	}
	
	public function getProfileLevel()
	{
		$query1 = $this->db->execute("function (){ return db.getProfilingLevel(); }");
		return $query1["retval"];
	}
}

?>