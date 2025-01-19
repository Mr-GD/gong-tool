<?php
namespace App\Exceptions;

use common\Exception\Code;

/**
 * Class ErrException
 * @package common\errors
 */
class ErrException extends \Exception
{

    public int $status = 500;

    /**
     * ErrException constructor.
     * @param int $code
     * @param string $message
     * @param int|null $status
     * @param \Throwable|null $previous
     */
    public function __construct($code = 0, $message = '', $status = null, \Throwable $previous = null)
    {
        $infos = Code::statusMessages();
        if($status){
            $this->status = $status;
        }else{
            if(isset($infos[$code]['status'])){
                $this->status = $infos[$code]['status'];
            }
        }
        if(!$message && isset($infos[$code]['message'])){
            $message = $infos[$code]['message'];
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param \Exception $e
     * @return string[]
     */
    public static function getTraceInfo(\Exception $e)
    {
        $trace = explode(PHP_EOL, $e->getTraceAsString());
        return array_merge(array('## '.$e->getFile().'('.$e->getLine().')'), $trace);
    }

    /**
     * @param \Exception $e
     * @return string
     */
    public static function getTraceString(\Exception $e)
    {
        $trace = self::getTraceInfo($e);
        return implode(PHP_EOL, $trace);
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }
    public function getFriendlyMessage()
    {
        $message = $this->getMessage();

        if (strpos($message, 'MongoDb 异常')) {
            $message = 'MongoDb 异常';
        }

        preg_match('/^(SQLSTATE\[.*?]).*$/m', $message, $matches);
        if (is_array($matches) && isset($matches[1])) {
            $message = '数据错误 ' . $matches[1];
        }

        return $message;
    }
}
