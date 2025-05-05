<?php

namespace gong\tool\base\abs\Approval;

abstract class ApprovalFactoryAbs
{
    /**
     * 设置下个审批节点
     * @param ApprovalFlowAbs $nodeInstance
     * @param ApprovalFlowAbs $approvalAbstract
     * @return ApprovalFlowAbs
     * @date 2024/10/23 13:27
     */
    protected function setNextApprovalNode(ApprovalFlowAbs $nodeInstance, ApprovalFlowAbs $approvalAbstract)
    {
        if (empty($nodeInstance->getNextApprovalNode())) {
            $nodeInstance->setNextApprovalNode($approvalAbstract);
        } else {
            $this->setNextApprovalNode($nodeInstance->getNextApprovalNode(), $approvalAbstract);
        }

        return $nodeInstance;
    }

    /**
     * 获取需要审批的节点
     * @param int $currentNode
     * @param array $nodes
     * @return array
     * @date 2024/10/24 10:07
     */
    protected function getNeedNodes(int $currentNode, array $nodes)
    {
        if (empty($nodes)) {
            return [];
        }

        $nodeIndex = array_search($currentNode, $nodes);
        if ($nodeIndex === false) {
            return [];
        }

        return array_slice($nodes, $nodeIndex);
    }
}