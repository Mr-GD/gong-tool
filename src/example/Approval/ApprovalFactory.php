<?php

namespace gong\example\Approval;

use gong\helper\traits\Data;
use gong\tool\base\abs\Approval\ApprovalFactoryAbs;
use gong\tool\base\abs\Approval\ApprovalFlowAbs;
use gong\tool\base\api\Approval\ApprovalNode;
use gong\tool\base\api\Factory;

class ApprovalFactory extends ApprovalFactoryAbs implements Factory
{
    use Data;

    /** @var ApprovalNode */
    protected $retreatApprovalNode;

    /** @var Retreat */
    protected $retreat;

    /**
     * 创建审批节点对象
     * @param $approvalStatus
     * @return ApprovalFlowAbs
     * @date 2024/10/23 11:08
     */
    public static function create($approvalStatus)
    {
        switch ($approvalStatus) {
            case RetreatApprovalNode::NODE_FIRST_LEADER: //直属一级领导
                $class = new DepartmentFirstLevelLeader();
                break;
            case RetreatApprovalNode::NODE_SECOND_LEADER: //直属二级领导
                $class = new DepartmentTwoStageLeader();
                break;
            case RetreatApprovalNode::NODE_ADMINISTRATION: //行政
                $class = new Administration();
                break;
            case RetreatApprovalNode::NODE_FINANCE: //财务
                $class = new Finance();
                break;
            case RetreatApprovalNode::NODE_HR: //人事
                $class = new Personnel();
                break;
            default:
                $class = null;
                break;
        }

        return $class;
    }

    /**
     * 创建审批节点实例对象
     * @return ApprovalFlowAbs
     * @date 2024/10/23 11:14
     */
    public function createNodeInstance($params)
    {
        $nodes        = explode('-', $this->retreatApprovalNode->node);
        $nodes        = $this->getNeedNodes($this->retreat->approval_node, $nodes);
        $nodeInstance = null;
        foreach ($nodes as $node) {
            $tempNode = self::create($node);
            if (!$tempNode) {
                continue;
            }
            $tempNode->setParams($params)
                     ->setApprovalNode($this->retreatApprovalNode)
            ;

            if (!$nodeInstance) {
                $nodeInstance = $tempNode;
            } else {
                $nodeInstance = $this->setNextApprovalNode($nodeInstance, $tempNode);
            }
        }

        return $nodeInstance;
    }

}