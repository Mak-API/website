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
                /**
                 * @var CronExpression $cron
                 */
                $cron = CronExpression::factory($task->getExpression());
                $cron->isDue();
                if($task->getLastExecution()->format('Y-m-d H:i:s') > $cron->getPreviousRunDate()->format('Y-m-d H:i:s')){
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

                    $returnCode = $application->run($argInput, $outputCommand);
                    dump($returnCode);
                    dump($cron->getNextRunDate()->format('Y-m-d H:i:s'));
                    dump($cron->getPreviousRunDate()->format('Y-m-d H:i:s'));
                    /*$task->setLastReturnCode(1);
                    $this->objectManager->persist($task);
                    $this->objectManager->flush();*/
                }
                //$returnCode = $command->run($commandInput, $output);
                /*
                 * Reste à découper les arguments et options de la commande pour ensuite le mettre dans l'arrayInput
                 * Puis vérifier la dernière date d'execution avec le $cron->getPrevious...
                 * Puis lancer et modifier l'entité. Modifié la dernière date d'exécution, le retour de la commande etc.
                 */
            }
        }


        $output->writeln("Cron execute command has successfully done.\n");
    }

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
        return [];
    }

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