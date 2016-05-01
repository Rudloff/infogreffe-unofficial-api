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
    static private $BASEURL = 'https://www.infogreffe.fr/';
    public $siret;
    public $name;
    public $address;
    public $removed;

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
    public function __construct($siren, $nic, $denomination, $address, $zipcode, $city, $removed = false)
    {
        $this->siret = $siren.$nic;
        $this->name = $denomination;
        $this->address['lines'] = $address;
        foreach ($this->address['lines'] as &$line) {
            $line = trim($line);
        }
        if (!empty($zipcode)) {
            $this->address['zipcode'] = $zipcode;
        }
        if (!empty($city)) {
            $this->address['city'] = $city;
        }
        $this->removed = $removed;
    }

    /**
     * Search for a company
     * @param  string $query Query
     * @return array Results
     */
    public static function search($query)
    {
        $client = new \GuzzleHttp\Client(array('cookies' => true));
        $response = $client->request(
            'GET',
            self::$BASEURL.'services/entreprise/rest/recherche/parPhrase',
            array(
                'query' => array(
                    'phrase' => $query,
                    'typeProduitMisEnAvant'=>'EXTRAIT'
                )
            )
        );
        $json = $response->getBody();
        $result = json_decode(
            $json
        );

        $client->request(
            'GET',
            self::$BASEURL.'societes/recherche-entreprise-dirigeants/'.
            'resultats-entreprise-dirigeants.html'
        );
        $response = $client->request(
            'GET',
            'https://www.infogreffe.fr/services/entreprise/rest/recherche/'.
            'derniereRechercheEntreprise'
        );
        $response = json_decode($response->getBody());
        $idsRCS = $idsNoRCS = array();
        foreach ($response->entrepRCSStoreResponse->items as $result) {
            if (isset($result->id)) {
                $idsRCS[] = $result->id;
            }
        }
        if (isset($response->entrepRadieeStoreResponse)) {
            foreach ($response->entrepRadieeStoreResponse->items as $result) {
                if (isset($result->id)) {
                    $idsRemovedRCS[] = $result->id;
                }
            }
        }
        foreach ($response->entrepHorsRCSStoreResponse->items as $result) {
            if (isset($result->id)) {
                $idsNoRCS[] = $result->id;
            }
        }
        $items = array();
        if (!empty($idsRCS)) {
            $resultRCS = $client->request(
                'POST',
                self::$BASEURL.'services/entreprise/rest/recherche/'.
                'resumeEntreprise?typeRecherche=ENTREP_RCS_ACTIF',
                array(
                    'json'=>$idsRCS,
                    'headers'=>array('Content-Type'=>'text/plain')
                )
            );
            $items = array_merge($items, json_decode($resultRCS->getBody())->items);
        }
        if (!empty($idsRemovedRCS)) {
            $resultRCS = $client->request(
                'POST',
                self::$BASEURL.'services/entreprise/rest/recherche/'.
                'resumeEntreprise?typeRecherche=ENTREP_RCS_RADIES',
                array(
                    'json'=>$idsRemovedRCS,
                    'headers'=>array('Content-Type'=>'text/plain')
                )
            );
            $items = array_merge($items, json_decode($resultRCS->getBody())->items);
        }
        if (!empty($idsNoRCS)) {
            $resultNoRCS = $client->request(
                'POST',
                self::$BASEURL.'services/entreprise/rest/recherche/'.
                'resumeEntreprise?typeRecherche=ENTREP_HORS_RCS',
                array(
                    'json'=>$idsNoRCS,
                    'headers'=>array('Content-Type'=>'text/plain')
                )
            );
            $items = array_merge(
                $items,
                json_decode($resultNoRCS->getBody())->items
            );
        }
        return self::getArrayFromJSON($items);
    }

    /**
     * Convert the JSON list returned by infogreffe.fr
     * to an array of Infogreffe objects
     *
     * @param array $items Items returned by infogreffe.fr
     *
     * @return array Array of Infogreffe objects
     * */
    private static function getArrayFromJSON($items)
    {
        $return = array();
        foreach ($items as $item) {
            if (isset($item->siren)) {
                $return[] = new Infogreffe(
                    $item->siren,
                    $item->nic,
                    $item->libelleEntreprise->denomination,
                    $item->adresse->lignes,
                    $item->adresse->codePostal,
                    $item->adresse->bureauDistributeur,
                    $item->radie
                );
            }
        }
        return $return;
    }

    /**
     * Get SIREN number
     * @return int SIREN
     */
    private function getSiren()
    {
        return substr($this->siret, 0, 9);
    }

    /**
     * Get escaped name for URL
     * @return string Escaped name
     */
    private function getEscapedName()
    {
        return preg_replace('/[^[:alnum:]]/', '-', strtolower($this->name));
    }

    /**
     * Get Infogreffe URL
     * @return string URL
     */
    public function getURL()
    {
        return self::$BASEURL.'societes/entreprise-societe/'.
        $this->getSiren().'-'.$this->getEscapedName().'-'.$this->siret.'.html';
    }
}
