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
     * @return void
     * */
    public function testSearch()
    {
        $this->assertEquals(
            array(
                new Infogreffe(
                    751087214, '00027', 'RUDLOFF PIERRE JULES',
                    array('MONSIEUR PIERRE RUDLOFF', '87 ROUTE DU POLYGONE'),
                    67100, 'STRASBOURG'
                )
            ),
            Infogreffe::search('Pierre Jules Rudloff')
        );
    }
}
