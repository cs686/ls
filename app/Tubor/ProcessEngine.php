<?php
namespace App\Turbor;

use App\Turbor\Processor\DefinitionProcessor;
use App\Turbor\Processor\RuntimeProcessor;

interface iProcessEngine {
    /**
     * Create a flow({@link FlowDefinitionPO}) with flowKey and descriptive info.
     * Attention: The {@link FlowModel} of the flow is empty.
     *
     * @param createFlowParam flowKey: business key for the flow
     *                        flowName/operator/remark: describe the flow
     * @return {@link CreateFlowParam} mainly includes flowModuleId to indicate an unique flow.
     */
    public function createFlow($createFlowParam);

    /**
     * Update a flow by flowModuleId. Set/update flowModel or update descriptive info.
     *
     * @param updateFlowParam flowModuleId: specify the flow to update
     *                        flowKey/flowName/flowModel/remark: content to update
     */
    public function updateFlow($updateFlowParam);

    /**
     * Deploy a flow by flowModuleId.
     * <p>
     * Create a {@link FlowDeploymentPO} every time.
     * A flow can be started to process only after deployed.
     *
     * @param deployFlowParam flowModuleId: specify the flow to deploy
     * @return {@link DeployFlowResult} mainly contains flowDeployId to indicate an unique record of the deployment.
     */
    public function deployFlow($deployFlowParam);

    /**
     * Get flow info includes flowModel content, status and descriptive info.
     * <p>
     * It'll query by flowDeployId while the flowDeployId is not blank. Otherwise, it'll query by flowModuleId.
     *
     * @param getFlowModuleParam flowModuleId specify the flow and get info from {@link FlowDefinitionPO}
     *                           flowDeployId specify the flow and get info from {@link FlowDeploymentPO}
     */
    public function getFlowModule($getFlowModuleParam);

    /**
     * Start process
     * <p>
     * 1.Create a flow instance({@link com.didiglobal.turbo.engine.entity.FlowInstancePO}) according to the specified
     * flow for the execution every time;
     * 2.Process the flow instance from the unique {@link StartEvent} node
     * until it reaches an {@link UserTask} node or
     * an {@link EndEvent} node.
     *
     * @param startProcessParam flowDeployId / flowModuleId: specify the flow to process
     *                          variables: input data to drive the process if required
     * @return {@link StartProcessResult} mainly contains flowInstanceId and activeTaskInstance({@link NodeInstance})
     * to describe the userTask to be committed or the EndEvent node instance.
     */
    public function startProcess($startProcessParam);

    /**
     * Commit suspended userTask of the flow instance previously created specified by flowInstanceId and continue to process.
     *
     * @param commitTaskParam flowInstanceId: specify the flowInstance of the task
     *                        nodeInstanceId: specify the task to commit
     *                        variables: input data to drive the process if required
     * @return {@link CommitTaskResult} similar to {@link #startProcess(StartProcessParam)}
     */
    public function commitTask($commitTaskParam);

    /**
     * Rollback task
     * <p>
     * According to the historical node instance list, it'll rollback the suspended userTask of the flow instance
     * specified by flowInstanceId forward until it reaches an UserTask node or an StartEvent node.
     *
     * @param rollbackTaskParam flowInstanceId / nodeInstanceId similar to {@link #commitTask(CommitTaskParam)}
     * @return {@link RollbackTaskResult} similar to {@link #commitTask(CommitTaskParam)}
     */
    public function rollbackTask($rollbackTaskParam);

    /**
     * Terminate process
     * <p>
     * If the specified flow instance has been completed, ignore. Otherwise, set status to terminated of the flow instance.
     *
     * @param flowInstanceId
     * @return {@link TerminateResult} similar to {@link #commitTask(CommitTaskParam)} without activeTaskInstance.
     */
    public function terminateProcess(String $flowInstanceId);

    /**
     * Get historical UserTask list
     * <p>
     * Get the list of processed UserTask of the specified flow instance order by processed time desc.
     * Attention: it'll include active userTask(s) and completed userTask(s) in the list without disabled userTask(s).
     *
     * @param flowInstanceId
     */
    public function getHistoryUserTaskList(String $flowInstanceId);

    /**
     * Get processed element instance list for the specified flow instance, and mainly used to show the view of the snapshot.
     *
     * @param flowInstanceId flowInstance ID
     * @return {@link ElementInstanceListResult} the list of nodes executed in history
     */
    public function getHistoryElementList(String $flowInstanceId);

    /**
     * Get latest {@link InstanceData} list of the specified flow instance.
     *
     * @param flowInstanceId
     */
    public function getInstanceData(String $flowInstanceId);

    /**
     * According to the flow instance and node instance given in, get node instance info.
     *
     * @param flowInstanceId
     * @param nodeInstanceId
     */
    public function getNodeInstance(String $flowInstanceId, String $nodeInstanceId);
}

class ProcessEngine implements iProcessEngine{
    public function __construct(DefinitionProcessor $definitionProcessor, RuntimeProcessor $runtimeProcessor)
    {
        $this->definitionProcessor = $definitionProcessor;
        $this->runtimeProcessor = $runtimeProcessor;
    }
    
    /**
     * Create a flow({@link FlowDefinitionPO}) with flowKey and descriptive info.
     * Attention: The {@link FlowModel} of the flow is empty.
     *
     * @param createFlowParam flowKey: business key for the flow
     *                        flowName/operator/remark: describe the flow
     * @return {@link CreateFlowParam} mainly includes flowModuleId to indicate an unique flow.
     */
    public function createFlow($createFlowParam) {
        return $this->definitionProcessor->create($createFlowParam);
    }

    /**
     * Update a flow by flowModuleId. Set/update flowModel or update descriptive info.
     *
     * @param updateFlowParam flowModuleId: specify the flow to update
     *                        flowKey/flowName/flowModel/remark: content to update
     */
    public function updateFlow($updateFlowParam) {

    }

    /**
     * Deploy a flow by flowModuleId.
     * <p>
     * Create a {@link FlowDeploymentPO} every time.
     * A flow can be started to process only after deployed.
     *
     * @param deployFlowParam flowModuleId: specify the flow to deploy
     * @return {@link DeployFlowResult} mainly contains flowDeployId to indicate an unique record of the deployment.
     */
    public function deployFlow($deployFlowParam){

    }

    /**
     * Get flow info includes flowModel content, status and descriptive info.
     * <p>
     * It'll query by flowDeployId while the flowDeployId is not blank. Otherwise, it'll query by flowModuleId.
     *
     * @param getFlowModuleParam flowModuleId specify the flow and get info from {@link FlowDefinitionPO}
     *                           flowDeployId specify the flow and get info from {@link FlowDeploymentPO}
     */
    public function getFlowModule($getFlowModuleParam){

    }

    /**
     * Start process
     * <p>
     * 1.Create a flow instance({@link com.didiglobal.turbo.engine.entity.FlowInstancePO}) according to the specified
     * flow for the execution every time;
     * 2.Process the flow instance from the unique {@link StartEvent} node
     * until it reaches an {@link UserTask} node or
     * an {@link EndEvent} node.
     *
     * @param startProcessParam flowDeployId / flowModuleId: specify the flow to process
     *                          variables: input data to drive the process if required
     * @return {@link StartProcessResult} mainly contains flowInstanceId and activeTaskInstance({@link NodeInstance})
     * to describe the userTask to be committed or the EndEvent node instance.
     */
    public function startProcess($startProcessParam){

    }

    /**
     * Commit suspended userTask of the flow instance previously created specified by flowInstanceId and continue to process.
     *
     * @param commitTaskParam flowInstanceId: specify the flowInstance of the task
     *                        nodeInstanceId: specify the task to commit
     *                        variables: input data to drive the process if required
     * @return {@link CommitTaskResult} similar to {@link #startProcess(StartProcessParam)}
     */
    public function commitTask($commitTaskParam){

    }

    /**
     * Rollback task
     * <p>
     * According to the historical node instance list, it'll rollback the suspended userTask of the flow instance
     * specified by flowInstanceId forward until it reaches an UserTask node or an StartEvent node.
     *
     * @param rollbackTaskParam flowInstanceId / nodeInstanceId similar to {@link #commitTask(CommitTaskParam)}
     * @return {@link RollbackTaskResult} similar to {@link #commitTask(CommitTaskParam)}
     */
    public function rollbackTask($rollbackTaskParam){

    }

    /**
     * Terminate process
     * <p>
     * If the specified flow instance has been completed, ignore. Otherwise, set status to terminated of the flow instance.
     *
     * @param flowInstanceId
     * @return {@link TerminateResult} similar to {@link #commitTask(CommitTaskParam)} without activeTaskInstance.
     */
    public function terminateProcess(String $flowInstanceId){

    }

    /**
     * Get historical UserTask list
     * <p>
     * Get the list of processed UserTask of the specified flow instance order by processed time desc.
     * Attention: it'll include active userTask(s) and completed userTask(s) in the list without disabled userTask(s).
     *
     * @param flowInstanceId
     */
    public function getHistoryUserTaskList(String $flowInstanceId){

    }

    /**
     * Get processed element instance list for the specified flow instance, and mainly used to show the view of the snapshot.
     *
     * @param flowInstanceId flowInstance ID
     * @return {@link ElementInstanceListResult} the list of nodes executed in history
     */
    public function getHistoryElementList(String $flowInstanceId){

    }

    /**
     * Get latest {@link InstanceData} list of the specified flow instance.
     *
     * @param flowInstanceId
     */
    public function getInstanceData(String $flowInstanceId){

    }

    /**
     * According to the flow instance and node instance given in, get node instance info.
     *
     * @param flowInstanceId
     * @param nodeInstanceId
     */
    public function getNodeInstance(String $flowInstanceId, String $nodeInstanceId){

    }
}