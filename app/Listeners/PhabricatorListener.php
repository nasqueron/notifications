<?php

namespace Nasqueron\Notifications\Listeners;

use Nasqueron\Notifications\Actions\ActionError;
use Nasqueron\Notifications\Actions\NotifyNewCommitsAction;
use Nasqueron\Notifications\Events\GitHubPayloadEvent;
use Nasqueron\Notifications\Events\ReportEvent;
use Nasqueron\Notifications\Phabricator\PhabricatorAPI;
use Nasqueron\Notifications\Phabricator\PhabricatorAPIException;

use Event;

class PhabricatorListener {
    ///
    /// GitHub → Phabricator
    ///

    /**
     * Handles payload events
     *
     * @param GitHubPayloadEvent $event The GitHub payload event
     */
    public function onGitHubPayload (GitHubPayloadEvent $event) {
        if ($event->event === 'push') {
            $this->notifyNewCommits($event);
        }
    }

    /**
     * @return string the repository call sign "OPS", or "" if not in Phabricator
     */
    private static function getCallSign (PhabricatorAPI $api, $remoteURI) {
        $reply = $api->call(
            'repository.query',
            [ 'remoteURIs[0]' => $remoteURI ]
        );

        if (!count($reply)) {
            return "";
        }

        return PhabricatorAPI::getFirstResult($reply)->callsign;
    }

    /**
     * Notifies Phabricator there are new commits to pull
     */
    public function notifyNewCommits (GitHubPayloadEvent $event) {
        $api = PhabricatorAPI::forProject($event->door);
        if (!$api) {
            // We don't have a Phabricator instance for this project.
            return;
        }

        $callSign = static::getCallSign(
            $api,
            $event->payload->repository->clone_url
        );

        if ($callSign === "") {
            return;
        }

        $actionToReport = new NotifyNewCommitsAction($callSign);
        try {
            $api->call(
                'diffusion.looksoon',
                [ 'callsigns[0]' => $callSign ]
            );
        } catch (PhabricatorAPIException $ex) {
            $actionToReport->attachError(new ActionError($ex));
        }

        Event::fire(new ReportEvent($actionToReport));
    }

    ///
    /// Events listening
    ///

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe (\Illuminate\Events\Dispatcher $events) {
        $class = 'Nasqueron\Notifications\Listeners\PhabricatorListener';
        $events->listen(
            'Nasqueron\Notifications\Events\GitHubPayloadEvent',
            "$class@onGitHubPayload"
        );
    }
}
