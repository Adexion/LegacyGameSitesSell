<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use MNGame\Field\CKEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\Ticket;
use MNGame\Database\Entity\User;
use MNGame\Field\EntityField;

class TicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ticket::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nazwa'),
            EmailField::new('email', 'E-mail'),
            TextField::new('type', 'Typ wiadomości'),
            TextField::new('subject', 'Temat'),
            CKEditorField::new('message', 'Wiadomość')->hideOnIndex(),
            DateTimeField::new('datetime', 'Data dostarczenia'),
            EntityField::new('user', 'Użytkownik')
                ->setClass(User::class, 'username')
        ];
    }
}
