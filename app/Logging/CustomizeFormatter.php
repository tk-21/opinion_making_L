<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;
use Illuminate\Support\Facades\Auth;

class CustomizeFormatter
{
    /**
     * ログフォーマット
     */
    private $logFormat = '[%datetime%][%channel%][%level_name%][%extra.ip%][user_id:%extra.userid%][user_name:%extra.username%][%extra.class%][%extra.function%][(%extra.line%)] %message%' . PHP_EOL;

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
        // ログのフォーマットと日付のフォーマットを指定する
        $formatter = new LineFormatter($this->logFormat, $this->dateFormat, true, true);

        // IntrospectionProcessorを使うとextraフィールドが使えるようになる
        $ip = new IntrospectionProcessor(Logger::DEBUG, ['Illuminate\\']);

        // WebProcessorを使うとextra.ipが使えるようになる
        $wp = new WebProcessor();

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
            // pushProcessorするとextra情報をログに埋め込んでくれる
            $handler->pushProcessor($ip);
            $handler->pushProcessor($wp);
            // addExtraFields()を呼び出す。extra.useridとextra.usernameをログに埋め込んでくれる
            $handler->pushProcessor([$this, 'addExtraFields']);
        }
    }

    public function addExtraFields(array $record): array
    {
        $user = Auth::user();
        $record['extra']['userid'] = $user->id ?? null;
        $record['extra']['username'] = $user ? $user->name : '未ログイン';
        return $record;
    }
}
