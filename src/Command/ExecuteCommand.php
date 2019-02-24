<?php
/**
 * Created by PhpStorm.
 * User: Backins
 * Date: 24/02/2019
 * Time: 22:10
 */

namespace App\Command;


use App\Entity\CronTasks;
use Cron\CronExpression;
use Symfony\Component\Console\Command\Command;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteCommand extends Command
{

    private $objectManager;
    private $tasks;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->tasks = $this->objectManager->getRepository(CronTasks::class)->findAll();

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('cron:execute')
            ->setDescription('Execute cron jobs')
            ->setHelp('This command need to be in cron tab')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     * @see https://packagist.org/packages/mtdowling/cron-expression
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($this->tasks === null){
            throw new \RuntimeException(
                'No tasks founded.'
            );
        }

        foreach($this->tasks as $task){
            if($task->getDisabled() === false){
                dump($task->getName());
                dump($task->getDisabled());
                dump($task->getExpression());
                $cron = CronExpression::factory($task->getExpression());
                $cron->isDue();
                dump($cron->getNextRunDate()->format('Y-m-d H:i:s'));
                dump($cron->getPreviousRunDate()->format('Y-m-d H:i:s'));
            }
        }

        $output->writeln("Cron execute command has successfully done.\n");
    }
}