<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
            ->setName('user:delete:inactive')
            ->setDescription(
                'Delete all users which were inactive since X days. 
                Specified X days with argument. 
                Default 5 days.'
            )
            ->setHelp('This command allow you to delete users which were inactive since X days.')
            ->setDefinition(array(
                new InputOption(
                    'day',
                    '-d',
                    InputOption::VALUE_OPTIONAL,
                    'How many inactive days ? Default 60',
                    60
                )
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $output->writeln([
            '==========================================',
            '     Delete inactive/unverified users     ',
            '==========================================',
            '',
        ]);

        if(!is_numeric($input->getOption('day'))){
            $output->writeln([
                '<fg=black;bg=cyan>================================================',
                '     --day option need to be numeric type !     ',
                '================================================</>',
                '',
            ]);
            die;
        }

        $output->writeln("All inactive and unverified (since 60 days ago) users were deleted. \n");
    }
}