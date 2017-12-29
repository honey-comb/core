<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Enum;


use HoneyComb\Core\Enum\Exceptions\EnumNotFoundException;

/**
 * Class Enumerable
 * @package HoneyComb\Core\Enum
 */
abstract class Enumerable
{
    /**
     * @var
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $description;

    /**
     * @var array
     */
    private static $instances = [];

    /**
     * Enumerable constructor.
     * @param $id
     * @param string $name
     * @param string $description
     */
    public function __construct($id, string $name, string $description = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;

        self::$instances[get_called_class()][$id] = $this;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->id;
    }

    /**
     * @param $id
     * @return Enumerable
     * @throws EnumNotFoundException
     */
    public static function from($id): Enumerable
    {
        $enum = self::enum();
        if (!isset($enum[$id])) {
            throw new EnumNotFoundException(strtr('Unable to find enumerable with id :id of type :type', [
                ':id' => $id,
                ':type' => get_called_class(),
            ]));
        }

        return $enum[$id];
    }

    /**
     * @return array
     */
    public static function enum(): array
    {
        $reflection = new \ReflectionClass(get_called_class());
        $finalMethods = $reflection->getMethods(\ReflectionMethod::IS_FINAL);

        $return = [];
        foreach ($finalMethods as $key => $method) {
            /** @var Enumerable $enum */
            $enum = $method->invoke(null);
            $return[$enum->id()] = $enum;
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function options(): array
    {
        return array_values(array_map(function(Enumerable $enumerable) {
            return ['id' => $enumerable->id(), 'name' => $enumerable->name()];
        }, self::enum()));
    }


    /**
     * @return string
     */
    public static function json(): string
    {
        return json_encode(array_map(function(Enumerable $enumerable) {
            return $enumerable->name();
        }, self::enum()));
    }

    /**
     * @param array $state
     * @return Enumerable
     */
    public static function __set_state(array $state): self
    {
        return self::make($state['id'], $state['name'], $state['description']);
    }

    /**
     * @param $id
     * @param string $name
     * @param string $description
     * @return Enumerable
     */
    protected static function make($id, string $name, string $description = ''): Enumerable
    {
        $class = get_called_class();

        if (isset(self::$instances[$class][$id])) {
            return self::$instances[$class][$id];
        }

        $reflection = new \ReflectionClass($class);

        /** @var Enumerable $instance */
        $instance = $reflection->newInstance($id, $name, $description);
        $refConstructor = $reflection->getConstructor();
        $refConstructor->setAccessible(true);

        return self::$instances[$class][$id] = $instance;
    }
}
