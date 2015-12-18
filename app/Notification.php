<?php

namespace Nasqueron\Notifications;

class Notification {

    ///
    /// From who?
    ///

    /**
     * The notification's source service (e.g. GitHub, Phabricator, Jenkins)
     *
     * @var string
     */
    public $service;

    ///
    /// For whom?
    ///

    /**
     * The notification's target project (e.g. Wikimedia Nasqueron, Wolfplex)
     *
     * @var string
     */
    public $project;

    /**
     * The notification's target group (e.g. Tasacora, Operations)
     *
     * @var string
     */
    public $group;

    ///
    /// WHAT?
    ///

    /**
     * The notification's source payload, data or message
     *
     * @var mixed
     */
    public $rawContent;

    /**
     * The notification's type (e.g. "commits", "task")
     *
     * @var string
     */
    public $type;

    /**
     * The notification's text
     *
     * @var string
     */
    public $text;

    /**
     * The notification's URL, to be used as the main link for widgets
     *
     * @var string
     */
    public $link;

}
