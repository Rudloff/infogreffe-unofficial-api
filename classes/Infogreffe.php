<?php
/**
 * Infogreffe class
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */
namespace InfogreffeUnofficial;
/**
 * Class used to search data on infogreffe.fr
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */
class Infogreffe
{
    static private $_BASEURL = 'https://www.infogreffe.fr/';
    static private $_JSONURL
        = 'services/entreprise/rest/recherche/parEntreprise';

    /**
     * Infogreffe constructor
     *
     * @param int    $siren        SIREN
     * @param int    $nic          NIC
     * @param string $denomination Name
     * @param array  $address      Address (array with lines)
     * @param int    $zipcode      ZIP code
     * @param string $city         City
     *
     * @return void
     * */
    function __construct($siren, $nic, $denomination, $address, $zipcode, $city)
    {
        $this->siret = $siren.$nic;
        $this->name = $denomination;
        $this->address['lines'] = $address;
        foreach ($this->address['lines'] as &$line) {
            $line = trim($line);
        }
        $this->address['zipcode'] = $zipcode;
        $this->address['city'] = $city;
        $this->address['country'] = 'France';
    }

    /**
     * Search by SIRET
     *
     * @param int $siret SIRET
     *
     * @return array Array of Infogreffe objects
     * */
    static function searchBySIRET($siret)
    {
        $json = @file_get_contents(
            self::$_BASEURL.self::$_JSONURL.'?sirenOuSiret='.$siret.
            '&typeEntreprise=TOUS&etsRadiees=false&etabSecondaire=false'
        );
        $result = json_decode(
            $json
        );
        if (!is_object($result)) {
            throw new \Exception('Could not get valid JSON');
        }
        return self::_getArrayFromJSON($result);
    }

    /**
     * Search by name
     *
     * @param string $name Name
     *
     * @return array Array of Infogreffe objects
     * */
    static function searchByName($name)
    {
        $result = json_decode(
            file_get_contents(
                self::$_BASEURL.self::$_JSONURL.'?deno='.urlencode($name).
                '&typeEntreprise=TOUS&etsRadiees=false&etabSecondaire=false'
            )
        );
        return self::_getArrayFromJSON($result);
    }

    /**
     * Convert the JSON list returned by infogreffe.fr
     * to an array of Infogreffe objects
     *
     * @param array $json JSON data returned by infogreffe.fr
     *
     * @return array Array of Infogreffe objects
     * */
    static private function _getArrayFromJSON($json)
    {
        $return = array();
        foreach (array(
            $json->entrepHorsRCSStoreResponse, $json->entrepRCSStoreResponse
        ) as $store) {
            foreach ($store->items as $item) {
                if (isset($item->siren)) {
                    $return[] = new Infogreffe(
                        $item->siren, $item->nic,
                        $item->libelleEntreprise->denomination,
                        $item->adresse->lignes, $item->adresse->codePostal,
                        $item->adresse->bureauDistributeur
                    );
                }
            }
        }
        return $return;
    }
}
?>
