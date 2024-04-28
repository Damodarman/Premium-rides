<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class TvrtkaModel extends Model{
    protected $table = 'tvrtka';
    
    protected $allowedFields = [
		'id',
        'naziv',
        'adresa',
        'postanskiBroj',
        'grad',
        'država',
        'OIB',
        'direktor',
        'pocetak_tvrtke',
		'fleet'
    ];
}