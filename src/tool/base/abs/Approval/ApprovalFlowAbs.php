<?php

namespace gong\tool\base\abs\Approval;

use gong\helper\traits\Data;
use gong\tool\base\api\Approval\ApprovalNode;

/** 审批流 */

/**
 * @method $this setNextApprovalNode(ApprovalFlowAbs $approvalAbstract) //设置下个审批节点
 * @method ApprovalFlowAbs getNextApprovalNode() //获取下个审批节点
 * @method $this setApprovalNode(ApprovalNode $ApprovalNode) //设置审批节点
 * @method $this setParams(array $params) //设置参数
 */
abstract class ApprovalFlowAbs
{
    use Data;

    /** @var ApprovalFlowAbs */
    public $nextApprovalNode; //下个审批节点
    protected $allowCurrentNodeApproval = true; //是否允许当前节点执行审批
    protected $params;

    /** @var array 审批后事件方法 */
    protected $postApprovalEventsFunc = [];

    public function __construct()
    {
    }

    /** 校验当前节点是否允许审批 */
    protected function calibrationLogic()
    {
    }

    protected function beforeApproval()
    {
        // TODO: beforeApproval() method.
    }

    protected function afterApproval()
    {
        // TODO: afterApproval() method.
    }

    protected function log(bool $approvalResult)
    {

    }

    /**
     * 执行审批.
     * @return bool
     * @date 2024/10/23 11:24
     */
    public function execute()
    {
        $this->calibrationLogic();
        /** 审批结果，为true代表审批结束，不需要执行下个审批节点 */
        $approvalResult = false;
        if ($this->allowCurrentNodeApproval) {
            $this->beforeApproval();
            $approvalResult = $this->approval();
            $this->afterApproval();
        }

        $this->log($approvalResult);

        $this->_postApprovalEventsFunc();

        if ($this->nextApprovalNode && !$approvalResult) {
            return $this->nextApprovalNode->execute();
        }

        return $approvalResult;
    }

    private function _postApprovalEventsFunc()
    {
        foreach ($this->postApprovalEventsFunc as $func) {
            if (method_exists($this, $func)) {
                $this->{$func}();
            }
        }
    }

}