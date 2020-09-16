<?php

namespace ModernGame\Service\Mail;

use RuntimeException;

class SchemaListProvider
{
    private array $schemaList = [
        '1' => [
            'replace' => '%token%',
            'text' => '<p>Link do resetu hasła: <a href="http://ModernGame.pl/reset/%token%">http://ModernGame.pl/reset/%token%</a></p>',
            'title' => 'Resetowanie hasła ModernGame.pl'
        ],
        '404' => [
            'replace' => '%error%',
            'text' => '<p>Wystąpił krytyczny błąd w API: </p><br />: %error%',
            'title' => 'API CRITICAL ERROR'
        ],
        '402' => [
            'replace' => ['%paySafeCard%', '%code%'],
            'text' => '<p>Użytkownik: %username% chce doładować konto!</p><p>Kod to: %code%</p>',
            'title' => 'Doładowanie konta prepaid przez PaySafeCard'
        ]
    ];

    public function provide($schemaId): array
    {
        if (!isset($this->schemaList[$schemaId])) {
            throw new RuntimeException('Email schema not set.');
        }

        return $this->schemaList[$schemaId];
    }
}
