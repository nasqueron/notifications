<?php

namespace Nasqueron\Notifications\Phabricator;

use Nasqueron\Notifications\Contracts\APIClient;
use Nasqueron\Notifications\Facades\Services;

class PhabricatorAPI implements APIClient {

    ///
    /// Private members
    ///

    /**
     * The Phabricator main URL
     *
     * @var string
     */
    private $endPoint;

    /**
     * The token generated at /settings/panel/apitokens/ to query the API
     *
     * @var string
     */
    private $apiToken;

    ///
    /// Constructors
    ///

    /**
     * Initializes a new instance of the Phabricator API class
     *
     * @param string $endPoint The Phabricator main URL, without trailing slash
     * @param string $apiToken The token generated at /settings/panel/apitokens/
     */
    public function __construct (string $endPoint, string $apiToken) {
        $this->endPoint = $endPoint;
        $this->apiToken = $apiToken;
    }

    /**
     * @throws \RuntimeException when the service isn't in credentials.json
     */
    public static function forInstance ($instance) : PhabricatorAPI {
        $service = Services::findServiceByProperty(
            'Phabricator',
            'instance',
            $instance
        );
        if ($service === null) {
            throw new \RuntimeException(
                "No credentials for Phabricator instance $instance."
            );
        }
        return new self($service->instance, $service->secret);
    }

    /**
     * @throws \RuntimeException when the service isn't in credentials.json
     */
    public static function forProject ($project) : PhabricatorAPI {
        $service = Services::findServiceByDoor('Phabricator', $project);
        if ($service === null) {
            throw new \RuntimeException(
                "No credentials for Phabricator project $project."
            );
        }
        return new self($service->instance, $service->secret);
    }

    ///
    /// APIClient implementation
    ///

    /**
     * Sets API end point
     *
     * @param string $url The API end point URL
     */
public function setEndPoint (string $url) : void {
        $this->endPoint = $url;
    }

    /**
     * Calls a Conduit API method
     *
     * @param string $method    The method to call (e.g. repository.create)
     * @param array  $arguments The arguments to use
     *
     * @return mixed The API result
     */
    public function call (string $method, array $arguments = []) : mixed {
        $url = $this->endPoint . '/api/' . $method;
        $arguments['api.token'] = $this->apiToken;

        $reply = json_decode(static::post($url, $arguments));

        if ($reply->error_code !== null) {
            throw new PhabricatorAPIException(
                $reply->error_code,
                $reply->error_info
            );
        }

        return $reply->result;
    }

    ///
    /// Helper methods
    ///

    /**
     * Gets the first result of an API reply.
     */
    public static function getFirstResult (iterable|\stdClass $reply) : mixed {
        if (is_object($reply) && property_exists($reply, 'data')) {
            $reply = $reply->data;
        }

        foreach ($reply as $value) {
            return $value;
        }
    }

    ///
    /// CURL session
    ///

    protected static function getPostFields ($arguments) : string {
        $items = [];
        foreach ($arguments as $key => $value) {
            $items[] = urlencode($key) . '=' . urlencode($value);
        }
        return implode('&', $items);
    }

    protected static function post ($url, $arguments) : bool|string {
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_POSTFIELDS => static::getPostFields($arguments),
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === false) {
            throw new \RuntimeException(
                "Can't reach Phabricator API endpoint: $url"
            );
        }

        return $result;
    }
}
