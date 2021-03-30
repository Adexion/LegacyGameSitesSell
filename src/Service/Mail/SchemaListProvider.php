<?php

namespace MNGame\Service\Mail;

use RuntimeException;

class SchemaListProvider
{
    private array $schemaList = [
        '1' => [
            'replace' => '%token%',
            'text' => '<p>Link do resetu hasła: <a href="http://mngame.pl/reset/%token%">https://mngame.pl/reset/%token%</a></p>',
            'title' => 'Resetowanie hasła MNGame.pl'
        ],
        '404' => [
            'replace' => '%error%',
            'text' => '<p>Wystąpił krytyczny błąd w API: </p><br />: %error%',
            'title' => 'API CRITICAL ERROR'
        ],
        '402' => [
            'replace' => ['%username%', '%code%'],
            'text' => '<p>Użytkownik: %username% chce doładować konto!</p><p>Kod to: %code%</p>',
            'title' => 'Doładowanie konta prepaid przez PaySafeCard'
        ],
        'contact' => [
            'replace' => ['%email%', '%content%'],
            'text' => '<p>Osoba %email% wyłała do nas email<br/><p>%content%</p>',
            'title' => 'Kontak przez formularz kontaktowy'
        ]
    ];

    public function provide(string $schemaId): array
    {
        if (!isset($this->schemaList[$schemaId])) {
            throw new RuntimeException('Email schema not set.');
        }

        return $this->schemaList[$schemaId];
    }
}
