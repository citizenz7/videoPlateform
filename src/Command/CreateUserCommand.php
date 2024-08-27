<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Crée un compte utilisateur',
)]

// Créer un user directement en console :
// php bin/console app:create-user EMAIL PASSWORD

class CreateUserCommand extends Command
{
    private $entityManagerInterface;
    private $encoder;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        UserPasswordHasherInterface $encoder
    ) {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->encoder = $encoder;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $user = new User();

        $user->setEmail($input->getArgument('email'));

        $password = $this->encoder->hashPassword($user, $input->getArgument('password'));
        $user->setPassword($password);

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        $io->success('Nouveau compte utilisateur créé !');
        return Command::SUCCESS;
    }
}
