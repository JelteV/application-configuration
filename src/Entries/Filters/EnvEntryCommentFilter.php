<?php

namespace JelteV\ApplicationConfiguration\Entries\Filters;

/**
 * Represent an filter to separate comments from application setting data for .env application settings.
 */
class EnvEntryCommentFilter
{
    /**
     * By parsing the line any line comments will be removed.
     *
     * When the line does contain any line comment the line will be returned unchanged.
     *
     * @param string $line The line to parse.
     * @return null|string When the line is successfully parsed (with or without any line comment) a string is returned
     * representing the specified line without containing any line comments.
     */
    public static function parseLine(string $line): ?string
    {
        $parsedLine     = null;
        $commentLine    = rtrim($line);

        // todo: make this more dynamic, for examples implement the strategy pattern.
        while (static::hasLineComments($commentLine)) {
            if (static::isLineCommentPound($commentLine)) {
                $commentLine = static::removeLineComment($commentLine, static::createRegexPatternLineCommentPound());
            } elseif (static::isLineCommentDoubleForwardSlash($commentLine)) {
                $commentLine = static::removeLineComment($commentLine, static::createRegexPatternLineCommentDoubleForwardSlash());
            } elseif (static::isLineCommentDoubleDash($commentLine)) {
                $commentLine = static::removeLineComment($commentLine, static::createRegexPatternLineCommentDoubleDash());
            } elseif (static::isLineCommentSemicolon($commentLine)) {
                $commentLine = static::removeLineComment($commentLine, static::createRegexPatternLineCommentSemicolon());
            }
        }

        if (!empty($commentLine)) {
            $parsedLine = $commentLine;
        }

        return $parsedLine;
    }

    /**
     * Check if the given line contains any supported comments.
     *
     * @param null|string $line The line to check if it contains any comments.
     * @return bool Returns true when the given line contains any supported comment.
     */
    private static function hasLineComments(?string $line): bool
    {
        $hasComment = false;

        if ($line !== null) {
            $hasComment = static::isLineCommentPound($line)
                || static::isLineCommentDoubleForwardSlash($line)
                || static::isLineCommentDoubleDash($line)
                || static::isLineCommentSemicolon($line);
        }

        return $hasComment;
    }


    /**
     * Determine if the given value is a line complete/partially commented out using the # character.
     *
     * @param string $line The line to test.
     * @return bool Returns true if the line is completely/partially commented out using the # character.
     */
    private static function isLineCommentPound(string $line): bool
    {
        return static::preformPregMatchTest($line, static::createRegexPatternLineCommentPound());
    }

    /**
     * Determine if the given value is a line complete/partially commented out using the // characters.
     *
     * @param string $line The line to test.
     * @return bool Returns true if the line is completely/partially commented out using the // characters.
     */
    private static function isLineCommentDoubleForwardSlash(string $line): bool
    {
        return static::preformPregMatchTest($line, static::createRegexPatternLineCommentDoubleForwardSlash());
    }

    /**
     * Determine if the given value is a line complete/partially commented out  using the -- characters.
     *
     * @param string $line The line to test.
     * @return bool Returns true if the line is completely/partially commented out using the -- characters.
     */
    private static function isLineCommentDoubleDash(string $line): bool
    {
        return static::preformPregMatchTest($line, static::createRegexPatternLineCommentDoubleDash());
    }

    /**
     * Determine if the given value is a line completely/partially commented out using a semicolon (;).
     *
     * @param string $line The line to test.
     * @return bool Returns true if the line is completely/partially commented out using a semicolon.
     */
    private static function isLineCommentSemicolon(string $line): bool
    {
        return static::preformPregMatchTest($line, static::createRegexPatternLineCommentSemicolon());
    }

    /**
     * Create the regex pattern for identifying a line comment using the # character.
     *
     * @return null|string Returns a regex pattern string for the line comment.
     */
    private static function createRegexPatternLineCommentPound(): ?string
    {
        return static::createRegexPattern('#');
    }

    /**
     * Create the regex pattern for identifying a line comment using the // characters.
     *
     * @return null|string Returns a regex pattern string for the line comment.
     */
    private static function createRegexPatternLineCommentDoubleForwardSlash(): ?string
    {
        return static::createRegexPattern('\/\/');
    }

    /**
     * Create the regex pattern for identifying a line comment using the -- characters.
     *
     * @return null|string Returns a regex pattern string for the line comment.
     */
    private static function createRegexPatternLineCommentDoubleDash(): ?string
    {
        return static::createRegexPattern('--');
    }

    /**
     * Create the regex pattern for identifying a line comment using the ; character.
     *
     * @return null|string Returns a regex pattern string for the line comment.
     */
    private static function createRegexPatternLineCommentSemicolon(): ?string
    {
        return static::createRegexPattern(';');
    }

    /**
     * Create a regex pattern used to find line comments.
     *
     * @param string $commentCharacters One or more characters that are used as identifier for a line comment.
     * @return null|string Returns a string containing the regex pattern.
     */
    private static function createRegexPattern(string $commentCharacters): ? string
    {
        $regexPattern = null;

        if (!empty($commentCharacters)) {
            $regexPattern = "/(^\s*{$commentCharacters}.*$)|([^'\"]{$commentCharacters}.*[^'\"]$)/";
        }

        return $regexPattern;
    }

    /**
     * Preform the regular expression test to see if the given value matches the condition in the regex pattern.
     *
     * @param string $value The value to test.
     * @param string $regexPattern The regular expression pattern holding the test conditions.
     * @return bool Returns true when the given value matches the condition specified in the regex pattern.
     */
    private static function preformPregMatchTest(string $value, string $regexPattern): bool
    {
        $matched = false;
        $value = ltrim($value);

        if (!empty($value)) {
            $matched = (bool) preg_match($regexPattern, $value) && preg_last_error() === PREG_NO_ERROR;
        }

        return $matched;
    }

    /**
     * Removes the line comment from the given value that matches the conditions of the regex pattern.
     *
     * When the given value does not contain a line comment that matches the conditions given in the regex pattern,
     * the value will be returned unchanged.
     *
     * @param string $value The value to remove the line comment from.
     * @param string $regexPattern The regular expression pattern with the conditions for matching a line comment.
     * @return null|string Returns a string without the specified line comment on success, otherwise null.
     */
    private static function removeLineComment(string $value, string $regexPattern): ?string
    {
        $newValue       = null;
        // Replace found comment with an empty string.
        $replacedValue  = preg_replace($regexPattern, '', $value);

        if (is_string($replacedValue) && preg_last_error() === PREG_NO_ERROR) {
            if (!empty($trimmedValue = trim($replacedValue))) {
                $newValue = $trimmedValue;
            }
        }

        return $newValue;
    }
}
