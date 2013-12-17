<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Server extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	
	public function index()
	{
		$directives = ini_get_all("mongo") ;
		$this->showLayout('index',
			array(
				"sysinfo"=>$this->mongo_db->getServerInfo(),
				"directives"=>$directives
			)
		);
	}
	
	public function Status()
	{
		$statusinfo = $this->mongo_db->getStatusInfo();
		$this->showLayout('status',
			array(
				"statusinfo"=>$statusinfo
			)
		);
	}
	
	public function Databases()
	{
		$dbinfo = $this->mongo_db->getDatabasesInfo();
		$this->showLayout('databases',
			array(
				"dbinfo"=>$dbinfo
			)
		);
	}
	
	public function newdb()
	{
		$excInfo = '' ;
		if( count($_POST) >0 )
			$excInfo = $this->mongo_db->newDatabase($this->input);
		
		$this->showLayout('newdb',
			array(
				"excInfo"=>$excInfo
			)
		);
	}
	
	public function Process()
	{
		$proginfo = $this->mongo_db->getProcessList();
		$this->showLayout('process',
			array(
				"proginfo"=>$proginfo
			)
		);
	}
	
	public function Command()
	{
		$dblist = $this->mongo_db->list_db();
		$this->showLayout('command',
			array(
				"dblist"=>$dblist
			)
		);
	}
	
	public function Execute()
	{
		$dblist = $this->mongo_db->list_db();
		$this->showLayout('execute',
			array(
				"dblist"=>$dblist
			)
		);
	}
	
	public function Users()
	{
		$this->showLayout('users',
			array(
				'userauth' => $this->mongo_db->getUserAuth()
			)
		);
	}
	
	public function addUser()
	{
		$excInfo = '' ;
		
		if( count( $_POST ) >0 ){
			$excInfo = $this->mongo_db->addUser($this->input);
		}
		
		$dblist = $this->mongo_db->list_db();
		$this->showLayout('adduser',
			array(
				"dblist" => $dblist ,
				"excInfo" => $excInfo 
			)
		);
	}
	
	public function delUser()
	{
		$this->mongo_db->delUserAuth($this->uri->segment(3));
		redirect('/Server/Users','refresh');
	}
	
	public function doCommand()
	{
		$cmd = json_decode($_POST['cmd']);
		$dbs = $_POST['dbs'];
		print_r( $this->mongo_db->runCommand($cmd,$dbs) );
	}
	
	public function doExecute()
	{
		$cmd = $_POST['cmd'];
		$dbs = $_POST['dbs'];
		print_r( $this->mongo_db->runExecute($cmd,$dbs) );
	}
	
	public function about()
	{
		$this->showLayout('about');
	}
	
	public function showLayout($LayoutName,$LayoutData=array())
	{	
		$_paramInfo =  array(
			"_dbname" =>$this->mongo_db->getCurrentDBName(),
			"_collectionname" =>$this->mongo_db->getCurrentCollectionName(),
			'_nController' => 'Server'
		);
		$LayoutData = array_merge($LayoutData,$_paramInfo);
		$DblistData = array_merge(array("dblist"=>$this->mongo_db->list_db()),$_paramInfo);
		$this->load->view('header');
		$this->load->view('dblist',$DblistData);
		$this->load->view('Server/'.$LayoutName,$LayoutData);
		$this->load->view('footer');
	}
}