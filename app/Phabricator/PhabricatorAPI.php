<?php

namespace Nasqueron\Notifications\Phabricator;

class PhabricatorAPI {
    /**
     * The Phabricator main URL
     *
     * @var string
     */
    private $instance;

    /**
     * The token generated at /settings/panel/apitokens/ to query the API
     *
     * @var string
     */
    private $apiToken;

    /**
     * Initializes a new instance of the Phabricator API class
     *
     * @param string $instance The Phabricator main URL, without trailing slash
     * @param string $apiToken The token generated at /settings/panel/apitokens/
     */
    public function __construct ($instance, $apiToken) {
        $this->instance = $instance;
        $this->apiToken = $apiToken;
    }

    /**
     * Calls a Conduit API method
     *
     * @param $method The method to call (e.g. repository.create)
     * @param $arguments The arguments to use
     */
    public function call ($method, $arguments = []) {
        $url = $this->instance . '/api/' . $method;
        $arguments['api.token'] = $this->apiToken;

        $reply = json_decode(static::post($url, $arguments));

        if ($reply->error_code !== NULL) {
            throw new PhabricatorAPIException(
                $reply->error_code,
                $reply->error_info
            );
        }

        return $reply->result;
    }

    ///
    /// CURL session
    ///

    public static function getPostFields ($arguments) {
        $items = [];
        foreach ($arguments as $key => $value) {
            $items[] = urlencode($key) . '=' . urlencode($value);
        }
        return implode('&', $items);
    }

    public static function post ($url, $arguments) {
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
        return $result;
    }
}
