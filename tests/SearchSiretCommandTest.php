<?php
/**
 * SearchSiretCommandTest class
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */

use InfogreffeUnofficial\SearchSiretCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Unit tests for SearchSiretCommand class
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */
class SearchSiretCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup tests
     * @return void
     */
    protected function setUp()
    {
        $application = new Application();
        $application->add(new SearchSiretCommand());

        $this->command = $application->find('search:siret');
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * Test execute
     * @return void
     */
    public function testExecute()
    {
        $this->commandTester->execute(
            array(
                'command' => $this->command->getName(),
                'siret'=>'75108721400027'
            )
        );

        $this->assertEquals(
            'RUDLOFF PIERRE JULES | 75108721400027 | MONSIEUR PIERRE RUDLOFF'.
            ', 87 ROUTE DU POLYGONE, 67100, STRASBOURG, France'.PHP_EOL,
            $this->commandTester->getDisplay()
        );
    }

    /**
     * Test execute with invalid SIRET
     *
     * @return            void
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testExecuteError()
    {
        $this->commandTester->execute(
            array(
                'command' => $this->command->getName(),
                'siret'=>'foobar'
            )
        );
    }

    /**
     * Test execute with wrong SIRET
     * @return void
     */
    public function testExecuteNoResult()
    {
        $this->commandTester->execute(
            array(
                'command' => $this->command->getName(),
                'siret'=>'75108721400026'
            )
        );

        $this->assertEquals(
            'No result :/'.PHP_EOL, $this->commandTester->getDisplay()
        );
    }

}
