<?php
/**
 * SearchNameCommandTest class
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */

use InfogreffeUnofficial\SearchNameCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Unit tests for SearchNameCommand class
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */
class SearchNameCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup tests
     * @return void
     */
    protected function setUp()
    {
        $application = new Application();
        $application->add(new SearchNameCommand());

        $this->command = $application->find('search:name');
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
                'name'=>'Pierre Rudloff'
            )
        );

        $this->assertEquals(
            'RUDLOFF PIERRE JULES | 75108721400027 | MONSIEUR PIERRE RUDLOFF, '.
            '87 ROUTE DU POLYGONE, 67100, STRASBOURG, France'.PHP_EOL,
            $this->commandTester->getDisplay()
        );
    }

    /**
     * Test execute with wrong name
     * @return void
     */
    public function testExecuteError()
    {
        $this->commandTester->execute(
            array(
                'command' => $this->command->getName(),
                'name'=>'foobar'
            )
        );

        $this->assertEquals(
            'No result :/'.PHP_EOL, $this->commandTester->getDisplay()
        );
    }

}
