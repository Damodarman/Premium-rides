<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('logs', 'LogViewerController::index'); 
//$routes->post('/admin/driver-activity', 'AdminController::driverActivity');
$routes->add('/admin/driver-activity', 'AdminController::driverActivity', ['methods' => ['GET', 'POST']]);

// BACKUPS
$routes->group('backups', ['namespace' => 'App\Controllers'], ['filter' => 'authGuard'], function ($routes) {
    // View all backups
    $routes->get('/', 'BackupController::index'); 

    // Create a backup based on type (hourly, daily, etc.)
    $routes->get('create/(:segment)', 'BackupController::createBackup/$1'); 

    // Rotate backups for a specific type
    $routes->get('rotate/(:segment)', 'BackupController::rotateBackups/$1'); 

    // Restore a specific backup
    $routes->get('restore/(:segment)', 'BackupController::restore/$1'); 
	
	// Manual backup
	$routes->get('manual', 'BackupController::manualBackup');
    // Send email with download links for backups
    $routes->get('email/(:segment)', 'BackupController::sendBackupLinks/$1'); 
});
$routes->get('backups/download/(:any)', 'BackupController::download/$1');



// TASKS
$routes->get('tasks', 'Tasks::index',['filter' => 'authGuard']);
$routes->get('tasks/create', 'Tasks::create',['filter' => 'authGuard']);
$routes->post('tasks/store', 'Tasks::store',['filter' => 'authGuard']);
$routes->post('tasks/requestHelp/(:num)', 'Tasks::requestHelp/$1',['filter' => 'authGuard']);
$routes->get('tasks/edit/(:num)', 'Tasks::edit/$1',['filter' => 'authGuard']);
$routes->post('tasks/update/(:num)', 'Tasks::update/$1',['filter' => 'authGuard']);
$routes->get('tasks/markAsCompleted/(:num)', 'Tasks::markAsCompleted/$1',['filter' => 'authGuard']);
$routes->get('tasks/start/(:num)', 'Tasks::start/$1',['filter' => 'authGuard']);
$routes->post('tasks/updateStatus/(:num)', 'Tasks::updateStatus/$1',['filter' => 'authGuard']);
$routes->get('tasks/show/(:num)', 'Tasks::show/$1',['filter' => 'authGuard']);
$routes->post('tasks/create/obracun/(:num)', 'Tasks::createObracunTask/$1',['filter' => 'authGuard']);
$routes->post('tasks/create/vozac/(:num)', 'Tasks::createVozacTask/$1',['filter' => 'authGuard']);
$routes->get('tasks/getChatMessages/(:num)', 'Tasks::getChatMessages/$1');
$routes->post('tasks/sendMessage', 'Tasks::sendMessage');



//NOTIFICATIONS

$routes->get('notifications/getNotifications', 'NotificationController::getNotifications');
$routes->post('notifications/markAsRead', 'NotificationController::markAsRead');
$routes->post('notifications/markAsDelivered', 'NotificationController::markAsDelivered');



// API AJAX ROUTES START
$routes->get('api/vehicle-counts', 'ApiController::getVehicleCounts');
$routes->post('api/getDriversReports', 'ApiController::getDriversReports');
$routes->get('api/getDriversReports', 'ApiController::getDriversReports');
$routes->get('api/getReportWeeks', 'ApiController::getReportWeeks');
$routes->get('api/drivers-counts', 'ApiController::getDriversCounts'); 
$routes->get('api/getPlatformRatio', 'ApiController::getPlatformRatio'); 
$routes->post('api/getPlatformRatio', 'ApiController::getPlatformRatio'); 
$routes->post('api/getDriverNameById', 'ApiController::getDriverNameById'); 
$routes->get('api/checkSession', 'ApiController::checkSession'); 


// API AJAX ROUTES END

// NEW ROUTES START, AFTER FINISHING TRANSFER TO NEW ROUTES DELETE THE OLD ONES
//Vehicle Managment
$routes->get('/dashboard', 'DashboardController::index',['filter' => 'authGuard']);
$routes->get('vehicles/page/(:num)', 'VehicleController::index/$1',['filter' => 'authGuard']);
$routes->get('/vehicles', 'VehicleController::index',['filter' => 'authGuard']);
$routes->get('/vehicles/step1', 'VehicleController::step1',['filter' => 'authGuard']);
$routes->get('/vehicles/edit/(:num)', 'VehicleController::edit/$1',['filter' => 'authGuard']);
$routes->post('/vehicles/storeStep1', 'VehicleController::storeStep1',['filter' => 'authGuard']);
$routes->post('/vehicles/store', 'VehicleController::store',['filter' => 'authGuard']);
$routes->get('/vehicles/edit/(:num)', 'VehicleController::edit/$1',['filter' => 'authGuard']);
$routes->post('/vehicles/update/(:num)', 'VehicleController::update/$1',['filter' => 'authGuard']);
$routes->get('/vehicles/delete/(:num)', 'VehicleController::delete/$1',['filter' => 'authGuard']);


// REPORTS IMPORT CONTROLLER

$routes->post('/tempUberReportImport', 'ImportController::tempUberReportImport',['filter' => 'authGuard']);
$routes->get('/uberImport', 'AdminController::uberImport',['filter' => 'authGuard']);
$routes->get('reports/uploadreports', 'ImportController::index',['filter' => 'authGuard']);
$routes->get('/reports/bolt', 'ImportController::getBoltReports',['filter' => 'authGuard']);
$routes->get('/reports/uber', 'ImportController::getUberReports',['filter' => 'authGuard']);
$routes->get('/reports/taximetar', 'ImportController::getTaximetarReports',['filter' => 'authGuard']);
$routes->get('/reports/myPos', 'ImportController::getMyPosReports',['filter' => 'authGuard']);
$routes->post('/uberReport/deleteUberReport', 'ImportController::deleteUberReport',['filter' => 'authGuard']);
$routes->post('/boltReport/deleteBoltReport', 'ImportController::deleteBoltReport',['filter' => 'authGuard']);
$routes->post('/taximetarReport/deleteTaximetarReport', 'ImportController::deleteTaximetarReport',['filter' => 'authGuard']);
$routes->post('/myPosReport/deleteMyPosReport', 'ImportController::deleteMyPosReport',['filter' => 'authGuard']);

// DRIVERS MANAGMENT
$routes->get('/driver/index', 'DriversController::index',['filter' => 'authGuard']);
$routes->post('/driver/processId', 'DriversController::processId',['filter' => 'authGuard']);
$routes->get('/driver/active', 'DriversController::active',['filter' => 'authGuard']);
$routes->get('/driver/create', 'DriversController::create',['filter' => 'authGuard']);

// NEW ROUTES END

$routes->get('generatePdfAll', 'AdminController::generatePdfAll',['filter' => 'authGuard']);
$routes->post('editTvrtka/(:num)', 'TvrtkaController::editTvrtka/$1');
$routes->get('tvrtka/uploadPecat/(:num)', 'TvrtkaController::uploadPecat/$1');
$routes->post('tvrtka/uploadPecat/(:num)', 'TvrtkaController::uploadPecat/$1');
$routes->get('generateCombinedPdf/(:num)', 'AdminController::generateCombinedPdf/$1',['filter' => 'authGuard']);
$routes->get('generateCombinedPdfSimple/(:num)', 'AdminController::generateCombinedPdfSimple/$1',['filter' => 'authGuard']);

    $routes->get('/vehicles/step2/(:num)', 'VehicleController::step2/$1',['filter' => 'authGuard']); // Vehicle ID is passed to this route
    $routes->post('/vehicles/storeStep2/(:num)', 'VehicleController::storeStep2/$1',['filter' => 'authGuard']); // Vehicle ID is passed to storeStep2

    // Step 3: Owner Details
    $routes->get('/vehicles/step3/(:num)', 'VehicleController::step3/$1',['filter' => 'authGuard']);
    $routes->post('/vehicles/storeStep3/(:num)', 'VehicleController::storeStep3/$1',['filter' => 'authGuard']);

    // Step 4: Vehicle Status
    $routes->get('/vehicles/step4/(:num)', 'VehicleController::step4/$1',['filter' => 'authGuard']);
    $routes->post('/vehicles/storeStep4/(:num)', 'VehicleController::storeStep4/$1',['filter' => 'authGuard']);

    // Step 5: Equipment & Safety
    $routes->get('/vehicles/step5/(:num)', 'VehicleController::step5/$1',['filter' => 'authGuard']);
    $routes->post('/vehicles/storeStep5/(:num)', 'VehicleController::storeStep5/$1',['filter' => 'authGuard']);

$routes->get('tjedneIsplateKreiraj/(:num)', 'TjedneIsplateController::tjedneIsplate/$1',['filter' => 'authGuard']);
$routes->post('generirajRevolutCSV', 'TjedneIsplateController::generirajRevolutCSV');
$routes->post('generirajHRSepa', 'TjedneIsplateController::generirajHRSepa');


$routes->post('ObracunController/getPlaceData', 'ObracunController::getPlaceData',['filter' => 'authGuard']);
$routes->get('place', 'ObracunController::place',['filter' => 'authGuard']);
$routes->get('companies', 'FleetDataController::getCompanies');
$routes->get('fleet-data', 'FleetDataController::getFleetDataStateLogs');
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('/admin', 'AdminController::index',['filter' => 'authGuard']);
$routes->match(['get', 'post'], '/sendReminder', 'DugoviController::dugoviOpomena',['filter' => 'authGuard']);
$routes->get('/signup', 'SignupController::index');
$routes->match(['get', 'post'], '/obradenoDa', 'KnjigovodaController::obradenoDa');
$routes->match(['get', 'post'], 'SignupController/store', 'SignupController::store');
$routes->match(['get', 'post'], 'passwordRecovery', 'SignupController::passwordRecovery');
$routes->match(['get', 'post'], 'SignupController/storeMob', 'SignupController::storeMob');
$routes->match(['get', 'post'], 'SignupController/passwordReset', 'SignupController::passwordReset');
$routes->match(['get', 'post'], 'SignupController/newPasswordSave', 'SignupController::newPasswordSave');
$routes->match(['get', 'post'], 'SigninController/loginAuth', 'SigninController::loginAuth');
$routes->get('/signin', 'SigninController::index');
$routes->get('/logout', 'ProfileController::logout');
$routes->get('/profile', 'ProfileController::index',['filter' => 'authGuard']);
$routes->get('/addDriver', 'AdminController::addDriver',['filter' => 'authGuard']);
$routes->get('/generator', 'NumberGenerationController::generator',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/raskidUgovora', 'AdminController::raskidUgovora', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/addDriverSave', 'AdminController::addDriverSave', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/napomenaSave', 'AdminController::napomenaSave', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/sendmsg', 'AdminController::sendmsg', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'sendMultipleMsg', 'AdminController::sendMultipleMsg', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'FlotaController/UltramsgLibPostavke', 'FlotaController::UltramsgLibPostavke', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'FlotaController/saveMsgTmpl', 'FlotaController::saveMsgTmpl', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/driverUpdate', 'AdminController::driverUpdate', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/driverPrijavaUpdate', 'AdminController::driverPrijavaUpdate', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'ObracunController/obracunSave', 'ObracunController::obracunSave', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'ObracunController/saveScreenshots', 'ObracunController::saveScreenshots');
$routes->match(['get', 'post'], 'ObracunController/skinutiSlike', 'ObracunController::skinutiSlike');
$routes->match(['get', 'post'], 'ObracunController/sveSlikeSpremljene', 'ObracunController::sveSlikeSpremljene');
$routes->match(['get', 'post'], 'ObracunController/obracunSavedodatni', 'ObracunController::obracunSavedodatni', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/uberReportImport', 'AdminController::uberReportImport',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/uskrataSave', 'AdminController::uskrataSave',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/spremiDug', 'AdminController::spremiDug');
$routes->match(['get', 'post'], 'dugovi/kreirajUskratu/(:any)', 'AdminController::kreirajUskratu/$1',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/poslatiSlike', 'AdminController::poslatiSlike');
$routes->match(['get', 'post'], 'AdminController/poslatiObracun', 'AdminController::poslatiObracun');
$routes->match(['get', 'post'], 'AdminController/boltReportImport', 'AdminController::boltReportImport',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'ImportController/taximetarImport', 'ImportController::taximetarImport',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'ImportController/boltImport', 'ImportController::boltImport',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'ImportController/importMultipleFilesBolt', 'ImportController::importMultipleFilesBolt',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'ImportController/importMultipleFilesUber', 'ImportController::importMultipleFilesUber',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'ImportController/uberActivityReportImport', 'ImportController::activityUberImport',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'ImportController/boltActivityImport', 'ImportController::boltActivityImport',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/myPosReportImport', 'AdminController::myPosReportImport',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'NumberGenerationController/generate', 'NumberGenerationController::generate',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'KnjigovodstvoController/addTrgovcaSave', 'KnjigovodstvoController::addTrgovcaSave',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'KnjigovodstvoController/addPrMjTrSave', 'KnjigovodstvoController::addPrMjTrSave',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'naProviziju', 'NaProvizijuController::index',['filter' => 'authGuard']);
$routes->get('/addTrgovca', 'KnjigovodstvoController::addTrgovca',['filter' => 'authGuard']);
$routes->get('/naPlacu', 'NaPlacuController::index',['filter' => 'authGuard']);
$routes->get('/naProviziju', 'NaProvizijuController::index',['filter' => 'authGuard']);
$routes->get('/upute', 'UputeController::index');
$routes->get('/obracun', 'ObracunController::index',['filter' => 'authGuard']);
$routes->get('/vozila', 'AdminController::vozila',['filter' => 'authGuard']);
$routes->get('/knjigovodstvo', 'KnjigovodstvoController::index',['filter' => 'authGuard']);
$routes->get('/aktivniDanas', 'KnjigovodaController::activDrivers',['filter' => 'authGuard']);
$routes->get('/addprodajnomjesto', 'KnjigovodstvoController::addprodajnomjesto',['filter' => 'authGuard']);
$routes->get('/ugovoroRadu', 'AdminController::ugovoroRadu',['filter' => 'authGuard']);
$routes->get('/ugovoroRadu/(:any)', 'AdminController::ugovoroRaduPdf/$1',['filter' => 'authGuard']);
$routes->get('/ugovoroRaduPrint/(:any)', 'AdminController::ugovoroRaduPdf/$1',['filter' => 'authGuard']);
$routes->get('/aneksUgovora/(:any)', 'AdminController::aneksUgovoraPdf/$1',['filter' => 'authGuard']);
$routes->get('/ugovoroNajmu', 'AdminController::ugovoroNajmu',['filter' => 'authGuard']);
$routes->get('/ugovoroNajmu/(:any)', 'AdminController::ugovoroNajmuPdf/$1',['filter' => 'authGuard']);
$routes->get('/kreirajRaskid/(:any)', 'AdminController::kreirajRaskid/$1',['filter' => 'authGuard']);
$routes->get('/radniOdnos', 'AdminController::radniOdnos',['filter' => 'authGuard']);
$routes->get('/radniOdnos/(:any)', 'AdminController::radniOdnos/$1',['filter' => 'authGuard']);
$routes->get('/radniOdnosBolt/(:any)', 'AdminController::radniOdnosBolt/$1',['filter' => 'authGuard']);
$routes->get('/blagajnickiminmax', 'AdminController::blagajnickiminmax',['filter' => 'authGuard']);
$routes->get('/blagajnickiminmax/(:any)', 'AdminController::blagajnickiminmaxPdf/$1',['filter' => 'authGuard']);
$routes->get('/obracun/(:num)', 'AdminController::obracun/$1',['filter' => 'authGuard']);
$routes->get('/obracun/view/(:num)', 'ObracunController::obracunView/$1',['filter' => 'authGuard']);
$routes->get('/placanjeNajam/(:any)', 'AdminController::obracunNajma/$1',['filter' => 'authGuard']);
$routes->get('/knjigovodstvo/(:any)', 'AdminController::knjigovodstvo/$1',['filter' => 'authGuard']);
$routes->get('/drivers', 'AdminController::drivers',['filter' => 'authGuard']);
$routes->get('/getTestData', 'AdminController::getTestData',['filter' => 'authGuard']);
$routes->get('/dugovi', 'AdminController::dugovi',['filter' => 'authGuard']);
$routes->get('/dugovi/tablicaDugova', 'AdminController::tablicaDugova',['filter' => 'authGuard']);
$routes->get('/dugPlacen/(:any)', 'AdminController::dugPlacen/$1',['filter' => 'authGuard']);
$routes->get('/dugPlacenPoslovnica/(:any)', 'AdminController::dugPlacenPoslovnica/$1',['filter' => 'authGuard']);
$routes->get('/dug/(:any)', 'AdminController::dug/$1',['filter' => 'authGuard']);
$routes->get('/emailVerification/(:any)', 'SignupController::confirmToken/$1');
$routes->get('/resetPassword/(:any)/(:any)', 'SignupController::resetPassword/$1/$2');
$routes->get('/neaktdrivers', 'AdminController::driversNeaktiv',['filter' => 'authGuard']);
$routes->get('/obr', 'ObracunController::index',['filter' => 'authGuard']);
$routes->get('/admin/flota', 'FlotaController::index',['filter' => 'authGuard']);
$routes->get('/admin/posaljiPoruku', 'AdminController::posaljiPoruku',['filter' => 'authGuard']);
$routes->get('/testemail', 'AdminController::sendTestEmail',['filter' => 'authGuard']);
$routes->get('/napomene', 'AdminController::napomene',['filter' => 'authGuard']);
$routes->get('/prijaveRadnika', 'PrijaveController::index',['filter' => 'authGuard']);
$routes->get('/voditelj', 'VoditeljController::index',['filter' => 'authGuard']);
$routes->get('/knjigovoda', 'KnjigovodaController::index',['filter' => 'authGuard']);
$routes->get('/admin/tvrtka', 'TvrtkaController::index',['filter' => 'authGuard']);
$routes->get('/obracunaj/(:any)', 'ObracunController::obracunaj/$1',['filter' => 'authGuard']);
$routes->get('/editirajObracun/(:any)', 'ObracunController::editVozacObracun/$1',['filter' => 'authGuard']);
$routes->get('/provjeriuniqueID/(:any)', 'ObracunController::checkId/$1');
$routes->get('/obracunVozac/(:any)', 'ObracunController::obracunVozac/$1');
$routes->get('/obrDel/(:any)', 'ObracunController::obracunDelete/$1',['filter' => 'authGuard']);
$routes->get('/drivers/(:any)', 'AdminController::driver/$1',['filter' => 'authGuard']);
$routes->get('/posaljiTaximetar/(:any)', 'TaximetarLogsController::posalji/$1',['filter' => 'authGuard']);
$routes->get('/taximetar/deaktiviraj/(:num)', 'TaximetarLogsController::deaktiviraj/$1');
$routes->get('/populateTaxiLogs', 'TaximetarLogsController::populateTaxiLogs',['filter' => 'authGuard']);
$routes->get('/ulazniRacuni', 'UlazniRacuniController::index',['filter' => 'authGuard']);
$routes->get('/unosRacuna', 'UlazniRacuniController::unosRacuna',['filter' => 'authGuard']);
$routes->get('/createPdf', 'PdfController::index');
$routes->add('fetchDriverData', 'AdminController::fetchDriverData', ['methods' => ['GET', 'POST']]);
$routes->add('dugovi/getFilteredData', 'DugoviController::getFilteredData', ['methods' => ['GET', 'POST']]);
$routes->add('dugovi/predano', 'DugoviController::predano', ['methods' => ['GET', 'POST']]);
$routes->add('dugovi/primljeno', 'DugoviController::primljeno', ['methods' => ['GET', 'POST']]);
$routes->match(['get', 'post'], 'obracunaj', 'ObracunController::obracunaj',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'UlazniRacuniController/saveRacuna', 'UlazniRacuniController::saveRacuna');
$routes->match(['get', 'post'], 'PdfController/htmlToPDF', 'PdfController::htmlToPDF');
$routes->match(['get', 'post'], 'getRacuni', 'UlazniRacuniController::getRacuni');
$routes->match(['get', 'post'], 'AdminController/addVoziloSave', 'AdminController::addVoziloSave', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'TvrtkaController/addTvrtka', 'TvrtkaController::addTvrtka', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/addVoziloUpdate', 'AdminController::addVoziloUpdate', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'ObracunController/obracunUpdate', 'ObracunController::obracunUpdate', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'FlotaController/postavkeFlote', 'FlotaController::postavkeFloteUpdate', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'NumberGenerationController/saveRacuna', 'NumberGenerationController::saveRacuna', ['filter' => 'authGuard']);
$routes->post('excel/import', 'ExcelImportController::importData');
$routes->get('excel/import', 'ExcelImportController::showImportForm');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
