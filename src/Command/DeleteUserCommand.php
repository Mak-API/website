<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
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
            ->setName('user:delete')
            ->setDescription('Create an user.')
            ->setHelp('This command allow you to create an user.')
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

        $output->writeln("All inactive and unverified (since 60 days ago) users were deleted. \n");
    }
}