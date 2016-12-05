<?php
/**
 * InfogreffeTest class.
 */

namespace InfogreffeUnofficial\Test;

use InfogreffeUnofficial\Infogreffe;

/**
 * Unit tests for Infogreffe class.
 */
class InfogreffeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test search.
     *
     * @param string     $query  Search query
     * @param Infogreffe $result Search result
     *
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
     * Provides companies used for the tests.
     *
     * @return void
     */
    public function searchProvider()
    {
        return [
            [
                'Pierre Jules Rudloff',
                new Infogreffe(
                    751087214,
                    '00027',
                    'RUDLOFF PIERRE JULES',
                    ['MONSIEUR PIERRE RUDLOFF', '87 ROUTE DU POLYGONE'],
                    67100,
                    'STRASBOURG'
                ),
            ],
            [
                'Google France',
                new Infogreffe(
                    443061841,
                    '00047',
                    'GOOGLE FRANCE',
                    ['8 R DE LONDRES'],
                    75009,
                    'PARIS'
                ),
            ],
        ];
    }
}
