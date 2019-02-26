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
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteCommand extends Command
{

    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var CronTasks[]
     */
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
                /**
                 * @var CronExpression $cron
                 */
                $cron = CronExpression::factory($task->getExpression());
                $cron->isDue();
                if($task->getLastExecution()->format('Y-m-d H:i:s') < $cron->getPreviousRunDate()->format('Y-m-d H:i:s')){
                    /**
                     * @var \DateTime $now
                     */
                    $now = new \DateTime();
                    /*
                     * Get command name, options and arguments
                     */
                    $command = ($task->getCommand() === null)?'':$task->getCommand();
                    $options = ($task->getOptions() === null)?'':$task->getOptions();
                    $arguments = ($task->getArguments() === null)?'':$task->getArguments();
                    $argInput = self::setArgumentCommand($command, $options, $arguments);

                    /*
                     * Create command and it argument
                     */
                    $application = $this->getApplication()->find($task->getCommand());
                    $argInput = new ArrayInput($argInput);
                    $outputCommand = new BufferedOutput();

                    /*
                     * Try to run command
                     */
                    try {
                        $returnCode = $application->run($argInput, $outputCommand);
                    } catch (\Exception $e){
                        $returnCode = 1;
                    }

                    $task->setLastReturnCode($returnCode);
                    $task->setLastExecution($now);
                    $this->objectManager->persist($task);
                    $this->objectManager->flush();
                }
            }
        }


        $output->writeln("Cron execute command has successfully done.\n");
    }

    /**
     * Build command's arguments and command's options.
     * @param string $command
     * @param string $options
     * @param string $arguments
     * @return array
     */
    private function setArgumentCommand(string $command, string $options, string $arguments): array
    {
        $arrayOptions = self::getOptions($options);
        $arrayArguments = self::getArgument($arguments);
        $argumentCommand = array_merge(
            ['command' => $command],
            array_merge($arrayArguments, $arrayOptions)
        );
        return $argumentCommand;
    }

    /**
     * String option to array
     * @param string $options
     * @return array
     */
    private function getOptions(string $options): array
    {
        $optExploded = [];
        if(!empty($options)){
            $options = explode(' ', $options);
            foreach($options as $option){
                if(strstr($option, '-')){
                    if(strstr($option, '=')){
                        list($name, $value) = explode('=', $option);
                        $optExploded[$name] = $value;
                    } else {
                        $optExploded[$option] = null;
                    }
                }
            }
        }
        return $optExploded;
    }

    /**
     * String argument to array
     * @param string $arguments
     * @return array
     */
    private function getArgument(string $arguments): array
    {
        $argExploded = [];
        if(!empty($arguments)){
            $arguments = explode(' ', $arguments);
            foreach($arguments as $argument){
                if(strstr($argument, '=')){
                    list($name, $value) = explode('=', $argument);
                    $argExploded[$name] = $value;
                }
            }
        }
        return $argExploded;
    }
}