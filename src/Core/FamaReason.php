<?php

namespace App\Core;

final class FamaReason
{
    const TOKEN_IS_NOT_VALID = 10;
    const TOKEN_IS_EXPIRED = 20;
    const TOKEN_HAS_WRONG_ENCODING_ALGORITHM = 30;
    const USER_NOT_FOUND = 40;
    const USER_PASSWORD_IS_NOT_VALID = 41;
    const USER_ACCOUNT_IS_DISABLED = 42;
    const USER_SESSION_ID_IS_NOT_VALID = 43;
    const USER_ALREADY_EXISTS = 44;
    const USER_ACCOUNT_MUST_TO_BE_ACTIVATED = 45;
    const USER_HAS_LIMITED_RIGHTS = 46;
    const JSON_IS_NOT_VALID = 50;
    const INPUT_PARAM_IS_NOT_VALID = 60;
    const INPUT_PARAM_NOT_FOUND = 70;
    const EMAIL_NOT_SENT = 80;

    public static $reasonTexts = [
        self::TOKEN_IS_NOT_VALID => 'Token is not valid',
        self::TOKEN_IS_EXPIRED => 'Token is expired',
        self::TOKEN_HAS_WRONG_ENCODING_ALGORITHM => 'Token has wrong encoding algorithm',
        self::USER_NOT_FOUND => 'User not found',
        self::USER_PASSWORD_IS_NOT_VALID => 'User password is not valid',
        self::USER_ACCOUNT_IS_DISABLED => 'User account is disabled',
        self::USER_SESSION_ID_IS_NOT_VALID => 'User session id is not valid',
        self::USER_ALREADY_EXISTS => 'User already exists',
        self::USER_ACCOUNT_MUST_TO_BE_ACTIVATED => 'User account must to be activated',
        self::USER_HAS_LIMITED_RIGHTS => 'Sorry, you have not enough right for this action',
        self::JSON_IS_NOT_VALID => 'Json is not valid',
        self::INPUT_PARAM_IS_NOT_VALID => 'Input param is not valid',
        self::INPUT_PARAM_NOT_FOUND => 'Input param not found',
        self::EMAIL_NOT_SENT => 'Sorry, we have a internal problem belongs to send email',
    ];


    /**
     * This class cannot be instantiated.
     */
    private function __construct()
    {
    }
}