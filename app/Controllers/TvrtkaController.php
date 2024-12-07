<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\FlotaModel;
use App\Models\TvrtkaModel;
  
class TvrtkaController extends Controller
{
	public function index(){
		helper('form');
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$columns = $tvrtkaModel->get()->getFieldNames('tvrtka');
		$columns = array_diff($columns, ['id']); // Remove 'id' field
		$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtke = $tvrtkaModel->where('fleet', $fleet)->get()->getResultArray();
		$aktivnaTvrtka = $tvrtkaModel->where('id', $postavkeFlote['tvrtka_id'])->get()->getRowArray();

		
		$data['role'] = $role;
		$data['fleet'] = $fleet;
		$data['page'] = 'Tvrtke';
		$data['aktivnaTvrtka'] = $aktivnaTvrtka;
		$data['tvrtke'] = $tvrtke;
		$data['columns'] = $columns;
		
		echo view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/tvrtka')
			. view('footer');

		
	}
	
	
	public function addTvrtka() {
		helper('form');
		$session = session();
		$fleet = $session->get('fleet');

		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();

		$request = service('request');
		$validationRules = [
			'naziv' => 'required|min_length[1]|max_length[100]',
			'adresa' => 'required|min_length[1]|max_length[100]',
			'postanskiBroj' => 'required|exact_length[5]|numeric',
			'grad' => 'required',
			'država' => 'required',
			'OIB' => 'required|regex_match[/^\d{11}$/]',
			'direktor' => 'required',
			'pocetak_tvrtke' => 'required',
		];

		// Validate the form input
		if (!$this->validate($validationRules)) {
			// If validation fails, display error messages and redisplay the form with user input
			$data['validation'] = $this->validator;
			foreach ($validationRules as $inputName => $rule) {
				$data['formData'][$inputName] = $request->getPost($inputName);
			}
			$columns = $tvrtkaModel->get()->getFieldNames('tvrtka');
			$columns = array_diff($columns, ['id']); // Remove 'id' field
			$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
			$tvrtke = $tvrtkaModel->where('fleet', $fleet)->get()->getResultArray();
			$aktivnaTvrtka = $tvrtkaModel->where('id', $postavkeFlote['tvrtka_id'])->get()->getRowArray();
			$data['fleet'] = $fleet;
			$data['page'] = 'Tvrtke';
			$data['aktivnaTvrtka'] = $aktivnaTvrtka;
			$data['tvrtke'] = $tvrtke;
			$data['columns'] = $columns;

			echo view('adminDashboard/header', $data)
				. view('adminDashboard/navBar')
				. view('adminDashboard/tvrtka')
				. view('footer');
			} else {

				$potpisPecatFile = $this->request->getFile('potpis_pecat');
				 $newFileName = 'default.png'; // Default image name
				if ($potpisPecatFile && $potpisPecatFile->isValid()) {
					$newFileName = $potpisPecatFile->getRandomName();
					$potpisPecatFile->move('uploads', $newFileName);
				}

			// Insert new record
			$tvrtkaData = [
				'naziv' => $this->request->getPost('naziv'),
				'adresa' => $this->request->getPost('adresa'),
				'postanskiBroj' => $this->request->getPost('postanskiBroj'),
				'grad' => $this->request->getPost('grad'),
				'država' => $this->request->getPost('država'),
				'OIB' => $this->request->getPost('OIB'),
				'direktor' => $this->request->getPost('direktor'),
				'pocetak_tvrtke' => $this->request->getPost('pocetak_tvrtke'),
				'fleet' => $fleet,
				'oib_direktora' => $this->request->getPost('oib_direktora'),
				'MBS' => $this->request->getPost('MBS'),
				'IBAN' => $this->request->getPost('IBAN'),
				'BIC' => $this->request->getPost('BIC'),
				'potpis_pecat' => $newFileName,
			];
			// Process the form data and redirect to a success page
			if($tvrtkaModel->save($tvrtkaData)){
				$session->setFlashdata('msgtvrtka', ' Uspješno dodana tvrtka.');
				session()->setFlashdata('alert-class', 'alert-success');
				return redirect()->to('admin/tvrtka');
			}
			else{
				$session->setFlashdata('msgtvrtka', ' Tvrtka nije dodana.');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('admin/tvrtka');
				
			}
		}
	}
	
    public function editTvrtka($id)
    {
        $model = new TvrtkaModel();
		$session = session();
		$fleet = $session->get('fleet');

        $potpisPecatFile = $this->request->getFile('potpis_pecat');
        $data = [
            'naziv' => $this->request->getPost('naziv'),
            'adresa' => $this->request->getPost('adresa'),
            'postanskiBroj' => $this->request->getPost('postanskiBroj'),
            'grad' => $this->request->getPost('grad'),
            'država' => $this->request->getPost('država'),
            'OIB' => $this->request->getPost('OIB'),
            'direktor' => $this->request->getPost('direktor'),
            'pocetak_tvrtke' => $this->request->getPost('pocetak_tvrtke'),
            'fleet' => $fleet,
            'oib_direktora' => $this->request->getPost('oib_direktora'),
            'MBS' => $this->request->getPost('MBS'),
            'IBAN' => $this->request->getPost('IBAN'),
            'BIC' => $this->request->getPost('BIC'),
        ];

        // Handle file upload for potpis_pecat if provided
        if ($potpisPecatFile && $potpisPecatFile->isValid()) {
            $newFileName = $potpisPecatFile->getRandomName();
            $potpisPecatFile->move('uploads', $newFileName);
            $data['potpis_pecat'] = $newFileName; // Only update if new file is uploaded
        }

        if($model->update($id, $data)){
				$session->setFlashdata('msgtvrtka', ' Uspješno editirana tvrtka.');
				session()->setFlashdata('alert-class', 'alert-success');
				return redirect()->to('admin/tvrtka');
		}
			else{
				$session->setFlashdata('msgtvrtka', ' Tvrtka nije editirana.');
				session()->setFlashdata('alert-class', 'alert-danger');
				echo view('adminDashboard/header', $data)
					. view('adminDashboard/navBar')
					. view('adminDashboard/tvrtka')
					. view('footer');
				
			}
    }
	
	
    public function uploadPecat($id)
    {
        $model = new TvrtkaModel();
        $tvrtka = $model->find($id);

        if ($this->request->getMethod() === 'post') {
            $file = $this->request->getFile('potpis_pecat');

            // Check if the file is valid and uploaded
            if ($file && $file->isValid()) {
                // Move the file to the uploads directory and generate a new name
                $newName = $file->getRandomName();
                $file->move('uploads', $newName);

                // Update the tvrtka with the new file path
                $model->update($id, ['potpis_pecat' => $newName]);

                // Redirect back to the tvrtka list or show a success message
                return redirect()->to(site_url('admin/tvrtke'))->with('msgtvrtka', 'Potpis i Pečat uploaded successfully');
            } else {
                return redirect()->back()->with('msgtvrtka', 'Failed to upload the image. Please try again.');
            }
        }

        // Load the view with tvrtka details
        return view('adminDashboard/tvrtka_upload_pecat', ['tvrtka' => $tvrtka]);
    }
	
	
	
}