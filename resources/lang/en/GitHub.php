<?php

return [

    /*
    |--------------------------------------------------------------------------
    | GitHub notifications messages
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to localize notifications for events
    | fired by GitHub
    |
    */

    'Separator' => ' — ',

    'Commits' => [
        'Message' => ':committer committed :title',
        'Authored' => ' (authored by :author)', // appended to Message
    ],

    'RepoAndBranch' => ':repo (branch :branch)',

    'EventsDescriptions' => [
        'CommitCommentEvent' => ':author added a comment to :commit: :excerpt',

        'CreateEvent' => 'New :type on :repository: :ref',
        'CreateEventUnknown' => 'Unknown create reference: :type :ref',

        'DeleteEvent' => 'Removed :type on :repository: :ref',
        'DeleteEventUnknown' => 'Unknown delete reference: :type :ref',

        'ForkEvent' => ':repo_base has been forked to :repo_fork',

        'IssueCommentEventPerAction' => [
            'created' => ":author added a comment to issue #:issueNumber — :issueTitle: :excerpt",
            'edited' => ":author edited a comment to issue #:issueNumber — :issueTitle: :excerpt",
            'deleted' => ":author deleted a comment to issue #:issueNumber — :issueTitle",
        ],
        'IssueCommentEventUnknown' => 'Unknown issue comment action: :action',

        'PingEvent' => '« :zen » — GitHub Webhooks ping zen aphorism.',

        'PushEvent' => [
            '0' => ':user forcely updated :repoAndBranch',
            'n' => ':user pushed :count commits to :repoAndBranch', // n > 1
        ],

        'RepositoryEventPerAction' => [
            'created' => 'New repository :repository',
            'deleted' => "Repository :repository deleted (danger zone)",
            'publicized' => "Repository :repository is now public",
            'privatized' => "Repository :repository is now private",
        ],
        'RepositoryEventFork' => ' (fork)',
        'RepositoryEventUnknown' => 'Unknown repository action: :action',

        'StatusEvent' => 'Status of :commit: :status',

        'WatchEvent' => ':user starred :repository',
    ],

    'StatusEventState' => [
        'pending' => 'pending',
        'success' => 'success',
        'failure' => 'failure',
        'error'   => 'error',
    ],

];
