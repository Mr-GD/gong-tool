<?php

namespace gong\tool\Observer;

use gong\helper\traits\Data;
use gong\helper\traits\Make;
use gong\helper\traits\UsageLogs;
use gong\tool\base\abs\ObserverAbs;
use gong\tool\base\api\Observer;
use gong\tool\base\api\Subject;

/**
 * 观察者模式
 * @example Action::instance()->register(new Observer())->notify();
 */
class Action implements Subject
{
    use Make, Data, UsageLogs;

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
            try {
                $observer->setAction($this)->watch();
            } catch (\Throwable $e) {
                $this->log(sprintf('[Observer notify error] %s Message:%s', $classname, $e->getMessage()), 'error', $e);
                /** 可重试的观察者异常向上冒泡，交由队列重试机制处理 */
                if ($observer instanceof ObserverAbs && $observer->isRetryable()) {
                    throw $e;
                }
            }
        }

        $this->_observer = [];
        $this->result     = [];

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

    /**
     * 检测并移除不可序列化的观察者（含闭包、资源等），避免整批入队失败
     * @return array 被移除的观察者类名列表
     */
    public function filterSerializable(): array
    {
        $removed = [];
        foreach ($this->_observer as $key => $observer) {
            try {
                serialize($observer);
            } catch (\Throwable $e) {
                $classname = get_class($observer);
                $removed[] = $classname;
                $this->log(sprintf('[Observer not serializable, removed] %s Message:%s', $classname, $e->getMessage()), 'error', $e);
                unset($this->_observer[$key]);
            }
        }
        $this->_observer = array_values($this->_observer);
        return $removed;
    }
}