<?php
/**
 * Basic CLI interface.
 *
 * PHP Version 5.4
 *
 * @category CLI
 *
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 *
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */
require_once 'vendor/autoload.php';
use InfogreffeUnofficial\SearchCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new SearchCommand());
$application->run();
