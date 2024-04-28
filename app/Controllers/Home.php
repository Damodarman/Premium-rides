<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
		$locale = $this->request->getLocale();
		$data = ['locale' => $locale];
        return view('home', $data)
			. view('footer');
    }
}
