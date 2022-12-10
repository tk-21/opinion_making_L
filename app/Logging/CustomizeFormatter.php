<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomizeFormatter
{
    /**
     * ログフォーマット
     */
    private $logFormat = '[%extra.class%][][@%extra.function%][(%extra.line%)] %message%' . PHP_EOL;

    /**
     * 日付フォーマット
     */
    private $dateFormat = 'Y-m-d H:i:s.v';

    /**
     * Customize the given logger instance.
     *
     * @param \Illuminate\Log\Logger $logger
     * @return void
     */
    public function __invoke($logger)
    {
        // formatter
        $formatter = new LineFormatter($this->logFormat, $this->dateFormat, true, true);

        // extra(class,method)
        $ip = new IntrospectionProcessor(Logger::DEBUG, ['Illuminate\\']);

        // ip address
        $wp = new WebProcessor();

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
            $handler->pushProcessor($ip);
            $handler->pushProcessor($wp);
            $handler->pushProcessor([$this, 'processLogRecord']);
        }
    }

    /**
     * edit record data
     *
     * @param array $record
     * @return array
     */
    public function processLogRecord(array $record): array
    {
        $userid = 'ログインしていない';
        if (Auth::check()) {
            $userid = Auth::id();
        }

        $record['extra'] += [
            'userid' => $userid,
            'localdate' => Carbon::now('JST')
        ];
        return $record;
    }
}
