<?php

namespace Nasqueron\Notifications\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Config;
use Raven;

use Exception;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        AuthorizationException::class,
        CommandNotFoundException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Reports or logs an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $e
     */
    public function report(Exception $e) : void {
        if (!$this->shouldReport($e)) {
            return;
        }

        if ($this->shouldReportToSentry()) {
            $this->reportToSentry($e);
        }

        $this->log->error((string)$e);
    }

    /**
     * Determines if the error handler should report to Sentry
     *
     * @return bool
     */
    protected function shouldReportToSentry () : bool {
        return Raven::isConfigured() && Config::get('app.env') !== 'testing';
    }

    /**
     * Reports the exception to Sentry
     *
     * @param Exception $e The exception to report
     */
    protected function reportToSentry (Exception $e) : void {
        Raven::captureException($e);
    }

}
