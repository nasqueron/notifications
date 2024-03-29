<?php

namespace Nasqueron\Notifications\Console\Commands;

use Nasqueron\Notifications\Notifications\Notification;
use Nasqueron\Notifications\Phabricator\PhabricatorStory;

use Illuminate\Console\Command;

use InvalidArgumentException;
use ReflectionClass;

class NotificationsPayload extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:payload {service} {payload} {args*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = <<<'TXT'
Gets a notification payload from a service payload
TXT;


    /**
     * The service to handle a payload for.
     *
     * @var string
     */
    private $service;

    /**
     * The payload.
     *
     * @var string
     */
    private $payload;

    /**
     * The parameters to pass to the notifications class constructor.
     *
     * An array with arguments' names as keys, arguments' values as values.
     *
     * @var array
     */
    private $constructor;

    /**
     * Executes the console command.
     */
    public function handle() : void {
        if ($this->parseArguments()) {
            $this->printNotification();
        }
    }

    /**
     * Parses arguments passed to the command.
     *
     * @return bool true if arguments looks good; otherwise, false.
     */
    private function parseArguments () : bool {
        try {
            $this->parseService();
            $this->parsePayload();
            $this->parseConstructorParameters();
        } catch (InvalidArgumentException $ex) {
            $this->error($ex->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Parses service argument.
     *
     * Fills it to the service property.
     *
     * @throws InvalidArgumentException when a notification class can't be
     * found for the requested service.
     */
    private function parseService () : void {
        $this->service = $this->argument('service');

        if (!class_exists($this->getNotificationClass())) {
            throw new InvalidArgumentException(
                "Unknown service: $this->service"
            );
        }
    }

    /**
     * Parses path to the payload argument.
     *
     * Fills the content of the file to the payload property.
     *
     * @throws InvalidArgumentException when payload file is not found.
     */
    private function parsePayload () : void {
        $payloadFile = $this->argument('payload');

        if (!file_exists($payloadFile)) {
            throw new InvalidArgumentException("File not found: $payloadFile");
        }

        $this->payload = file_get_contents($payloadFile);
    }

    /**
     * Parses all the extra arguments and sets the constructor property
     * as an array of constructor arguments.
     *
     * @throws InvalidArgumentException on wrong arguments count.
     */
    private function parseConstructorParameters () : void {
        $keys = $this->getNotificationConstructorParameters();

        $values = $this->argument('args');
        $values['payload'] = $this->payload;

        $this->constructor = self::argumentsArrayCombine($keys, $values);
        $this->constructor['payload'] = $this->formatPayload();
    }

    /**
     * Formats payload to pass to constructor
     *
     * @return PhabricatorStory|\stdClass A deserialization of the payload
     */
    private function formatPayload() : \stdClass|PhabricatorStory {
        if ($this->service === "Phabricator") {
            $project = $this->constructor['project'];
            return PhabricatorStory::loadFromJson($project, $this->payload);
        }

        return json_decode($this->payload);
    }

    /**
     * Creates an array by using one array for keys and another for its values.
     *
     * @param array $keys
     * @param array $values
     * @return array
     *
     * @throws InvalidArgumentException when keys and values counts don't match
     */
    public static function argumentsArrayCombine (
        array $keys, array $values
    ) : array {
        $countKeys = count($keys);
        $countValues = count($values);

        if ($countKeys != $countValues) {
            throw new InvalidArgumentException(<<<MSG
Number of arguments mismatch: got $countValues but expected $countKeys.
MSG
            );
        }

        return array_combine($keys, $values);
    }

    /**
     * Initializes a new instance of the relevant notification class,
     * with the arguments given in the constructor property.
     */
    private function getNotification () : Notification {
        $class = $this->getNotificationClass();
        $args = array_values($this->constructor);
        return new $class(...$args);
    }

    /**
     * Gets the notification in JSON format.
     */
    private function formatNotification () : string {
        return json_encode($this->getNotification(), JSON_PRETTY_PRINT);
    }

    /**
     * Prints the notification for the service, payload and specified arguments.
     */
    private function printNotification () : void {
        $this->line($this->formatNotification());
    }

    /**
     * Gets the notification class for the specified service
     */
    private function getNotificationClass () : string {
        $namespace = "Nasqueron\Notifications\Notifications\\";
        return $namespace . $this->service . "Notification";
    }

    /**
     * Gets an array with the parameters to pass to the constructor
     * of the notification class for the specified service.
     *
     * @return string[]
     */
    private function getNotificationConstructorParameters () : array {
        $parameters = [];

        $class = new ReflectionClass($this->getNotificationClass());
        foreach ($class->getConstructor()->getParameters() as $parameter) {
            $parameters[] = $parameter->getName();
        }

        return $parameters;
    }
}
