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
     * const DEACTIVATES
     * Users who wish want to delete their account.
     */
    const DEACTIVATES=-1;
    /**
     * const UNVERIFIED
     * Users who aren't verified their account email.
     */
    const UNVERIFIED=0;
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var array
     */
    private $users;

    /**
     * DeleteUserCommand constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        $this->users = $this->objectManager->getRepository(User::class)->findAll();

        parent::__construct();
    }

    /**
     * Configure (option, argument, etc.) of our command
     */
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
                    'How many deactivates/unverified days ?\n
                    Write \'--day\' or \'-d\' with integer value.',
                    60
                ),
                new InputOption(
                    'force',
                    '-f',
                    InputOption::VALUE_OPTIONAL,
                    'Need to be force. \n 
                    Write \'--force\' or \'-f\' with no value.',
                    false
                ),
                new InputOption(
                    'all',
                    '-a',
                    InputOption::VALUE_OPTIONAL,
                    'Delete unverified and deactivates accounts. \n 
                    Write \'--all\' or \'-a\' with no value.',
                    false
                )
            ))
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $daysOption = $input->getOption('day');
        $forceOption = $input->getOption('force');
        $allOption = $input->getOption('all');
        $now = new \DateTime();

        if(!is_numeric($daysOption)){
            throw new \RuntimeException(
                'Day need to be an integer'
            );
        }

        if($forceOption !== null){
            throw new \RuntimeException(
                'You need use \'--force\' or \'-f\' to use this command with no value'
            );
        }

        $output->writeln([
            '==========================================',
            '     Delete deactivates/unverified users     ',
            "        Selected days : $daysOption days        ",
            '==========================================',
            '',
        ]);

        foreach($this->users as $user){
            $interval = date_diff($user->getUpdatedAt(), $now);
            if($interval->days >= $daysOption){
                if(($user->getStatus() === self::DEACTIVATES) || ($allOption === null && $user->getStatus() === self::UNVERIFIED)){
                    $this->objectManager->remove($user);
                    $this->objectManager->flush();
                    $output->writeln("User <comment>".$user->getEmail()."</comment> has been removed \n");
                }
            }
        }


        $output->writeln("All inactive and unverified (since $daysOption days ago) users were deleted. \n");
    }
}