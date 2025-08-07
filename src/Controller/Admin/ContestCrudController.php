<?php

namespace App\Controller\Admin;

use App\Entity\Contest;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contest::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),

            TextField::new('name', 'Contest name'),

            TextField::new('imageUrl', 'Image URL')
                ->setHelp('Absolute URL or path to the banner/cover image'),

            IntegerField::new('totalQuestions', 'Total questions'),

            ArrayField::new('allowedJokers', 'Allowed jokers')
                ->onlyOnForms(),
            ArrayField::new('difficultyCurve', 'Difficulty curve')
                ->onlyOnForms(),

            AssociationField::new('questions')
                ->hideOnForm()
                ->setTemplatePath('@EasyAdmin/crud/field/association.html.twig'),

            DateTimeField::new('createdAt')
                ->hideOnForm(),
        ];
    }
}
