<?php
namespace Src\User\Validators;

class UserValidator
{
    public const MINIMUM_USERNAME_LENGTH = 6;
    public const MINIMUM_PASSWORD_LENGTH = 8;

    /**
     * Regex pattern for matching letters, numbers and underscores.
     */
    public const USERNAME_REGEX_PATTERN = '/^[a-zA-Z0-9_]+$/';

    /**
     * Regex pattern for matching letters, numbers and special characters.
     */
    public const PASSWORD_REGEX_PATTERN = '/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?]+$/';

    /**
     * Validates the username.
     *
     * @param string $username The username to validate.
     * @return array Returns an array of error messages if validation fails, or an empty array if valid.
     */
    public function validateUsername(string $username): array
    {
        $errors = [];

        if (strlen($username) < self::MINIMUM_USERNAME_LENGTH)
            $errors[] = "Username must be longer than " . self::MINIMUM_USERNAME_LENGTH . " characters";

        if (!preg_match(self::USERNAME_REGEX_PATTERN, $username)) {
            $errors[] = "Username must only contain letters, numbers, and underscores.";
        }

        return $errors;
    }

    /**
     * Validates the password.
     * @param string $password The password to validate.
     * @return array Returns an array of error messages if validation fails, or an empty array if valid.
     */
    public function validatePassword(string $password): array
    {
        $errors = [];

        if (strlen($password) < self::MINIMUM_PASSWORD_LENGTH)
            $errors[] = "Password must be longer than " . self::MINIMUM_PASSWORD_LENGTH . " characters";

        if (!preg_match(self::PASSWORD_REGEX_PATTERN, $password)) {
            $errors[] = "Password must only contain letters, numbers, and special characters.";
        }

        return $errors;
    }
}
?>