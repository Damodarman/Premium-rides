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
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('/admin', 'AdminController::index',['filter' => 'authGuard']);
$routes->get('/signup', 'SignupController::index');
$routes->match(['get', 'post'], 'SignupController/store', 'SignupController::store');
$routes->match(['get', 'post'], 'passwordRecovery', 'SignupController::passwordRecovery');
$routes->match(['get', 'post'], 'SignupController/storeMob', 'SignupController::storeMob');
$routes->match(['get', 'post'], 'SignupController/passwordReset', 'SignupController::passwordReset');
$routes->match(['get', 'post'], 'SignupController/newPasswordSave', 'SignupController::newPasswordSave');
$routes->match(['get', 'post'], 'SigninController/loginAuth', 'SigninController::loginAuth');
$routes->get('/signin', 'SigninController::index');
$routes->get('/logout', 'ProfileController::logout');
$routes->get('/profile', 'ProfileController::index',['filter' => 'authGuard']);
$routes->get('/uberImport', 'AdminController::uberImport',['filter' => 'authGuard']);
$routes->get('/addDriver', 'AdminController::addDriver',['filter' => 'authGuard']);
$routes->get('/generator', 'NumberGenerationController::generator',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/raskidUgovora', 'AdminController::raskidUgovora', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/addDriverSave', 'AdminController::addDriverSave', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/napomenaSave', 'AdminController::napomenaSave', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'AdminController/sendmsg', 'AdminController::sendmsg', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'FlotaController/UltramsgLibPostavke', 'FlotaController::UltramsgLibPostavke', ['filter' => 'authGuard']);
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
$routes->match(['get', 'post'], 'AdminController/myPosReportImport', 'AdminController::myPosReportImport',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'NumberGenerationController/generate', 'NumberGenerationController::generate',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'KnjigovodstvoController/addTrgovcaSave', 'KnjigovodstvoController::addTrgovcaSave',['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'KnjigovodstvoController/addPrMjTrSave', 'KnjigovodstvoController::addPrMjTrSave',['filter' => 'authGuard']);
$routes->get('/addTrgovca', 'KnjigovodstvoController::addTrgovca',['filter' => 'authGuard']);
$routes->get('/upute', 'UputeController::index');
$routes->get('/obracun', 'ObracunController::index',['filter' => 'authGuard']);
$routes->get('/vozila', 'AdminController::vozila',['filter' => 'authGuard']);
$routes->get('/knjigovodstvo', 'KnjigovodstvoController::index',['filter' => 'authGuard']);
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
$routes->get('/obracun/(:any)', 'AdminController::obracun/$1',['filter' => 'authGuard']);
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
$routes->get('/admin/tvrtka', 'TvrtkaController::index',['filter' => 'authGuard']);
$routes->get('/obracunaj/(:any)', 'ObracunController::obracunaj/$1',['filter' => 'authGuard']);
$routes->get('/editirajObracun/(:any)', 'ObracunController::editVozacObracun/$1',['filter' => 'authGuard']);
$routes->get('/obracunVozac/(:any)', 'ObracunController::obracunVozac/$1');
$routes->get('/obrDel/(:any)', 'ObracunController::obracunDelete/$1',['filter' => 'authGuard']);
$routes->get('/drivers/(:any)', 'AdminController::driver/$1',['filter' => 'authGuard']);
$routes->get('/ulazniRacuni', 'UlazniRacuniController::index',['filter' => 'authGuard']);
$routes->get('/unosRacuna', 'UlazniRacuniController::unosRacuna',['filter' => 'authGuard']);
$routes->get('/createPdf', 'PdfController::index');
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
