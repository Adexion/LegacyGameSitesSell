<?php

namespace ModernGame\Controller;

use ModernGame\Service\Content\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    const CONTACT_TOKEN_ADDRESS = 'https://gemdust.pl/token/';

    public function setMessageContact(Request $request, ContactService $contact)
    {
        return new JsonResponse([
            'message' => [
                'Dziękujemy za wysłanie formularza. Twoja konwersacja znajduje się pod poniższym adresem' =>
                    self::CONTACT_TOKEN_ADDRESS . $contact->setContactMessage($request)
            ],
            'timeout' => 20000
        ], JsonResponse::HTTP_BAD_REQUEST);
    }
}
