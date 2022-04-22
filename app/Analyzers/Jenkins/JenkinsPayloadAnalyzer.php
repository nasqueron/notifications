<?php

namespace Nasqueron\Notifications\Analyzers\Jenkins;

use Nasqueron\Notifications\Analyzers\BasePayloadAnalyzer;

class JenkinsPayloadAnalyzer extends BasePayloadAnalyzer {

    /**
     * The name of the service, used to get specific classes and config
     */
    const SERVICE_NAME = "Jenkins";

    ///
    /// Payload custom properties
    ///

    /**
     * Gets the name of the item, ie here of the job.
     *
     * @var string
     */
    public function getItemName () : string {
        return $this->payload->name;
    }

    ///
    /// Notify only on failure helper methods
    ///

    /**
     * Tries to get build status.
     *
     * @param out string &$status
     * @return bool indicates if the build status is defined in the payload
     */
    private function tryGetBuildStatus (string &$status) : bool {
        if (!isset($this->payload->build->status)) {
            return false;
        }

        $status = $this->payload->build->status;
        return true;
    }

    /**
     * @return bool
     */
    public function shouldNotifyOnlyOnFailure () : bool {
        return in_array(
            $this->getItemName(),
            $this->configuration->notifyOnlyOnFailure
        );
    }

    /**
     * Determines if the build status is a failure.
     *
     * @return bool
     */
    public function isFailure () : bool {
        $status = "";

        if (!$this->tryGetBuildStatus($status)) {
            return false;
        }

        return $status === "FAILURE"
            || $status === "ABORTED"
            || $status === "UNSTABLE";
    }

    /**
     * Indicates if we should handle this payload to trigger a notification.
     *
     * @return bool if false, this payload is to be ignored for notifications
     */
    public function shouldNotify () : bool {
        return $this->isFailure() || !$this->shouldNotifyOnlyOnFailure();
    }

}
