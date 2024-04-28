<?php

namespace App\Models;

use CodeIgniter\Model;

class DriverModel extends Model
{
    protected $table = 'vozaci';

    protected $allowedFields = ['vozac', 'ime', 'email', 'mobitel', 'adresa', 'grad', 'drzava', 'dob', 'oib', 'uber', 'bolt', 'taximetar', 'refered_by', 'referal_reward', 'pocetak_rada', 'vrsta_provizije', 'iznos_provizije', 'popust_na_proviziju', 'uber_unique_id', 'bolt_unique_id', 'myPos_unique_id'];
}
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";