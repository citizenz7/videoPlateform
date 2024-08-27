<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\File;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    )
    {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            EmailField::new('email', 'Adresse e-mail')
                ->setColumns(3)
                ->setRequired(false),
            ChoiceField::new('roles', 'Rôle')
                ->setColumns(3)
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ])
                ->allowMultipleChoices()
                ->renderAsBadges([
                    'ROLE_USER' => 'info',
                    'ROLE_ADMIN' => 'success'
                ])
                ->setTemplatePath('admin/roles_displayed_index.html.twig'),
            TextField::new('password', 'Mot de passe')
                ->setFormType(RepeatedType::class)
                ->setRequired(false)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Mot de passe'
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe'
                    ],
                    'invalid_message' => 'Les mots de passe ne sont pas identiques',
                    'mapped' => false
                ])
                ->onlyOnForms(),
            TextField::new('firstname', 'Prénom')
                ->setColumns(3),
            TextField::new('lastname', 'Nom')
                ->setColumns(3),
            ImageField::new('image', 'Image de profil')
                ->setColumns(6)
                ->setRequired(false)
                ->setUploadDir('public/uploads/img/users')
                ->setBasePath('uploads/img/users')
                ->setUploadedFileNamePattern('[name]-[timestamp].[extension]')
                ->setFileConstraints([
                    new File([
                        'maxSize' => '500k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez sélectionner une image au format .png ou .jpg',
                        'maxSizeMessage' => 'Veuillez sélectionner une image de moins de 500ko',
                    ])
                    ]),
                    BooleanField::new('active', 'Actif')
                        ->setColumns(12),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        // Hash the password before saving
        $entityInstance->setPassword(
            $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword())
        );

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des utilisateurs')
            ->setPageTitle('edit', 'Modifier un utilisateur')
            ->setPageTitle('new', 'Ajouter un utilisateur')
            ->setPageTitle('detail', 'Détail d\'un utilisateur')
            ->setPaginatorPageSize(10)
            ->showEntityActionsInlined(true)
            ->setEntityLabelInSingular('utilisateur')
            ->setEntityLabelInPlural('utilisateurs')
            ->renderContentMaximized()
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL, Action::NEW, Action::DELETE)
            // ->add(Crud::PAGE_INDEX, Action::DETAIL, Action::DELETE)
            ->update(Crud::PAGE_INDEX,Action::NEW,function(Action $action){
                return $action->setIcon('fas fa-newspaper pe-1')->setLabel('Ajouter un utilisateur');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('')->addCssClass('text-dark');
            })
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('')->addCssClass('text-dark');
            })
            ->update(Crud::PAGE_INDEX,Action::DELETE,function(Action $action){
                return $action->setIcon('fas fa-trash text-danger')->setLabel('')->addCssClass('text-dark');
            });
    }
}
