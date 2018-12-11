<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/11
 * Time: 11:59 AM
 */

namespace PhalconExt\Logger\Formatter;

use Phalcon\Logger\Formatter;

class Json extends Formatter
{
    /**
     * Applies a format to a message before sent it to the internal log
     *
     * @param string $message
     * @param int $type
     * @param int $timestamp
     * @param array $context
     * @return string|array
     */
    public function format($message, $type, $timestamp, $context = null)
    {
        $data = [
            'type' => $this->getTypeString($type),
            'message' => $message,
        ];
        $context ? $data += $context : null;
        $data['timestamp'] = $timestamp;
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
    }
}