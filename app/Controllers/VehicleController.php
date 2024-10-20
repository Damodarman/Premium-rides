<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use App\Models\OwnershipModel;
use App\Models\OwnerModel;
use App\Models\VehicleStatusModel;
use App\Models\VehicleEquipmentModel;
use App\Models\WeeklyVehicleCheckModel;
use App\Models\MonthlyVehicleReportModel;
use App\Models\RegistrationInsuranceModel;
use App\Models\TvrtkaModel;
use App\Models\FlotaModel;
use App\Models\DriverModel;



class VehicleController extends BaseController
{
    protected $vehicleModel;
    protected $tvrtkaModel;
    protected $ownershipModel;
    protected $ownerModel;
    protected $driverModel;
    protected $flotaModel;
    protected $vehicleStatusModel;
    protected $vehicleEquipmentModel;
    protected $weeklyVehicleCheckModel;
    protected $monthlyVehicleReportModel;
    protected $registrationInsuranceModel;
public function __construct()
{
	$this->vehicleModel = model('VehicleModel');
    $this->tvrtkaModel = model('TvrtkaModel');
    $this->vehicleModel = model('VehicleModel');
    $this->ownershipModel = model('OwnershipModel');
    $this->ownerModel = model('OwnerModel');
    $this->driverModel = model('DriverModel');
    $this->flotaModel = model('FlotaModel');
    $this->vehicleStatusModel = model('VehicleStatusModel');
    $this->vehicleEquipmentModel = model('VehicleEquipmentModel');
    $this->weeklyVehicleCheckModel = model('WeeklyVehicleCheckModel');
    $this->monthlyVehicleReportModel = model('MonthlyVehicleReportModel');
    $this->registrationInsuranceModel = model('RegistrationInsuranceModel');
}

public function index()
{
    // Load the pagination library
    $pager = \Config\Services::pager();

    // Set the number of records to display per page
    $perPage = 10;

    // Fetch paginated results
    $vehicles = $this->vehicleModel->paginate($perPage);

    // Get the pagination links
    $pagerLinks = $this->vehicleModel->pager;

    // Load the vehicle listing view and pass the vehicles data
    return view('VehicleManagement/vehicles/index', [
        'title' => 'Vehicles List',
        'vehicles' => $vehicles,
        'pager' => $pagerLinks,  // Pass the pagination links to the view
    ]);
}
	
    // Step 1: Vehicle Details
    public function step1()
    {
        return view('VehicleManagement/vehicles/step1', [
            'title' => 'Step 1 of 5: Vehicle Details',
        ]);
    }

    public function storeStep1()
    {
        // Validation for vehicle details
        $validation = $this->validate([
            'make' => 'required|regex_match[/^[a-zA-ZšđčćžŠĐČĆŽ\s]+$/]',
            'model' => 'required|regex_match[/^[a-zA-Z0-9šđčćžŠĐČĆŽ\s]+$/]',
            'license_plate' => 'required|is_unique[Vehicle.license_plate]',
            'vin' => 'required|is_unique[Vehicle.vin]',
            'year' => 'required|numeric|min_length[4]|max_length[4]',
            'eko_norm' => 'required',
            'kilometers' => 'required|numeric',
        ]);

        if (!$validation) {
            // Store validation errors in an array
            $validationErrors = $this->validator->getErrors();

            // Pass the errors and old input directly to the view
            return view('VehicleManagement/vehicles/step1', [
                'title' => 'Step 1 of 5: Vehicle Details',
                'validationErrors' => $validationErrors,
                'old' => $this->request->getPost(),
            ]);
        }

        // Data to save
        $data = [
            'make' => $this->request->getPost('make'),
            'model' => $this->request->getPost('model'),
            'license_plate' => $this->request->getPost('license_plate'),
            'vin' => $this->request->getPost('vin'),
            'year' => $this->request->getPost('year'),
            'eko_norm' => $this->request->getPost('eko_norm'),
            'kilometers' => $this->request->getPost('kilometers'),
            'user_id' => session()->get('id'),
            'fleet' => session()->get('fleet'),  // Fetch the fleet name from session
            'completion_status' => false,  // Mark as incomplete initially
            'completion_step' => 1,  // Set current step
        ];

        try {
            $this->vehicleModel->save($data);
        } catch (\Exception $e) {
            // Handle database errors (e.g., duplicate entry, invalid data)
            return redirect()->to(site_url('vehicles/step1'))
                ->with('error', 'Failed to save vehicle details. Please try again later.')
                ->withInput();
        }

        $vehicleId = $this->vehicleModel->insertID();

        // Redirect to Step 2
        return redirect()->to(site_url('vehicles/step2/' . $vehicleId))->with('success', 'Vehicle details saved. Please complete ownership details.');
    }

    // Step 2: Ownership Details
public function step2($vehicleId)
{
    // Fetch existing owners from the database for third-party selections
	$fleet = session()->get('fleet');
    $existingOwners = $this->ownerModel->findAll();
	$flota =$this->flotaModel->where('naziv', $fleet)->get()->getRowArray();
	$existingCompanyId = $flota['tvrtka_id'];
	$activeDrivers = $this->driverModel ->select('id, vozac')
										->where('aktivan', true)
										->orderBy('vozac', 'ASC') // Sort by first name
                                       ->where('fleet', $fleet)
                                       ->findAll();

    return view('VehicleManagement/vehicles/step2', [
        'title' => 'Step 2 of 5: Ownership Details',
        'vehicle_id' => $vehicleId,
        'existingOwners' => $existingOwners,
		'activeDrivers' => $activeDrivers,
    ]);
}

public function storeStep2($vehicleId)
{
    // Initialize validation rules array
    $validationRules = [
        'ownership_type' => 'required|in_list[company_owned,third_party_owned,driver_owned,third_party_via_company]',
    ];

    // Get ownership type and owner_id from the form
    $ownershipType = $this->request->getPost('ownership_type');
    $ownerId = $this->request->getPost('owner_id');
    $driverId = $this->request->getPost('driver_id');
    $fleet = session()->get('fleet');

    $flota = $this->flotaModel->where('naziv', $fleet)->get()->getRowArray();
    $existingCompanyId = $flota['tvrtka_id'];

    // Validation for driver-owned vehicles
    if ($ownershipType === 'driver_owned') {
        $validationRules['driver_id'] = 'required|integer';
        $ownerId = null;
    }
    if ($ownershipType === 'third_party_owned') {
        $ownerId = null;
    }

    // If "Not on the list" is selected, validate new owner fields
    if ($ownerId === 'not_on_list') {
        $validationRules['owner_name'] = 'required';
        $validationRules['owner_oib'] = 'required';
        $validationRules['contact_phone'] = 'required';
        $validationRules['contact_email'] = 'required|valid_email';
        $validationRules['address'] = 'required';
    }

    // Validate rental details and weekly price for company or third-party via company
    if ($ownershipType === 'company_owned' || $ownershipType === 'third_party_via_company') {
        $validationRules['rental_details'] = 'required';
        $validationRules['weekly_price'] = 'required|decimal';
    }

    // Perform validation
    if (!$this->validate($validationRules)) {
        return view('VehicleManagement/vehicles/step2', [
            'title' => 'Step 2 of 5: Ownership Details',
            'vehicle_id' => $vehicleId,
            'validationErrors' => $this->validator->getErrors(),
            'old' => $this->request->getPost(),
            'existingOwners' => $this->ownerModel->findAll(),
            'activeDrivers' => $this->driverModel->where(['aktivan' => true, 'fleet' => session()->get('fleet')])->orderBy('ime', 'ASC')->findAll(),
        ]);
    }

    // If "Not on the list", check if OIB exists, else insert new owner
    if ($ownerId === 'not_on_list') {

        $ownerOib = $this->request->getPost('owner_oib');
        $existingOwner = $this->ownerModel->where('oib', $ownerOib)->first();

        if ($existingOwner) {
            // OIB already exists
            return view('VehicleManagement/vehicles/step2', [
                'title' => 'Step 2 of 5: Ownership Details',
                'vehicle_id' => $vehicleId,
                'validationErrors' => ['owner_oib' => 'This OIB already exists in the database. Please select the owner from the list.'],
                'old' => $this->request->getPost(),
                'existingOwners' => $this->ownerModel->findAll(),
                'activeDrivers' => $this->driverModel->where(['aktivan' => true, 'fleet' => session()->get('fleet')])->orderBy('ime', 'ASC')->findAll(),
            ]);
        }

        // Insert new owner
        $newOwnerData = [
            'owner_name' => $this->request->getPost('owner_name'),
            'oib' => $this->request->getPost('owner_oib'),
            'owner_type' => $ownershipType,
            'contact_phone' => $this->request->getPost('contact_phone'),
            'contact_email' => $this->request->getPost('contact_email'),
            'address' => $this->request->getPost('address'),
            'fleet' => session()->get('fleet'),
            'user_id' => session()->get('id'),
        ];
        $this->ownerModel->save($newOwnerData);
        $ownerId = $this->ownerModel->insertID(); // Get the new owner ID

    }

    // Prepare ownership data
    $ownershipData = [
        'vehicle_id' => $vehicleId,
        'ownership_type' => $ownershipType,
        'fleet' => session()->get('fleet'),
        'user_id' => session()->get('id'),
    ];

    // If company-owned or third-party via company
    if ($ownershipType === 'third_party_via_company') {
        $ownershipData['rental_details'] = $this->request->getPost('rental_details');
        $ownershipData['weekly_price'] = $this->request->getPost('weekly_price');
        $ownershipData['owner_id'] = $ownerId;
    }elseif($ownershipType === 'company_owned') {
         $ownershipData['rental_details'] = $this->request->getPost('rental_details');
        $ownershipData['weekly_price'] = $this->request->getPost('weekly_price');
       $ownershipData['vozac_id'] = null;
        $ownershipData['owner_id'] = null;
		$ownershipData['company_id'] = $existingCompanyId;
	}elseif ($ownershipType === 'driver_owned') {
        $ownershipData['vozac_id'] = $driverId;
        $ownershipData['owner_id'] = null;
		$ownershipData['company_id'] = null;
    }else {
        $ownershipData['owner_id'] = null;
        $ownershipData['vozac_id'] = null;
		$ownershipData['company_id'] = null;
    }

    // Check if ownership already exists
    $existingOwnership = $this->ownershipModel->where('vehicle_id', $vehicleId)->first();

    try {
        // Update or insert ownership record
        if ($existingOwnership) {
            $this->ownershipModel->update($existingOwnership['ownership_id'], $ownershipData);
        } else {
            $this->ownershipModel->save($ownershipData);
        }

        // Update vehicle's completion step
        $this->vehicleModel->update($vehicleId, ['completion_step' => 2]);
    } catch (\Exception $e) {
        return redirect()->to(site_url('vehicles/step2/' . $vehicleId))
            ->with('error', 'Failed to save ownership details. Please try again later.')
            ->withInput();
    }

    // Redirect to step 3
    return redirect()->to(site_url('vehicles/step3/' . $vehicleId))->with('success', 'Ownership details saved. Please complete owner details.');
}







	
	
    // Step 3: Owner Details (optional)
public function step3($vehicleId)
{
    // Fetch vehicle ownership details
    $ownership = $this->ownershipModel->where('vehicle_id', $vehicleId)->first();

    // Skip Step 3 if the vehicle is driver-owned or third-party owned
    if ($ownership['ownership_type'] === 'driver_owned' || $ownership['ownership_type'] === 'third_party_owned') {
        return redirect()->to(site_url('vehicles/step4/' . $vehicleId))->with('success', 'Ownership type does not require vehicle status.');
    }

    return view('VehicleManagement/vehicles/step3', [
        'title' => 'Step 3 of 5: Vehicle Status',
        'vehicle_id' => $vehicleId,
    ]);
}

public function storeStep3($vehicleId)
{
    // Validate input fields
    $validationRules = [
        'registration_expiry' => 'required|valid_date',
        'insurance_expiry' => 'required|valid_date',
        'service_interval' => 'required|numeric',
         'insurance_provider' => 'required',
        'insurance_policy_number' => 'required',
		'damage_check' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',  // For sliders 1-5
        'lights_check' => 'required|in_list[ok,not_ok]',  // Specific checks
        'wipers_check' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'tyres_check' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'brakes_check' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'suspension_check' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'oil_check' => 'required|in_list[ok,needs_refill,needs_change]',
        'antifreeze_check' => 'required|in_list[ok,needs_refill,needs_change]',
        'adblue_check' => 'required|in_list[ok,needs_refill]',
        'engine_mounts_check' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
		'dashboard_warning_lights' => 'permit_empty|string',
    ];

    // Perform validation
    if (!$this->validate($validationRules)) {
        return view('VehicleManagement/vehicles/step3', [
            'title' => 'Step 3 of 5: Vehicle Status',
            'vehicle_id' => $vehicleId,
            'validationErrors' => $this->validator->getErrors(),
            'old' => $this->request->getPost(),
        ]);
    }

    // Fetch fleet and user from session
    $fleet = session()->get('fleet');
    $userId = session()->get('id');
	$currentDate = date('Y-m-d');

    // Save vehicle status
    $vehicleStatusData = [
        'vehicle_id' => $vehicleId,
        'fleet' => $fleet,
        'user_id' => $userId,
        'registration_expiry' => $this->request->getPost('registration_expiry'),
        'insurance_expiry' => $this->request->getPost('insurance_expiry'),
        'service_interval' => $this->request->getPost('service_interval'),
    ];

    try {
        // Save vehicle status
        $this->vehicleStatusModel->save($vehicleStatusData);

        // Save weekly vehicle check
        $weeklyCheckData = [
            'vehicle_id' => $vehicleId,
            'fleet' => $fleet,
            'user_id' => $userId,
            'body_damage' => $this->request->getPost('damage_check'),
            'lights_condition' => $this->request->getPost('lights_check'),
            'wipers_status' => $this->request->getPost('wipers_check'),
            'tyres_status' => $this->request->getPost('tyres_check'),
        	'dashboard_warning_lights' => $this->request->getPost('dashboard_warning_lights'),
			'check_date' => $currentDate,
        ];

        $this->weeklyVehicleCheckModel->save($weeklyCheckData);

        // Save monthly vehicle report
        $monthlyReportData = [
            'vehicle_id' => $vehicleId,
            'fleet' => $fleet,
            'user_id' => $userId,
            'brakes_condition' => $this->request->getPost('brakes_check'),
            'shock_absorbers' => $this->request->getPost('suspension_check'),
            'oil_level' => $this->request->getPost('oil_check'),
            'coolant_level' => $this->request->getPost('antifreeze_check'),
            'adblue_level' => $this->request->getPost('adblue_check'),
            'engine_mounts' => $this->request->getPost('engine_mounts_check'),
			'check_date' => $currentDate,
        ];

        $this->monthlyVehicleReportModel->save($monthlyReportData);
		// Save registration and insurance details
		$registrationInsuranceData = [
			'vehicle_id' => $vehicleId,
			'fleet' => $fleet,
			'user_id' => $userId,
			'registration_expiry' => $this->request->getPost('registration_expiry'),
			'insurance_expiry' => $this->request->getPost('insurance_expiry'),
			'insurance_provider' => $this->request->getPost('insurance_provider'),
			'insurance_policy_number' => $this->request->getPost('insurance_policy_number'),
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$this->registrationInsuranceModel->save($registrationInsuranceData);

        // Update the vehicle's completion step to Step 3
        $this->vehicleModel->update($vehicleId, ['completion_step' => 3]);

    } catch (\Exception $e) {
        return redirect()->to(site_url('vehicles/step3/' . $vehicleId))
            ->with('error', 'Failed to save vehicle status. Please try again later.')
            ->with('e', $e)
            ->withInput();
    }

    // Redirect to step 4
    return redirect()->to(site_url('vehicles/step4/' . $vehicleId))->with('success', 'Vehicle status saved. Please complete the next step.');
}

public function step4($vehicleId)
{
    // Retrieve any necessary data for the view (if required)
    return view('VehicleManagement/vehicles/step4', [
        'title' => 'Step 4 of 4: Vehicle Equipment & Safety',
        'vehicle_id' => $vehicleId,
    ]);
}	
	
public function storeStep4($vehicleId)
{
    // Validate input fields
    $validationRules = [
        'fire_extinguisher_validity' => 'required|valid_date',
        'first_aid_kit_status' => 'required|in_list[0,1]', // 0 for not present, 1 for present
        'yellow_paper_validity' => 'required|valid_date',
    ];

    if (!$this->validate($validationRules)) {
        return view('VehicleManagement/vehicles/step4', [
            'title' => 'Step 4 of 4: Vehicle Equipment & Safety',
            'vehicle_id' => $vehicleId,
            'validationErrors' => $this->validator->getErrors(),
            'old' => $this->request->getPost(),
        ]);
    }

    $fleet = session()->get('fleet');
    $userId = session()->get('id');

    // Save equipment details
    $equipmentData = [
        'vehicle_id' => $vehicleId,
        'fire_extinguisher_validity' => $this->request->getPost('fire_extinguisher_validity'),
        'first_aid_kit_status' => $this->request->getPost('first_aid_kit_status'),
        'yellow_paper_validity' => $this->request->getPost('yellow_paper_validity'),
        'fleet' => $fleet,
        'user_id' => $userId,
    ];

    try {
        $this->vehicleEquipmentModel->save($equipmentData);

        // Update the vehicle's completion step to Step 4
	   $this->vehicleModel->update($vehicleId, [
			'completion_step' => 4,
			'completion_status' => true
		]);
        // Redirect to the final step (or vehicle listing, as needed)
        return redirect()->to(site_url('vehicles'))->with('success', 'Vehicle equipment and safety details saved.');
    } catch (\Exception $e) {
        return redirect()->to(site_url('vehicles/step4/' . $vehicleId))
            ->with('error', 'Failed to save vehicle equipment details. Please try again later.')
            ->withInput();
    }
}

  
	
	
	// Step 5: Vehicle Equipment & Safety
    public function step5($vehicleId)
    {
        return view('VehicleManagement/vehicles/step5', [
            'title' => 'Step 5 of 5: Equipment & Safety',
            'vehicle_id' => $vehicleId,
        ]);
    }

 
	
	
	
	
	
	
	public function storeStep5($vehicleId)
    {
        $validation = $this->validate([
            'fire_extinguisher' => 'required',
            'first_aid_kit' => 'required',
            'yellow_paper' => 'required|valid_date',
        ]);

        if (!$validation) {
            return redirect()->to(site_url('vehicles/step5/' . $vehicleId))
                ->withInput()
                ->with('validation', $this->validator);
        }

        $equipmentData = [
            'vehicle_id' => $vehicleId,
            'fire_extinguisher' => $this->request->getPost('fire_extinguisher'),
            'first_aid_kit' => $this->request->getPost('first_aid_kit'),
            'yellow_paper' => $this->request->getPost('yellow_paper'),
        ];

        try {
            $this->vehicleEquipmentModel->save($equipmentData);
            $this->vehicleModel->update($vehicleId, [
                'completion_status' => true, // Mark the vehicle as complete
                'completion_step' => 5,  // Mark the last step as completed
            ]);
        } catch (\Exception $e) {
            return redirect()->to(site_url('vehicles/step5/' . $vehicleId))
                ->with('error', 'Failed to save vehicle equipment details. Please try again later.')
                ->withInput();
        }

        return redirect()->to(site_url('vehicles'))->with('success', 'Vehicle entry complete.');
    }
}
