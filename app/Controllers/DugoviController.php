<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DugoviNaplataModel;
use CodeIgniter\HTTP\ResponseInterface;

class DugoviController extends Controller
{
	
public function predano() {
    try {
        // Get session data
        $session = session();
        $fleet = $session->get('fleet');
        $role = $session->get('role');
        $dugoviNaplataModel = new DugoviNaplataModel();

        // Get the ID from POST data
        $post = $this->request->getPost();
        $id = $post['id']; // Ensure you get the correct field from POST data
        
        // Check if ID is valid
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid or missing ID'
            ]);
        }

        // Update the record to mark as 'predano'
        $data['predano'] = 'DA';
        $dugoviNaplataModel->update($id, $data);

        // Return a success response
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Successfully marked as predano'
        ]);

    } catch (\Exception $e) {
        // Return an error response in case of exceptions
        return $this->response->setStatusCode(500)->setJSON([
            'status' => 'error',
            'message' => 'An error occurred while updating data: ' . $e->getMessage()
        ]);
    }
}	

public function primljeno() {
    try {
        // Get session data
        $session = session();
        $role = $session->get('role'); // Retrieve the user role

        // Check if the role is "admin"
        if ($role !== 'admin') {
            // Return a 403 Forbidden status with an error message
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'You are not allowed to perform this action'
            ]);
        }

        $fleet = $session->get('fleet');
        $dugoviNaplataModel = new DugoviNaplataModel();

        // Get the ID from POST data
        $post = $this->request->getPost();
        $id = isset($post['id']) ? $post['id'] : null;

        // Check if the ID is valid
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid or missing ID'
            ]);
        }

        // Update the record to mark as "primljeno"
        $data['primljeno'] = 'DA';
        $dugoviNaplataModel->update($id, $data);

        // Return a success response
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Successfully marked as primljeno'
        ]);

    } catch (\Exception $e) {
        // Return an error response in case of exceptions
        return $this->response->setStatusCode(500)->setJSON([
            'status' => 'error',
            'message' => 'An error occurred while updating data: ' . $e->getMessage()
        ]);
    }
}	
public function getFilteredData()
{
    $session = session();
    $fleet = $session->get('fleet');
    $dugoviNaplataModel = new DugoviNaplataModel();

    try {
        // Get POST data and log it
        $filter = $this->request->getPost();

        // Start building the query
        $query = $dugoviNaplataModel->where('fleet', $fleet);

        // Check user filter and log the condition
    // Check user filter
    if (isset($filter['user']) && !empty($filter['user'])) {
        // Use LIKE with wildcard to allow partial matching
        $query->like('user', '%' . $filter['user'] . '%');
    }

    // Check vozac filter
    if (isset($filter['vozac']) && !empty($filter['vozac'])) {
        // Use LIKE with wildcard for partial matching
        $query->like('vozac', '%' . $filter['vozac'] . '%');
    }

    // Check nacin_placanja filter
    if (isset($filter['nacin_placanja']) && !empty($filter['nacin_placanja'])) {
        // Use LIKE for flexible matching
        $query->like('nacin_placanja', '%' . $filter['nacin_placanja'] . '%');
    }
		
    if (isset($filter['iznos']) && !empty($filter['iznos'])) {
        // Use LIKE for flexible matching
        $query->like('iznos', '%' . $filter['iznos'] . '%');
    }

    // Check date filter for exact date matching
    if (isset($filter['predano']) && !empty($filter['predano'])) {
        $query->like('predano', '%' . $filter['predano'] . '%');
    }
    if (isset($filter['primljeno']) && !empty($filter['primljeno'])) {
        $query->like('primljeno', '%' . $filter['primljeno'] . '%');
    }

    // Check start_date and end_date for date range filtering
    if (isset($filter['start_date']) && !empty($filter['start_date'])) {
        $query->where('DATE(timestamp) >=', $filter['start_date']);
    }

    if (isset($filter['end_date']) && !empty($filter['end_date'])) {
        $query->where('DATE(timestamp) <=', $filter['end_date']);
    }

	 $query->orderBy('timestamp', 'DESC');

	
    // Execute the query and fetch results
    $naplaceniDugovi = $query->get()->getResultArray();        
        // Log the number of results

        return $this->response->setJSON($naplaceniDugovi); // Return data as JSON
    } catch (\Exception $e) {
        // Log exceptions
        return $this->response->setStatusCode(500, 'Internal Server Error');
    }
}}
