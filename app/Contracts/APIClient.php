<?php

namespace Nasqueron\Notifications\Contracts;

interface APIClient {

    /**
     * Sets API end point
     *
     * @param string $url The API end point URL
     * @return void
     */
    public function setEndPoint ($url);

    /**
     * Calls an API method
     *
     * @param string $method The method to call
     * @param array $arguments The arguments to use
     * @return mixed The API result
     */
    public function call ($method, $arguments = []);

}
