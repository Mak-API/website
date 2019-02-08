<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct();
        $this->objectManager = $objectManager;
    }

    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Create an user.')
            ->setHelp('This command allow you to create an user.')
            ->setDefinition(array(
                new InputArgument('email', InputArgument::REQUIRED, 'The email of the user.'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password.'),
                new InputArgument('roles', InputArgument::IS_ARRAY, 'The roles of the user.')
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '==============================',
            '       Create an User        ',
            '==============================',
            '',
        ]);

        $email = $input->getArgument('email');
        if ($this->objectManager->getRepository(User::class)->findOneBy(["email" => $email])) {
            throw new \Exception("{$email} already used.");
        }

        $password = $input->getArgument('password');

        $roles = $input->getArgument('roles');
        foreach ($roles as $index => $role) {
            if (! in_array($role, User::getExistingRoles())) {
                unset($roles[$index]);
            }
        }


        $user = new User();
        $user->setEmail($email)
            ->setLogin($email)
            ->setPassword($password)
            ->setRoles($roles);
        $this->objectManager->persist($user);
        $this->objectManager->flush();

        $output->writeln("<comment>Created user {$user->getEmail()}.</comment> \n");
    }
}