<?php
/**
 * Basic CLI interface
 * 
 * PHP Version 5.4
 * 
 * @category CLI
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */
require_once 'Infogreffe.class.php';
if (isset($argv[1]) && isset($argv[2])) {
    switch($argv[1]){
    case 'name':
        $result = Infogreffe::searchByName($argv[2]);
        break;
    case 'siret':
        $result = Infogreffe::searchBySIRET($argv[2]);
        break;
    }
    if (empty($result)) {
        echo 'No result :/'.PHP_EOL;
    } else {
        foreach ($result as $org) {
            $org->address['lines'] = implode(', ', $org->address['lines']);
            echo $org->name.' | '.$org->siret.' | '.
                implode(', ', $org->address).PHP_EOL;
        }
    }
} else {
    echo 'Usage: php cli.php name|siret search'.PHP_EOL;
}
?>
