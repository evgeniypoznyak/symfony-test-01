<?php
/**
 * Created by IntelliJ IDEA.
 * @author Evgeniy
 * Date: 2018-11-04
 */

namespace App\Command;


use App\Service\Greeting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloCommand extends Command
{
    /**
     * @var Greeting
     */
    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:say-hello');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->greeting->greet('TEST'));
    }

}
