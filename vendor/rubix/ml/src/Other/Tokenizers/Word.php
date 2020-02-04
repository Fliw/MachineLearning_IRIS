<?php

namespace Rubix\ML\Other\Tokenizers;

/**
 * Word
 *
 * This tokenizer matches words with 1 or more characters.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class Word implements Tokenizer
{
    /**
     * The regular expression to match words in a sentence.
     *
     * @var string
     */
    protected const WORD_REGEX = '/\w+/u';

    /**
     * Tokenize a block of text.
     *
     * @param string $string
     * @return string[]
     */
    public function tokenize(string $string) : array
    {
        $tokens = [];

        preg_match_all(self::WORD_REGEX, $string, $tokens);

        return $tokens[0];
    }
}
