<?php
namespace Src\Comment\Validators;

class CommentValidator
{
    public const TITLE_MAXIMUM_LENGTH = 255;
    public const PROHIBITED_WORDS = ["CCP", "China Communist Party"];

    /**
     * Validates the content of a post.
     *
     * @param string $title The content to validate.
     * @return array Returns an array of error messages if validation fails, or an empty array if valid.
     */
    public function validateContent($title): array
    {
        $errors = [];

        if (strlen($title) === 0)
            $errors[] = "Comment cannot be empty";

        foreach (self::PROHIBITED_WORDS as $word) {
            if (stripos($title, $word) !== false) {
                $errors[] = "Title contains prohibited word: '$word'";
            }
        }

        return $errors;
    }
}
?>