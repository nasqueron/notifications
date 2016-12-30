<?php

namespace Nasqueron\Notifications\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Config;
use Raven;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        CommandNotFoundException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
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
    protected function shouldReportToSentry () {
        return Raven::isConfigured() && Config::get('app.env') !== 'testing';
    }

    /**
     * Reports the exception to Sentry
     *
     * @param Exception $e The exception to report
     */
    protected function reportToSentry (Exception $e) {
        Raven::captureException($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return parent::render($request, $e);
    }
}
