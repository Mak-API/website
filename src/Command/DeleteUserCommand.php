<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteUserCommand extends Command
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var array
     */
    private $users;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        $this->users = $this->objectManager->getRepository(User::class)->findAll();

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('user:delete:deactivates')
            ->setDescription(
                'Delete all users which were deactivates since X days. 
                Specified X days with argument. 
                Default 5 days.'
            )
            ->setHelp('This command allow you to delete users which were deactivates since X days.')
            ->setDefinition(array(
                new InputOption(
                    'day',
                    '-d',
                    InputOption::VALUE_OPTIONAL,
                    'How many deactivates days ? Default 60',
                    60
                ),
                new InputOption(
                    'force',
                    '-f',
                    InputOption::VALUE_OPTIONAL,
                    'Need to be force',
                    false
                )
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $days = $input->getOption('day');
        $force = $input->getOption('force');

        if(!is_numeric($days)){
            throw new \RuntimeException(
                'Day need to be an integer'
            );
        }

        if(!is_null($force)){
            throw new \RuntimeException(
                'You need use \'--force\' or \'-f\' to use this command with no value'
            );
        }

        $now = new \DateTime();
        $output->writeln([
            '==========================================',
            '     Delete deactivates/unverified users     ',
            "        Selected days : $days days        ",
            '==========================================',
            '',
        ]);

        foreach($this->users as $user){
            $interval = date_diff($user->getUpdatedAt(), $now);
            if($interval->days >= $days && ($user->getStatus() === -1 || $user->getVerified() === false)){
                $this->objectManager->remove($user);
                $this->objectManager->flush();
            }
        }


        $output->writeln("All inactive and unverified (since $days days ago) users were deleted. \n");
    }
}