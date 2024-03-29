<?php

namespace Nasqueron\Notifications\Contracts;

interface APIFactory {

    /**
     * Gets an instance of the API client class
     *
     * @param string $endPoint The API end point
     */
    public function get (string $endPoint) : APIClient;

}
