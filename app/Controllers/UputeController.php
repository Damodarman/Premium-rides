<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;


class UputeController extends BaseController
{
    public function index()
    {
		
		$data['page'] = 'Upute';
		
		return view('upute/header', $data)
			. view('upute/navBar')
			. view('upute/upute')
			. view('footer');

	}
	
}