<?php
/**
 * InfogreffeTest class
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */

use InfogreffeUnofficial\Infogreffe;

/**
 * Unit tests for Infogreffe class
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */
class InfogreffeTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test search
     * @param string     $query  Search query
     * @param Infogreffe $result Search result
     * @return void
     * @dataProvider searchProvider
     * */
    public function testSearch($query, $result)
    {
        $results = Infogreffe::search($query);
        $this->assertEquals(
            $result,
            $results[0]
        );
    }

    /**
     * [searchProvider description]
     * @return [type] [description]
     */
    public function searchProvider()
    {
        return array(
            array(
                'Pierre Jules Rudloff',
                new Infogreffe(
                    751087214, '00027', 'RUDLOFF PIERRE JULES',
                    array('MONSIEUR PIERRE RUDLOFF', '87 ROUTE DU POLYGONE'),
                    67100, 'STRASBOURG'
                )
            ),
            array(
                'Google France',
                new Infogreffe(
                    443061841, '00047', 'GOOGLE FRANCE',
                    array('8 R DE LONDRES'),
                    75009, 'PARIS'
                )
            )
        );
    }
}
