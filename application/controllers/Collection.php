<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collection extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->_dbname = $this->uri->segment(3,'admin');
		$this->_tbname = $this->uri->segment(4,'system.indexes');
		$this->_oparam = $this->uri->segment(5,1);
		$this->_dtpage = $this->_oparam ;
		$this->mongo_db->switch_DB($this->_dbname);
		$this->mongo_db->switch_Collection($this->_tbname);
		$this->_showType = 'JSON';
		$this->_parseini = parse_ini_file('showtype.ini');
		if(isset($this->_parseini['ShowType']))
			$this->_showType = $this->_parseini['ShowType'] ;
	}
	
	public function index()
	{
	}
	
	public function target()
	{
		$dataTotalCount = $this->mongo_db->count();
		$this->mongo_db->limit(10);
		$this->mongo_db->offset($this->_dtpage*10-10);	
		$this->mongo_db->order_by(array('_id'=>-1));
		$columes = $this->mongo_db->get();
		
		$pageType = 'ccindex@'.$this->_showType ;
		$this->showLayout($pageType,array(
			'columes'=>$this->mongo_db->_formatExportVar($columes),
			'dataTotalPageCount'=> ceil($dataTotalCount/10) ,
			'dataTotalCount' => $dataTotalCount - (($this->_dtpage-1)*10) ,
			'nowPage' => $this->_dtpage
		));
	}
	
	public function validate()
	{
		$validInfo = $this->mongo_db->collectionValidate();
		$this->showLayout('validate',array(
			"validInfo" => $validInfo
		));
	}
	
	public function statistics()
	{
		$statInfo = $this->mongo_db->collectionStatistics();
		$this->showLayout('statistics',array(
			"statInfo" => $statInfo[0] ,
			"topInfo" => $statInfo[1]
		));
	}
	
	public function rename()
	{	
		$excInfo = '';
		if ( $this->input->post() )
			$excInfo = $this->mongo_db->setCollectionName($this->input);
		
		$this->showLayout('rename',array(
			"excInfo" => $excInfo 
		));
	}
	
	public function duplicate()
	{
		$excInfo = '';
		if ( $this->input->post() )
			$excInfo = $this->mongo_db->setCollectionDuplicate($this->input);
		
		$this->showLayout('duplicate',array(
			"excInfo" => $excInfo
		));
	}
	
	public function clear()
	{
		$excInfo = "" ;
		if ( $this->input->post() )
			$excInfo = $this->mongo_db->clearCollections();
	
		$this->showLayout('clear',array(
			"excInfo" => $excInfo
		));
	}
	public function drop()
	{
		$excInfo = "" ;
		if ( $this->input->post() ){
			$this->mongo_db->dropCollections();
			redirect('/DB/target/'.$this->mongo_db->getCurrentDBName(), 'refresh');
		}
	
		$this->showLayout('drop',array(
			"excInfo" => $excInfo
		));
	}
	
	public function indexes()
	{
		$excInfo = "" ;
		$this->showLayout('indexes',array(
			"colIndexes"=> $this->mongo_db->getCollectionIndexes(),
			"excInfo" => $excInfo
		));
	}
	
	public function dropIndexes()
	{
		$this->mongo_db->dropCollectionIndexes($this->input);
		redirect('Collection/Indexes/'.$this->mongo_db->getCurrentDBName().'/'.$this->mongo_db->getCurrentCollectionName(), 'refresh');
	}
	
	public function addIndexes()
	{
		$excInfo = '' ;
		if ( $this->input->post() ){
			$excInfo = $this->mongo_db->addIndexes($this->input);
			if($excInfo=='1')
				redirect('Collection/Indexes/'.$this->mongo_db->getCurrentDBName().'/'.$this->mongo_db->getCurrentCollectionName(), 'refresh');
		}
		$this->showLayout('addindexes',array(
			'excInfo' => $excInfo
		));
	}
	
	public function insert()
	{
		$excInfo = '' ;
		$resData = '' ;
		
		if ( $this->input->post() ){
			$resData = $this->input->post('data');
			$deData= (array)json_decode($resData);
			$excInfo = $this->mongo_db->insert($deData) ;
			if($excInfo=='1')
				redirect('Collection/target/'.$this->mongo_db->getCurrentDBName().'/'.$this->mongo_db->getCurrentCollectionName(), 'refresh');
		}
		
		$this->showLayout('insert',array(
			'excInfo' => $excInfo ,
			'data'=> $resData
		));
	}
	
	public function changeType()
	{
		$showtype = $this->input->post('showtype');
		@file_put_contents('showtype.ini','ShowType='.$showtype);
	}
	
	public function newField()
	{
		$excInfo = '' ;
		
		if ( $this->input->post() )
			$excInfo = $this->mongo_db->setCollectionNewFields($this->input);
		
		$this->showLayout('newfield',array(
			'excInfo' => $excInfo ,
		));
	}
	
	public function delete()
	{
		$del_objid = $this->_oparam ;
		$this->mongo_db->where(array('_id'=>new MongoId($del_objid)))->delete();
		redirect('Collection/target/'.$this->mongo_db->getCurrentDBName().'/'.$this->mongo_db->getCurrentCollectionName(), 'refresh');
	}
	
	public function edit()
	{
		if ( $this->input->post() ){
			$this->mongo_db->updateCollectionRow($this->input);
			redirect('Collection/target/'.$this->mongo_db->getCurrentDBName().'/'.$this->mongo_db->getCurrentCollectionName(), 'refresh');
		}
		
		$edit_objid = $this->_oparam ;
		$resData = $this->mongo_db->where(array('_id'=>new MongoId($edit_objid)))->get()[0];
		unset($resData['_id']);
		$this->showLayout('update',array(
			'MongoID'=>$edit_objid ,
			'resData'=>$this->mongo_db->_formatExportVar($resData)
		));
	}
	
	public function search()
	{
		$excInfo = '' ;
		$criteria = '{'.chr(13).chr(10).'}' ;
		$field = array('_id') ;
		$od = array('ASC') ;
		$limit = 0 ;
		$row = 10 ;
		$page = 1 ;
		$columes = array();
		$dataTotalCount = 0 ;
		$urParam = '';
		
		if($this->input->get()){
			$criteria = $this->input->get('criteria');
			$field = $this->input->get('field');
			$od = $this->input->get('od');
			$limit=  $this->input->get('limit');
			$row = $this->input->get('row');
			$page = $this->input->get('page');
			$urParam = "?criteria=".urlencode($criteria)."&limit={$limit}&row={$row}" ;
			
			$qlimit = $limit ;
			if($limit==0||$limit>$row) $qlimit = $row ;
			$odBy = array();
			foreach ($field as $fKey=>$fVal):
				if( $fVal=='' )continue ;
				$odBy[$fVal] = $od[$fKey] ;
				$urParam.= "&field[]={$fVal}&od[]={$od[$fKey]}" ;
			endforeach;
			
			$pcriteria = (array)json_decode($criteria);
			
			$this->mongo_db->where($pcriteria);
			$dataTotalCount = $this->mongo_db->count();
			if($dataTotalCount!=0):
				if($qlimit>$dataTotalCount) $qlimit = $dataTotalCount ;
				$this->mongo_db->limit($qlimit);
				$this->mongo_db->offset($page*$row-$row);	
				$this->mongo_db->order_by($odBy);
				$columes = $this->mongo_db->get();
			else:
				$excInfo = 'No records is found.';
			endif;
		}
					
		$this->showLayout('search',array(
			'criteria' => $criteria ,
			'field' => $field ,
			'od' => $od ,
			'limit' =>$limit ,
			'row' =>$row ,
			'columes' =>$columes ,
			'excInfo' => $excInfo,
			'dataTotalCount'=>$dataTotalCount - (($page-1)*$row) ,
			'dataTotalPageCount'=> ceil($dataTotalCount/$row) ,
			'nowPage'=>$page,
			'urParam'=>$urParam
		));
	}
	
	public function showLayout($LayoutName,$LayoutData=array()){
		$_paramInfo =  array(
			"_dbname" =>$this->mongo_db->getCurrentDBName(),
			"_collectionname" =>$this->mongo_db->getCurrentCollectionName(),
			'_showType'=>$this->_showType ,
			'_nController' => 'Collection'
		);
		$LayoutData = array_merge($LayoutData,$_paramInfo);
		$DblistData = array_merge(array("dblist"=>$this->mongo_db->list_db()),$_paramInfo);
		$this->load->view('header');
		$this->load->view('dblist',$DblistData);
		$this->load->view('Collection/'.$LayoutName,$LayoutData);
		$this->load->view('footer');
	}
}