<?php

namespace Psrlint\Util;

use PhpParser\ParserFactory;
use PhpParser\Lexer;

function createAst($text)
{
    $lexer = initLexer();
    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7, $lexer);

    try {
        return $parser->parse($text);
    } catch (\PhpParser\Error $e) {
        throw new \Psrlint\Error($e->getMessage());
    }
}

function initLexer()
{
    return new Lexer\Emulative([
        'usedAttributes' => [
            'comments',
            'startLine',
            'endLine',
            'startTokenPos',
            'endTokenPos',
            'startFilePos',
            'endFilePos'
        ]
    ]);
}
