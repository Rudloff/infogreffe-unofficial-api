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
                'StrasWeb',
                new Infogreffe(
                    524469699,
                    '00010',
                    'ASSOCIATION STRASWEB',
                    ['ASSOCIATION STRASWEB', '3 RUE MARIANO'],
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
                    ['8 RUE DE LONDRES'],
                    75009,
                    'PARIS'
                ),
            ],
        ];
    }
}
