<?php

namespace gong\tool\base\api;

/**
 * 被观察者
 */
interface Subject
{
    public function register(Observer $observer);
    /** 删除观察者对象 */
    public function detach(Observer $observer);
    /** 通知观察者执行功能 */
    public function notify();

}