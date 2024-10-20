<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\DriverModel;
use App\Models\VozilaModel;
use App\Models\TaximetarLogsModel;
use App\Models\TaximetarAdminLogsModel;
use App\Models\FlotaModel;
use App\Models\TvrtkaModel;
use App\Models\UltramsgLibConfigModel;

use App\Libraries\MsgTemplateLib;
use App\Libraries\UltramsgLib;


helper('session');
  
class TaximetarLogsController extends Controller
{
    public function index()
    {
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
	
	}
	
//	public function populateTaxiLogs(){
//        $session = session();
//		$data = $session->get();
//		$fleet = $session->get('fleet');
//		$role = $session->get('role');
//		$driverModel = new DriverModel();
//
//		$drivers = $driverModel->where('fleet', $fleet)->where('aktivan', 1)->where('taximetar', 1)->get()->getResultArray();
//		
//		$resultat = array();
//		foreach($drivers as $driver){
//			$id = $driver['id'];
//			$result = $this->posalji($id);
//			$resultat[] = array(
//				'vozac' => $driver['vozac'],			
//				'vozac_id' => $driver['id'],			
//				'result' => $result,			
//			);
//		}
//	
//		print_r($resultat);
//	}
	
	public function deaktiviraj($id = null){
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$user_id = $session->get('id');
		$username = $session->get('name');
		$driverModel = new DriverModel();
		$vozilaModel = new VozilaModel();
		$flotaModel = new FlotaModel();
		$taximetarLogsModel = new TaximetarLogsModel();
		$taximetarAdminLogsModel = new TaximetarAdminLogsModel();
		
		$referrer = $this->request->getHeader('referer')->getValue();
		
		$driver = $driverModel->where('id', $id)->get()->getRowArray();
		$vozilo = $vozilaModel->where('vozac_id', $id)->get()->getRowArray();
		$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$taximetarWhatsApp = $postavkeFlote['taximetar_whatsapp_broj'];
		$existingLog = $taximetarLogsModel->where('vozac_id', $id)->get()->getRowArray();
		
		$tvrtka = $postavkeFlote['tvrtka_naziv'];
		
		if ($existingLog) {
			// Delete the existing record
			$deleteSuccess = $taximetarLogsModel->where('vozac_id', $id)->delete();

			if ($deleteSuccess) {
				// Prepare WhatsApp message
				$message = "Molimo vas odjavu vozača {$driver['vozac']} sa flote {$tvrtka}:
	{$driver['taximetar_unique_id']}
	{$driver['vozac']}
	{$driver['oib']}";

				// Send the WhatsApp message
				$this->sendWhatsAppMessage($taximetarWhatsApp, $message);

				// Log the deactivation in the admin logs table
				$podaciZaTaximetarAdminLogs = array(
					'vozac_id' => $driver['id'],
					'user_id' => $user_id,
					'ukljucenje' => 0,
					'iskljucenje' => 1,
					'promjena' => 0,
					'fleet' => $fleet,
				);
				$taximetarAdminLogsModel->insert($podaciZaTaximetarAdminLogs);

				return redirect()->to($referrer)
					->with('msgtaximetarlog', 'Vozaču je uspješno deaktiviran taximetar i obavijest je poslana na WhatsApp.')
					->with('alert-class', 'alert-success');
			} else {
				return redirect()->to($referrer)
					->with('msgtaximetarlog', 'Greška pri deaktiviranju vozača. Obavijestite administratora.')
					->with('alert-class', 'alert-danger');
			}
		} else {
			return redirect()->to($referrer)
				->with('msgtaximetarlog', 'Vozač nije pronađen u bazi podataka.')
				->with('alert-class', 'alert-warning');
		}    		
	}
	
	
public function posalji($id = null){
    $session = session();
    $fleet = $session->get('fleet');
    $role = $session->get('role');
    $user_id = $session->get('id');
    $username = $session->get('name');

    $driverModel = new DriverModel();
    $vozilaModel = new VozilaModel();
    $flotaModel = new FlotaModel();
    $taximetarLogsModel = new TaximetarLogsModel();
    $taximetarAdminLogsModel = new TaximetarAdminLogsModel();

    $referrerHeader = $this->request->getHeader('referer');
    $referrer = $referrerHeader ? $referrerHeader->getValue() : base_url();

    $driver = $driverModel->where('id', $id)->get()->getRowArray();
    $vozilo = $vozilaModel->where('vozac_id', $id)->get()->getRowArray();
    $postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
    $taximetarWhatsApp = $postavkeFlote['taximetar_whatsapp_broj'];
    $tvrtka = $postavkeFlote['tvrtka_naziv'];

    $podaciZaTaximetar = [
        'vozac' => $driver['ime'] .' ' .$driver['prezime'],
        'brojMoba' => $driver['mobitel'],
        'OIB' => $driver['oib'],
        'email' => $driver['taximetar_unique_id'],
        'ModelMobitela' => $driver['mobTaximetar'],
        'regVozila' => $vozilo['reg'],
    ];

    $podaciZaTaximetarLog = [
        'vozac' => $driver['ime'] .' ' .$driver['prezime'],
        'vozac_id' => $driver['id'],
        'brojMoba' => $driver['mobitel'],
        'OIB' => $driver['oib'],
        'email' => $driver['taximetar_unique_id'],
        'modelMoba' => $driver['mobTaximetar'],
        'administrator_id' => $user_id,
        'administrator' => $username,
        'regVozila' => $vozilo['reg'],
        'fleet' => $fleet,
    ];

    // Check if there's already a record for this driver in the TaximetarLogsModel
    $existingLog = $taximetarLogsModel->where('vozac_id', $id)->get()->getRowArray();

    if ($existingLog) {
        // Track the changes
        $changedFields = [];

        if ($existingLog['brojMoba'] != $podaciZaTaximetar['brojMoba']) {
            $changedFields['brojMoba'] = $podaciZaTaximetar['brojMoba'];
        }
        if ($existingLog['email'] != $podaciZaTaximetar['email']) {
            $changedFields['email'] = $podaciZaTaximetar['email'];
        }
        if ($existingLog['modelMoba'] != $podaciZaTaximetar['ModelMobitela']) {
            $changedFields['ModelMobitela'] = $podaciZaTaximetar['ModelMobitela'];
        }
        if ($existingLog['regVozila'] != $podaciZaTaximetar['regVozila']) {
            $changedFields['regVozila'] = $podaciZaTaximetar['regVozila'];
        }

        if (!empty($changedFields)) {
            // Update the existing record
            $updateSuccess = $taximetarLogsModel->update($existingLog['id'], $podaciZaTaximetarLog);
            $podaciZaTaximetarAdminLogs = [
                'vozac_id' => $driver['id'],
                'user_id' => $user_id,
                'ukljucenje' => 0,
                'iskljucenje' => 0,
                'promjena' => 1,
                'fleet' => $fleet,
            ];
            $taximetarAdminLogsModel->insert($podaciZaTaximetarAdminLogs);

            if ($updateSuccess) {
                // Include only the changed fields in the WhatsApp message
                $message = $this->formatWhatsAppMessage(array_merge(['vozac' => $driver['ime'] .' ' .$driver['prezime'], 'email' => $driver['taximetar_unique_id']], $changedFields), true, $tvrtka);
                $this->sendWhatsAppMessage($taximetarWhatsApp, $message);
                return redirect()->to($referrer)
                    ->with('msgtaximetarlog', 'Podaci za taximetar su pronađeni, ažurirani i poslani u STP na WhatsApp.')
                    ->with('alert-class', 'alert-success');
            } else {
                return redirect()->to($referrer)
                    ->with('msgtaximetarlog', 'Greška pri ažuriranju podataka za taximetar. Obavijestite administratora')
                    ->with('alert-class', 'alert-danger');
            }
        } else {
            return redirect()->to($referrer)
                ->with('msgtaximetarlog', 'Podaci za taximetar su pronađeni i nije bilo potrebe ažurirati.')
                ->with('alert-class', 'alert-info');
        }
    } else {
        // Insert new record and check for success
        $insertSuccess = $taximetarLogsModel->insert($podaciZaTaximetarLog);
        if ($insertSuccess) {
            $podaciZaTaximetarAdminLogs = [
                'vozac_id' => $driver['id'],
                'user_id' => $user_id,
                'ukljucenje' => 1,
                'iskljucenje' => 0,
                'promjena' => 0,
                'fleet' => $fleet,
            ];
            $taximetarAdminLogsModel->insert($podaciZaTaximetarAdminLogs);
            $message = $this->formatWhatsAppMessage($podaciZaTaximetar, false, $tvrtka);
            $this->sendWhatsAppMessage($taximetarWhatsApp, $message);
            return redirect()->to($referrer)
                ->with('msgtaximetarlog', 'Podaci za taximetar nisu pronađeni pa su kreirani novi i poslani u STP na WhatsApp.')
                ->with('alert-class', 'alert-success');
        } else {
            return redirect()->to($referrer)
                ->with('msgtaximetarlog', 'Greška pri kreiranju novih podataka za taximetar. Obavijestite administratora')
                ->with('alert-class', 'alert-danger');
        }
    }
}

private function formatWhatsAppMessage($data, $isUpdate, $tvrtka) {
		if (isset($data['regVozila'])) {
	$data['regVozila'] = str_replace('-', ' ', $data['regVozila']);
		}
	
    if ($isUpdate) {
        $message = "Podaci za vozača {$data['vozac']} na floti {$tvrtka} su ažurirani:\n";
        $message .= "{$data['email']}\n";
        $message .= "Novi podaci su:\n";
		if (isset($data['OIB'])) {
			$message .= "{$data['OIB']}\n";
		}
		if (isset($data['regVozila'])) {
			$message .= "{$data['regVozila']}\n";
		}
		if (isset($data['brojMoba'])) {
			$message .= "{$data['brojMoba']}\n";
		}
		if (isset($data['ModelMobitela'])) {
			$message .= "{$data['ModelMobitela']}\n";
		}
    } else {
        $message = "Poštovani, molimo vas uključenje novog vozača na flotu {$tvrtka}:\n";
        $message .= "{$data['email']}\n";
        $message .= "{$data['vozac']}\n";
        $message .= "{$data['OIB']}\n";
        $message .= "{$data['regVozila']}\n";
        $message .= "{$data['brojMoba']}\n";
        $message .= "{$data['ModelMobitela']}\n";
   }

    return $message;
}

private function sendWhatsAppMessage($to, $message) {
    $ultraMsgLib = new UltramsgLib(); // Make sure this is properly initialized
    $data = [
        'to' => $to,
        'msg' => $message
    ];
    $response = $ultraMsgLib->sendMsg($data);

    if ($response['status'] == 'success') {
        log_message('info', 'WhatsApp message sent successfully.');
    } else {
        log_message('error', 'Failed to send WhatsApp message. Error: ' . $response['error']);
    }
}

}