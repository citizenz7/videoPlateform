<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\File;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VideoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Video::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('title', 'Titre de la vidéo')
                ->setColumns(6),
            ImageField::new('url', 'Fichier vidéo')
                ->hideOnIndex()
                ->setColumns(6)
                ->setUploadDir('public/uploads/videos')
                ->setBasePath('uploads/videos')
                ->setUploadedFileNamePattern('[name]-[timestamp].[extension]')
                ->setRequired(false)
                ->setFileConstraints([
                    new File([
                        'maxSize' => '25M',
                        'mimeTypes' => [
                            'video/mp4',
                        ],
                        'mimeTypesMessage' => 'Veuillez sélectionner un fichier vidéo au format .mp4',
                        'maxSizeMessage' => 'Veuillez sélectionner un fichier de moins de 25Mo',
                    ])
                ]),
                IntegerField::new('duration', 'Durée de la vidéo')
                    ->setColumns(2)
                    ->setHelp('Duree de la vidéo en secondes'),
                ImageField::new('image', 'Image')
                    ->setColumns(6)
                    ->setUploadDir('public/uploads/videos')
                    ->setBasePath('uploads/videos')
                    ->setUploadedFileNamePattern('[name]-[timestamp].[extension]')
                    ->setRequired(false)
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
                    ])
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des vidéos')
            ->setPageTitle('edit', 'Modifier unu vidéo')
            ->setPageTitle('new', 'Ajouter unu vidéo')
            ->setPageTitle('detail', 'Détail de la vidéo')
            ->setDefaultSort(['id' => 'DESC'])
            ->showEntityActionsInlined(true);
    }

    public function configureActions(Actions $actions): Actions{
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL, Action::NEW, Action::DELETE)
            ->update(Crud::PAGE_INDEX,Action::NEW,function(Action $action){
                return $action->setIcon('fas fa-newspaper pe-1')->setLabel('Ajouter un vidéo');
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
