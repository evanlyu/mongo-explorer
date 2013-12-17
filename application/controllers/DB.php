<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DB extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$config =  array(
			'upload_path'     => dirname($_SERVER["SCRIPT_FILENAME"])."/import/",
			'allowed_types'   => "*",
			'overwrite'       => TRUE,
			'max_size'        => "100KB"
		);
		
		$this->load->library('upload',$config);
		$this->load->helper('url');
		$dbname = $this->uri->segment(3);
		$this->mongo_db->switch_DB($dbname);
	}
	
	public function index()
	{
	}
	
	public function target(){
		
		$dbinfo = $this->mongo_db->getCurrentDatabasesInfo();
		$this->showLayout('dbindex',array(
			"dbinfo" => $dbinfo
		));
	}
	
	public function repair()
	{
		$excInfo = $this->mongo_db->runRepairDatabase();
		$this->showLayout('repair',array(
			"excInfo" => $excInfo
		));
	}
	
	public function newCollection()
	{
		$excInfo = "" ;
		if(count($_POST)>0){
			$excInfo = $this->mongo_db->createCollection($this->input);
		}
		
		$this->showLayout('newcollection',array(
			"excInfo" => $excInfo
		));
	}
	
	public function clear()
	{
		$excInfo = "" ;
		if(count($_POST)>0){
			$excInfo = $this->mongo_db->clearDatabaseCollections();
		}
		
		$this->showLayout('clear',array(
			"excInfo" => $excInfo
		));
	}
	public function drop()
	{
		$excInfo = "" ;
		if(count($_POST)>0){
			$this->mongo_db->dropDatabase();
			redirect('/Server/Databases', 'refresh');
		}
		
		$this->showLayout('drop',array(
			"excInfo" => $excInfo
		));
	}
	
	public function duplicate()
	{
		$excInfo = "" ;
		if(count($_POST)>0){
			$excInfo = $this->mongo_db->duplicateDatabase($this->input);
		}
		
		$this->showLayout('duplicate',array(
			"excInfo" => $excInfo
		));
	}
	
	public function export()
	{
		$resultcode = '' ;
		
		if ( $this->input->post() )
			$resultcode = $this->mongo_db->getDatabaseExport($this->input);
		
		$this->showLayout('export',array(
			'dbcollection' => $this->mongo_db->list_db_collection(),
			"resultcode" => $resultcode 
		));
		
	}
	
	public function import()
	{
		$excInfo = '' ;
		if($this->upload->do_upload('jsfile'))
		{
		    $excInfo = $this->mongo_db->setDatabaseImport($this->upload->data());
		}
		
		$this->showLayout('import',array(
			"excInfo" =>$excInfo
		));
	}
	
	public function profile()
	{
		$excInfo = '' ;
		
		$gProfile = $this->mongo_db->getProfile();
		
		$this->showLayout('profile',array(
			"excInfo" =>$excInfo,
			'gProfile'=>$gProfile
		));
	}
	
	public function clearProfile()
	{
		$this->mongo_db->clearProfile();
		redirect('/DB/Profile/'.$this->mongo_db->getCurrentDBName(),'refresh');
	}
	
	public function changeProfileLevel()
	{
		$excInfo = '' ;
		if ( $this->input->post() )
			$excInfo = $this->mongo_db->setProfileLevel($this->input);
		
		$this->showLayout('changeprofilelevel',array(
			"excInfo" =>$excInfo,
			'nowLevel'=>$this->mongo_db->getProfileLevel()
		));
	}
	
	public function showLayout($LayoutName,$LayoutData=array()){
		
		$_paramInfo =  array(
			"_dbname" =>$this->mongo_db->getCurrentDBName(),
			"_collectionname" =>$this->mongo_db->getCurrentCollectionName(),
			'_nController' => 'DB'
		);
		$LayoutData = array_merge($LayoutData,$_paramInfo);
		$DblistData = array_merge(array("dblist"=>$this->mongo_db->list_db()),$_paramInfo);
		$this->load->view('header');
		$this->load->view('dblist',$DblistData);
		$this->load->view('DB/'.$LayoutName,$LayoutData);
		$this->load->view('footer');
	}
}