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

        'PingEvent' => '« :zen » — GitHub Webhooks ping zen aphorism.',

        'PushEvent' => [
            '0' => ':user forcely updated :repoAndBranch',
            'n' => ':user pushed :count commits to :repoAndBranch', // n > 1
        ],

        'RepositoryEvent' => 'New repository :repository',
        'RepositoryEventFork' => ' (fork)',

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
