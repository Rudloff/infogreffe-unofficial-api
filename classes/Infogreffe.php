<?php
/**
 * Infogreffe class.
 */
namespace InfogreffeUnofficial;

use Symfony\Component\Console\Logger\ConsoleLogger;

/**
 * Class used to manage companies and search data on infogreffe.fr.
 */
class Infogreffe
{
    /**
     * Base API URL.
     *
     * @var string
     */
    const BASEURL = 'https://www.infogreffe.fr/';

    /**
     * SIRET.
     *
     * @var int
     */
    public $siret;

    /**
     * Company name.
     *
     * @var string
     */
    public $name;

    /**
     * Company address.
     *
     * @var string[]
     */
    public $address;

    /**
     * Has the company been removed from the registry?
     *
     * @var bool
     */
    public $removed;

    /**
     * Guzzle client.
     *
     * @var \GuzzleHttp\Client
     */
    private static $client;

    /**
     * Infogreffe constructor.
     *
     * @param int      $siren        SIREN
     * @param int      $nic          NIC
     * @param string   $denomination Name
     * @param string[] $address      Address (array with lines)
     * @param int      $zipcode      ZIP code
     * @param string   $city         City
     * @param bool     $removed      Has the company been removed from the registry?
     * */
    public function __construct($siren, $nic, $denomination, $address, $zipcode, $city, $removed = false)
    {
        $this->siret = intval($siren.$nic);
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
     * Get detailed info on companies from API.
     *
     * @param int[]  $ids  IDs to look for
     * @param string $type Type of search
     *
     * @return array
     */
    private static function getItems($ids, $type)
    {
        $resultRCS = self::$client->request(
            'POST',
            self::BASEURL.'services/entreprise/rest/recherche/'.
            'resumeEntreprise?typeRecherche='.$type,
            [
                'json'    => $ids,
                'headers' => ['Content-Type' => 'text/plain'],
            ]
        );

        return json_decode($resultRCS->getBody())->items;
    }

    /**
     * Search for a company.
     *
     * @param string $query Query
     *
     * @return array Results
     */
    public static function search($query, ConsoleLogger $logger = null)
    {
        $handler = \GuzzleHttp\HandlerStack::create();
        if (isset($logger)) {
            $handler->push(\GuzzleHttp\Middleware::log(
                $logger,
                new \GuzzleHttp\MessageFormatter('{req_headers}'.PHP_EOL.'{req_body}')
            ));
        }
        self::$client = new \GuzzleHttp\Client(['cookies' => true, 'handler' => $handler]);
        self::$client->request(
            'GET',
            self::BASEURL.'services/entreprise/rest/recherche/parPhrase',
            [
                'query' => [
                    'phrase'                => $query,
                    'typeProduitMisEnAvant' => 'EXTRAIT',
                ],
            ]
        );

        self::$client->request(
            'GET',
            self::BASEURL.'societes/recherche-entreprise-dirigeants/'.
            'resultats-entreprise-dirigeants.html'
        );
        $response = self::$client->request(
            'GET',
            'https://www.infogreffe.fr/services/entreprise/rest/recherche/'.
            'derniereRechercheEntreprise'
        );
        $response = json_decode($response->getBody());
        $idsRCS = $idsNoRCS = $idsRemovedRCS = [];
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
        $items = [];
        if (!empty($idsRCS)) {
            $items = array_merge($items, self::getItems($idsRCS, 'ENTREP_RCS_ACTIF'));
        }
        if (!empty($idsRemovedRCS)) {
            $items = array_merge($items, self::getItems($idsRemovedRCS, 'ENTREP_RCS_RADIES'));
        }
        if (!empty($idsNoRCS)) {
            $items = array_merge($items, self::getItems($idsNoRCS, 'ENTREP_HORS_RCS'));
        }

        return self::getArrayFromJSON($items);
    }

    /**
     * Convert the JSON list returned by infogreffe.fr
     * to an array of Infogreffe objects.
     *
     * @param array $items Items returned by infogreffe.fr
     *
     * @return array Array of Infogreffe objects
     * */
    private static function getArrayFromJSON($items)
    {
        $return = [];
        foreach ($items as $item) {
            if (isset($item->siren)) {
                $return[] = new self(
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
     * Get SIREN number.
     *
     * @return int SIREN
     */
    private function getSiren()
    {
        return (int) substr($this->siret, 0, 9);
    }

    /**
     * Get escaped name for URL.
     *
     * @return string Escaped name
     */
    private function getEscapedName()
    {
        return preg_replace('/[^[:alnum:]]/', '-', strtolower($this->name));
    }

    /**
     * Get Infogreffe URL.
     *
     * @return string URL
     */
    public function getURL()
    {
        return self::BASEURL.'societes/entreprise-societe/'.
        $this->getSiren().'-'.$this->getEscapedName().'-'.$this->siret.'.html';
    }
}
