<?php
namespace App\Libraries;

use App\Models\MsgTmplModel;

class MsgTemplateLib
{

	
	public function getAll(){
        $session = session();
		$fleet = $session->get('fleet');
		$user = $session->get('name');
		$tmplModel = new MsgTmplModel();
		
		$allMsg = $tmplModel->where('fleet', $fleet)->get()->getResultArray();
		if(!empty($allMsg)){
			return $allMsg;
		}else{
			$allMsg = 0;
			return $allMsg;
		}
	}
	
	public function getMsgTmpl($name){
        $session = session();
		$fleet = $session->get('fleet');
		$user = $session->get('name');
		$tmplModel = new MsgTmplModel();
		$MsgTmpl = $tmplModel->where('fleet', $fleet)->where('name', $name)->get()->getRowArray();
//		print_r($name);
//		die();
		if(!empty($MsgTmpl)){
			return $MsgTmpl;
		}else{
			$MsgTmpl = 0;
			return $MsgTmpl;
		}
	}
	
	
	public function saveMsgTmpl($data){
        $session = session();
		$fleet = $session->get('fleet');
		$user = $session->get('name');
		$tmplModel = new MsgTmplModel();
		$data['user'] = $user;
		$data['fleet'] = $fleet;
//		print_r($data);
//		die();
		if($tmplModel->save($data)){
			return(1);
		}else{
			return(0);
		}
	}
}