<?php
class PostValidator
{
    public const PROHIBITED_WORDS = ["CCP", "China Communist Party"];

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