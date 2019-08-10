<?php

namespace ModernGame\Service\Mail;

use RuntimeException;

class SchemaListProvider
{
    private $schemaList = [
        '1' => [
            'replace' => '%token%',
            'text' => '<p>Link do resetu hasła: <a href="http://ModernGame.pl/reset/%token%">http://ModernGame.pl/reset/%token%</a></p>',
            'title' => 'Resetowanie hasła ModernGame.pl'
        ],
        '404' => [
            'replace' => '%error%',
            'text' => '<p>Wystąpił krytyczny błąd w API: </p><br />: %error%',
            'title' => 'API CRITICAL ERROR'
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
