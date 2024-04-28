<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class TrgovciModel extends Model{
    protected $table = 'trgovci';
    
    protected $allowedFields = [
		'id',
        'nazivTrgovca',
        'adresaTrgovca',
        'gradTrgovca',
        'porezniBrojTrgovca',
		'postanskiBroj'
    ];
	
	
	public function getTrgovciWithPrMjTrgovca()
	{
		$builder = $this->db->table('trgovci');
		$builder->join('prMjTrgovca', 'prMjTrgovca.trgovci_id = trgovci.id');
		$query = $builder->get();
		return $query->getResultArray();
	}
}