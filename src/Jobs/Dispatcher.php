<?php

namespace Hichamagm\IzagentShared\Jobs;

use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue as FacadesQueue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class Dispatcher extends Queue {
    
    public static function push($job, string $serviceName = null)
    {
        return (new self)->dispatch($job, $serviceName);
    }

    protected function dispatch($job, string $serviceName = null)
    {
        $configName = "database.redis.options.prefix";
        
        $oldPrefix = Config::get($configName);
        $newPrefix = $job->serviceName ?? $serviceName;
        $newPrefix .= ":";

        $this->setRedisPrefix($newPrefix);
        
        $queue = $job->queue ?? null;
        
        $payload = $this->createPayloadArray($job, $this->getQueueName($queue));
        $targetClass = $job->targetClass;

        $payload = array_merge($payload, [
            "displayName" => $targetClass,
            "sender" => env("APP_SLUG", ""),
            "data" => array_merge($payload["data"], [
                "commandName" => $targetClass,
                "command" => $this->changeCommand($payload["data"]["command"], $targetClass)
            ]),
            "id" => Str::random(32),
            "attempts" => 0
        ]);
        
        $payload = json_encode($payload, \JSON_UNESCAPED_UNICODE);
        
        FacadesQueue::connection('redis')->pushRaw($payload, $queue);

        $this->setRedisPrefix($oldPrefix);
    }

    protected function setRedisPrefix($prefix)
    {
        Redis::connection()->setOption(\Redis::OPT_PREFIX, $prefix);
    }

    protected function changeCommand($string, $targetClass)
    {
        $pattern = '/O:\d+:"([^"]+)"/';
        preg_match($pattern, $string, $matches);

        // Extract the class name and length
        $originalClassName = $matches[1];
        $originalClassLength = strlen($originalClassName);

        // Define the new class name
        $newClassName = $targetClass;
        $newClassLength = strlen($newClassName);

        // Replace the class name while adjusting the length
        $modifiedSerializedString = preg_replace(
            '/O:' . $originalClassLength . ':"' . preg_quote($originalClassName, '/') . '"/',
            'O:' . $newClassLength . ':"' . $newClassName . '"',
            $string
        );

        // Unserialize the modified string
        $object = unserialize($modifiedSerializedString);

        // Serialize the object back (optional, for verification)
        $newSerializedString = serialize($object);

        return $newSerializedString;
    }

    protected function resolveClass($className)
    {
        return $this->classMap[$className] ?? $className;
    }

    protected function getQueueName($queue)
    {
        return 'queues:' . $queue;
    }
}