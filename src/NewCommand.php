<?php
namespace Acme;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\ClientInterface;

class NewCommand extends Command {
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }
    public function configure()
    {
        $this->setName('new')
            ->setDescription('Create a new laravel app')
            ->addArgument('name',InputArgument::OPTIONAL);
//            ->addOption('greeting',null,InputOption::VALUE_OPTIONAL,'Override the default greeting','Hello');

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = getcwd() . '/' . $input->getArgument('name');  // get the current working directory
        // assert that the folder doesn't already exist
        $this->assertApplicationDoesNotExist($directory,$output);

        // download the nightly version of laravel
        $this->download()
            ->extract();
        // extract zip file

        // alert the user that they are ready to go
    }

    // how would we go about doing this?
    // it sounds like we need to accept the name of the directory
    // we also need to alert the user so will pass through the OutputInterface object
    private function assertApplicationDoesNotExist($directory, OutputInterface $output)
    {
        if(is_dir($directory)){
            $output->writeln('<error>Application already exists!</error>');
            exit(1); // General error status code for something went wrong
        }

        
    }

    private function download()
    {
//        there is a cron job that runs every day
//              http://cabinet.laravel.com/latest.zip  this url will give you a nightly version of the framework

        $response = $this->client->get('http://cabinet.laravel.com/latest.zip')
                        ->getBody();
    }
}