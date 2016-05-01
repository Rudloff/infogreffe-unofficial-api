<?php
/**
 * SearchCommandTest class
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */

use InfogreffeUnofficial\SearchCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Unit tests for SearchCommand class
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */
class SearchCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup tests
     * @return void
     */
    protected function setUp()
    {
        $application = new Application();
        $application->add(new SearchCommand());

        $this->command = $application->find('search');
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
                'query'=>'Pierre Rudloff'
            )
        );

        $this->assertEquals(
            '+------------------------+----------------+------------------------------------------------------------------+---------+'.PHP_EOL.
            '| Name                   | SIRET          | Address                                                          | Removed |'.PHP_EOL.
            '+------------------------+----------------+------------------------------------------------------------------+---------+'.PHP_EOL.
            '| RUDLOFF PIERRE JULES   | 75108721400027 | MONSIEUR PIERRE RUDLOFF, 87 ROUTE DU POLYGONE, 67100, STRASBOURG |         |'.PHP_EOL.
            '| RUDLOFF MATHIEU PIERRE | 52256102600017 | APP 1064, 136 RUE VICTOR HUGO, 60280, MARGNY LES COMPIEGNE       | ❌       |'.PHP_EOL.
            '+------------------------+----------------+------------------------------------------------------------------+---------+'.PHP_EOL,
            $this->commandTester->getDisplay()
        );
    }

    /**
     * Test execute with --url
     * @return void
     */
    public function testExecuteWithUrl()
    {
        $this->commandTester->execute(
            array(
                'command' => $this->command->getName(),
                'query'=>'Pierre Jules Rudloff',
                '--url'=>true
            )
        );

        $this->assertEquals(
            '+----------------------------------------------------------------------------------------------------------+'.PHP_EOL.
            '| https://www.infogreffe.fr/societes/entreprise-societe/751087214-rudloff-pierre-jules-75108721400027.html |'.PHP_EOL.
            '+----------------------------------------------------------------------------------------------------------+'.PHP_EOL,
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
                'query'=>'foobarbaz'
            )
        );

        $this->assertEquals(
            'No result :/'.PHP_EOL, $this->commandTester->getDisplay()
        );
    }

}
