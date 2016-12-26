<?php

namespace Nasqueron\Notifications\Contracts;

interface APIFactory {

    /**
     * Gets an instance of the API client class
     *
     * @param string $endPoint The API end point
     * @return APIClient
     */
    public function get ($endPoint);

}
