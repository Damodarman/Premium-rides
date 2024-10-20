<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class MsgTmplModel extends Model{
    protected $table = 'msgTmpl';
    
    protected $allowedFields = [
		'id',
        'user',
        'fleet',
        'name',
        'content',
    ];
}