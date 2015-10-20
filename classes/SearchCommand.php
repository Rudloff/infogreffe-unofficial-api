<?php
/**
 * SearchCommand class
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */
namespace InfogreffeUnofficial;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI search command
 *
 * PHP Version 5.4
 *
 * @category API
 * @package  Infogreffe
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  LGPL https://www.gnu.org/copyleft/lesser.html
 * @link     https://github.com/Rudloff/infogreffe-unofficial-api
 * */
class SearchCommand extends Command
{
    /**
     * Configure command
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('search')
            ->setDescription('Search')
            ->addArgument(
                'query',
                InputArgument::REQUIRED,
                'Search query'
            )
            ->addOption(
                'url',
                null,
                InputOption::VALUE_NONE,
                'If set, will print the URL of each result'
            );
    }

    /**
     * Execute command
     * @param  InputInterface  $input  Input
     * @param  OutputInterface $output Output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = Infogreffe::search($input->getArgument('query'));
        if (empty($result)) {
            $output->writeln('<error>No result :/</error>');
        } else {
            foreach ($result as $org) {
                if ($input->getOption('url')) {
                    $output->writeln(
                        $org->getURL()
                    );
                } else {
                    $org->address['lines'] = implode(', ', $org->address['lines']);
                    $output->writeln(
                        $org->name.' | '.$org->siret.' | '.
                        implode(', ', $org->address)
                    );
                }
            }
        }
    }
}
