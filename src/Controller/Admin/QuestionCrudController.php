<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Enum\Difficulty;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class QuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Question::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('title'),

            TextField::new('optionA', 'Option A'),
            TextField::new('optionB', 'Option B'),
            TextField::new('optionC', 'Option C'),
            TextField::new('optionD', 'Option D'),

            ChoiceField::new('correctAnswer')
                ->setChoices([
                    'A' => 'a',
                    'B' => 'b',
                    'C' => 'c',
                    'D' => 'd',
                ])
                ->renderExpanded(false)
                ->setHelp('Letter of the right option'),


            ChoiceField::new('difficulty')
                ->setChoices(Difficulty::cases()),

            AssociationField::new('contest', 'Contest')
                ->setRequired(true)
                ->setFormTypeOption('choice_label', 'name')
        ];
    }
}
