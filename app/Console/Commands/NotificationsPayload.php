<?php

namespace Nasqueron\Notifications\Console\Commands;

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
    protected $description = 'Gets a notification payload from a service payload';

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
     * Creates a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Executes the console command.
     */
    public function handle() {
        if ($this->parseArguments()) {
            $this->printNotification();
        }
    }

    /**
     * Parses arguments passed to the command.
     *
     * @return bool true if arguments looks good; otherwise, false.
     */
    private function parseArguments () {
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
     * @throws InvalidArgumentException when a notification class can't be found for the requested service.
     */
    private function parseService () {
        $this->service = $this->argument('service');

        if (!class_exists($this->getNotificationClass())) {
            throw new InvalidArgumentException("Unknown service: $this->service");
        }
    }

    /**
     * Parses path to the payload argument.
     *
     * Fills the content of the file to the payload property.
     *
     * @throws InvalidArgumentException when payload file is not found.
     */
    private function parsePayload () {
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
     * @throws InvalidArgumentException when too many or too few arguments have been given.
     */
    private function parseConstructorParameters () {
        $keys = $this->getNotificationConstructorParameters();

        $values = $this->argument('args');
        $values['payload'] = json_decode($this->payload);

        $this->constructor = self::argumentsArrayCombine($keys, $values);
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
    public static function argumentsArrayCombine ($keys, $values) {
        $countKeys = count($keys);
        $countValues = count($values);

        if ($countKeys != $countValues) {
            throw new InvalidArgumentException("Number of arguments mismatch: got $countValues but expected $countKeys.");
        }

        return array_combine($keys, $values);
    }

    /**
     * Initializes a new instance of the relevant notification class,
     * with the arguments given in the constructor property.
     *
     * @return Nasqueron\Notifications\Notification
     */
    private function getNotification () {
        $class = $this->getNotificationClass();
        $args = array_values($this->constructor);
        return new $class(...$args);
    }

    /**
     * Gets the notification in JSON format.
     *
     * @return string
     */
    private function formatNotification () {
        return json_encode($this->getNotification(), JSON_PRETTY_PRINT);
    }

    /**
     * Prints the notification for the service, payload and specified arguments.
     */
    private function printNotification () {
        $this->line($this->formatNotification());
    }

    /**
     * Gets the notification class for the specified service.
     *
     * @return string
     */
    private function getNotificationClass () {
        $namespace = "Nasqueron\Notifications\Notifications\\";
        return $namespace . $this->service . "Notification";
    }

    /**
     * Gets an array with the parameters to pass to the constructor
     * of the notification class for the specified service.
     *
     * @return array
     */
    private function getNotificationConstructorParameters () {
        $parameters = [];

        $class = new ReflectionClass($this->getNotificationClass());
        foreach ($class->getConstructor()->getParameters() as $parameter) {
            $parameters[] = $parameter->getName();
        }

        return $parameters;
    }
}
