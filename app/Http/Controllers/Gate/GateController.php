<?php

namespace Nasqueron\Notifications\Http\Controllers\Gate;

use Nasqueron\Notifications\Http\Controllers\Controller;

/**
 * Represents a controller handling an entry-point for API payloads
 */
class GateController extends Controller {
    /**
     * Handles GET requests
     */
    function onGet () {
        // Virtually all the push APIs will send they payloads
        // using a POST request, so we can provide a sensible
        // default GET error message.
        return view('gate/ispostonly');
    }
}
