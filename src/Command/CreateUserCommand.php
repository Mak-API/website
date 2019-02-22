<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;

class CreateUserCommand extends Command
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var array
     */
    private $usernamesList;
    /**
     * @var array
     */
    private $emailsList;
    /**
     * @var string
     */
    private $userPassword;

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct();
        $this->objectManager = $objectManager;

        $usersList = $this->objectManager->getRepository(User::class)->findAll();
        foreach ($usersList as $user) {
            $this->usernamesList[] = $user->getLogin();
            $this->emailsList[] = $user->getEmail();
        }
    }

    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Create an user.')
            ->setHelp('This command allow you to create an user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $output->writeln([
            '==============================',
            '       Create an User        ',
            '==============================',
            '',
        ]);

        $usernameQuestion = new Question('Enter an unknown username : ', 'username');
        $usernameQuestion->setAutocompleterValues($this->usernamesList);
        $usernameQuestion->setValidator(function($answer){
           if(in_array($answer, $this->usernamesList)){
               throw new \RuntimeException(
                   'New user need an unique username.'
               );
           }
           return $answer;
        });
        $username = $helper->ask($input, $output, $usernameQuestion);

        $emailQuestion = new Question('Enter an unknown email : ', 'email@email.com');
        $emailQuestion->setAutocompleterValues($this->emailsList);
        $emailQuestion->setValidator(function($answer){
           if(in_array($answer, $this->emailsList)){
               throw new \RuntimeException(
                   'New user need an unique email.'
               );
           }
           if(!filter_var($answer, FILTER_VALIDATE_EMAIL)){
               throw new \RuntimeException(
                   "Email address '$answer' is considered invalid."
               );
           }
           return $answer;
        });
        $email = $helper->ask($input, $output, $emailQuestion);

        $passwordQuestion = new Question('Enter password : ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);
        $this->userPassword = $helper->ask($input, $output, $passwordQuestion);

        $passwordConfirmQuestion = new Question('Confirm password : ');
        $passwordConfirmQuestion->setHidden(true);
        $passwordConfirmQuestion->setHiddenFallback(false);
        $passwordConfirmQuestion->setValidator(function($answer){
            if($answer !== $this->userPassword){
                throw new \RuntimeException(
                    'Confirm password is different.'
                );
            }
            return $answer;
        });
        $confirmPassword = $helper->ask($input, $output, $passwordConfirmQuestion);


        $rolesQuestion = new ChoiceQuestion(
            'Please select user role (default is ROLE_USER)',
            ['ROLE_USER', 'ROLE_ADMIN'],
            0
        );
        $rolesQuestion->setMultiselect(true);
        $rolesQuestion->setErrorMessage('Role %s is invalid');
        $roles = $helper->ask($input, $output, $rolesQuestion);

        $user = new User();
        $user->setEmail($email)
            ->setLogin($username)
            ->setPassword($confirmPassword)
            ->setRoles($roles);
        $this->objectManager->persist($user);
        $this->objectManager->flush();

        $output->writeln("<comment>Created user {$user->getEmail()}.</comment> \n");
    }
}