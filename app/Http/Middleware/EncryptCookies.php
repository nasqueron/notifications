<?php

namespace Nasqueron\Notifications\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as BaseEncrypter;

class EncryptCookies extends BaseEncrypter {

    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var string[]
     */
    protected $except = [
        //
    ];
}
