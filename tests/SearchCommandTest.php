<?php
/**
 * SearchCommandTest class.
 */

namespace InfogreffeUnofficial\Test;

use InfogreffeUnofficial\SearchCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Unit tests for SearchCommand class.
 */
class SearchCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup tests.
     *
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
     * Test execute.
     *
     * @return void
     */
    public function testExecute()
    {
        $this->commandTester->execute(
            [
                'command' => $this->command->getName(),
                'query'   => '51801365100014',
            ]
        );
        $result = <<<'EOT'
+---------------------------+----------------+----------------------------+---------+
| Name                      | SIRET          | Address                    | Removed |
+---------------------------+----------------+----------------------------+---------+
| PHARMACIE DE L'UNIVERSITE | 51801365100014 | 1 AVENUE DE LA FORÊT NOIRE |         |
|                           |                | 67000 STRASBOURG           |         |
+---------------------------+----------------+----------------------------+---------+

EOT;

        $this->assertEquals(
            $result,
            $this->commandTester->getDisplay()
        );
    }

    /**
     * Test execute with --url.
     *
     * @return void
     */
    public function testExecuteWithUrl()
    {
        $this->commandTester->execute(
            [
                'command' => $this->command->getName(),
                'query'   => '51801365100014',
                '--url'   => true,
            ]
        );
        $result = <<<'EOT'
+---------------------------------------------------------------------------------------------------------------+
| https://www.infogreffe.fr/societes/entreprise-societe/518013651-pharmacie-de-l-universite-51801365100014.html |
+---------------------------------------------------------------------------------------------------------------+

EOT;

        $this->assertEquals(
            $result,
            $this->commandTester->getDisplay()
        );
    }

    /**
     * Test execute with wrong name.
     *
     * @return void
     */
    public function testExecuteError()
    {
        $this->commandTester->execute(
            [
                'command' => $this->command->getName(),
                'query'   => 'foobarbaz',
            ]
        );

        $this->assertEquals(
            'No result :/'.PHP_EOL,
            $this->commandTester->getDisplay()
        );
    }
}
