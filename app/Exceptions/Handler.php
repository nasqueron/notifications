<?php

namespace Nasqueron\Notifications\Exceptions;

use Nasqueron\Notifications\Facades\Raven;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Config;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Reports or logs an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception|\Throwable $e
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function report(Exception|\Throwable $e) : void {
        if (!$this->shouldReport($e)) {
            return;
        }

        if ($this->shouldReportToSentry()) {
            $this->reportToSentry($e);
        }

        $log = $this->container->make(LoggerInterface::class);
        $log->error((string)$e);
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
