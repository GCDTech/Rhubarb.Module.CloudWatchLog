<?php

namespace Gcd\Rhubarb\CloudWatch;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Gcdtech\Aws\Settings\AwsSettings;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Logger;
use Rhubarb\Crown\Logging\MonologLog;

class CloudWatchLog extends MonologLog
{
    public function __construct($logLevel, $groupName, $streamName, $retentionPeriodInDays = 14)
    {
        $aws = AwsSettings::singleton();

        $awsCredentials = $aws->getClientSettings([]);

        $cwClient = new CloudWatchLogsClient($awsCredentials);
        $logger = new Logger('PHP Logging');

        $cwHandlerInstanceLog = new CloudWatch($cwClient, $groupName, $streamName, $retentionPeriodInDays, 10000, null,Logger::NOTICE);
        $logger->pushHandler($cwHandlerInstanceLog);

        parent::__construct($logLevel, $logger);
    }
}