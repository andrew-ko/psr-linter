<?php

namespace Psrlint\Util;

use PhpParser\ParserFactory;
use PhpParser\Lexer;

function createAst($text)
{
    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

    try {
        return $parser->parse($text);
    } catch (\PhpParser\Error $e) {
        throw new \Psrlint\Error($e->getMessage());
    }
}

# function initLexer()
# {
#     return new Lexer\Emulative([
#         'usedAttributes' => [
#             'comments',
#             'startLine',
#             'endLine',
#             'startTokenPos',
#             'endTokenPos',
#             'startFilePos',
#             'endFilePos'
#         ]
#     ]);
# }
