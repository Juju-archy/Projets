<?php

namespace App\Controller\Admin;

use App\Entity\CarriersInformation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CarriersInformationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CarriersInformation::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('carrierName'),
            TextField::new('country'),
            IntegerField::new('weightMin'),
            IntegerField::new('weightMax'),
            MoneyField::new('price')->setCurrency('EUR'),
        ];
    }

}
