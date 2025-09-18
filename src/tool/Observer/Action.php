<?php

namespace gong\tool\Observer;

use gong\helper\traits\Data;
use gong\helper\traits\Instance;
use gong\helper\traits\Log;
use gong\tool\base\abs\ObserverAbs;
use gong\tool\base\api\Observer;
use gong\tool\base\api\Subject;

/**
 * 观察者模式
 * @example Action::instance()->register(new Observer())->notify();
 */
class Action implements Subject
{
    use Instance, Data, Log;

    private array $_observer = [];
    protected array $result = [];

    /**
     * 获取节点结果
     * @param Observer $panelPoint
     * @return mixed|null
     * @example $result = $action->getNodeResult(new BTestObservation()); 对应观察者实例
     */
    public function getNodeResult(Observer $panelPoint)
    {
        $classname = get_class($panelPoint);
        $classname = basename(str_replace('\\', '/', $classname));
        return $this->result[$classname] ?? null;
    }

    /**
     * 注册观察者
     * @param Observer $observer
     * @return $this
     */
    public function register(Observer $observer)
    {
        $this->_observer[] = $observer;
        return $this;
    }

    /**
     * 删除观察者
     * @param Observer $observer
     * @return $this
     */
    public function detach(Observer $observer)
    {
        $classname = get_class($observer);
        $classname = basename(str_replace('\\', '/', $classname));
        unset($this->result[$classname]);
        return $this;
    }

    /**
     * 通知观察者
     * @return bool
     * @date 2024/9/29 16:53
     */
    public function notify()
    {
        /** @var ObserverAbs $observer */
        foreach ($this->_observer as $observer) {
            $classname = get_class($observer);
            $classname = basename(str_replace('\\', '/', $classname));
            try {
                $this->result[$classname] = $observer->setAction($this)->watch();
            } catch (\Exception $e) {
                $message = '【观察者】Classname:' . $classname . ' Error:' . $e->getMessage();
                $this->log($message);
            }
        }

        $this->_observer = [];

        return true;
    }

    public function clearObserver()
    {
        $this->_observer = [];
        return true;
    }

    public function clearResult()
    {
        $this->result = [];
        return true;
    }

    /**
     * 获取观察者
     * @return array
     */
    public function getObserver()
    {
        return $this->_observer;
    }

    protected function getLogCatalogue()
    {
        return 'Action';
    }
}