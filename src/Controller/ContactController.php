<?php

namespace ModernGame\Controller;

use ModernGame\Service\Content\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    public function setMessageContact(Request $request, ContactService $contact)
    {
        return new JsonResponse(['ticket' => $contact->setContactMessage($request)]);
    }
}
