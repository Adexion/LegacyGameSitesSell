<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\Ticket;
use ModernGame\Database\Entity\User;
use ModernGame\Field\EntityField;

class TicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ticket::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nazwa'),
            EmailField::new('email', 'E-mail'),
            TextField::new('type', 'Typ wiadomości'),
            TextField::new('subject', 'Temat'),
            TextEditorField::new('message', 'Wiadomość'),
            EntityField::new('user', 'Użytkownik')
                ->setClass(User::class, 'username')
        ];
    }
}
