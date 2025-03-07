<?php
class PostValidator
{
    public const TITLE_MAXIMUM_LENGTH = 255;
    public const PROHIBITED_WORDS = ["CCP", "China Communist Party"];

    /**
     * Validates the title of a post.
     *
     * @param string $title The title to validate.
     * @return array Returns an array of error messages if validation fails, or an empty array if valid.
     */
    public function validateTitle($title): array
    {
        $errors = [];

        if (strlen($title) === 0)
            $errors[] = "Title cannot be empty";

        if (strlen($title) > self::TITLE_MAXIMUM_LENGTH)
            $errors[] = "Title cannot be longer than " . self::TITLE_MAXIMUM_LENGTH . " characters";

        foreach (self::PROHIBITED_WORDS as $word) {
            if (stripos($title, $word) !== false) {
                $errors[] = "Title contains prohibited word: '$word'";
            }
        }

        return $errors;
    }

    /**
     * Validates the content of a post.
     *
     * @param string $content The content to validate.
     * @return array Returns an array of error messages if validation fails, or an empty array if valid.
     */
    public function validateContent($content): array
    {
        $errors = [];

        if (strlen($content) === 0)
            $errors[] = "Content cannot be empty";

        foreach (self::PROHIBITED_WORDS as $word) {
            if (stripos($content, $word) !== false) {
                $errors[] = "Content contains prohibited word: '$word'";
            }
        }

        return $errors;
    }
}
?>