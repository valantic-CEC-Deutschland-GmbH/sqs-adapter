<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Dependency\Client;

use Aws\Result;
use Generated\Shared\Transfer\SqsChangeMessageVisibilityArgsTransfer;
use Generated\Shared\Transfer\SqsCreateQueueArgsTransfer;
use Generated\Shared\Transfer\SqsDeleteMessageArgsTransfer;
use Generated\Shared\Transfer\SqsDeleteQueueArgsTransfer;
use Generated\Shared\Transfer\SqsGetQueueAttributesArgsTransfer;
use Generated\Shared\Transfer\SqsPurgeQueueArgsTransfer;
use Generated\Shared\Transfer\SqsReceiveMessageArgsTransfer;
use Generated\Shared\Transfer\SqsSendMessageArgsTransfer;
use Generated\Shared\Transfer\SqsSendMessageBatchArgsTransfer;
use GuzzleHttp\Promise\PromiseInterface;
use ValanticSpryker\Client\AwsSqs\AwsSqsClientInterface;

class SqsAdapterToAwsSqsClientBridge implements SqsAdapterToAwsSqsClientInterface
{
    protected AwsSqsClientInterface $awsSqsClient;

    /**
     * @param \ValanticSpryker\Client\AwsSqs\AwsSqsClientInterface $awsSqsClient
     */
    public function __construct(AwsSqsClientInterface $awsSqsClient)
    {
        $this->awsSqsClient = $awsSqsClient;
    }

    /**
     * @param \Generated\Shared\Transfer\SqsCreateQueueArgsTransfer $sqsCreateQueueArgsTransfer
     *
     * @return \Aws\Result
     */
    public function createQueue(SqsCreateQueueArgsTransfer $sqsCreateQueueArgsTransfer): Result
    {
        return $this->awsSqsClient->createQueue($sqsCreateQueueArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SqsDeleteQueueArgsTransfer $sqsDeleteQueueArgsTransfer
     *
     * @return void
     */
    public function deleteQueue(SqsDeleteQueueArgsTransfer $sqsDeleteQueueArgsTransfer): void
    {
        $this->awsSqsClient->deleteQueue($sqsDeleteQueueArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SqsPurgeQueueArgsTransfer $sqsPurgeQueueArgsTransfer
     *
     * @return void
     */
    public function purgeQueue(SqsPurgeQueueArgsTransfer $sqsPurgeQueueArgsTransfer): void
    {
        $this->awsSqsClient->purgeQueue($sqsPurgeQueueArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SqsSendMessageArgsTransfer $sqsSendMessageArgsTransfer
     *
     * @return \Aws\Result
     */
    public function sendMessage(SqsSendMessageArgsTransfer $sqsSendMessageArgsTransfer): Result
    {
        return $this->awsSqsClient->sendMessage($sqsSendMessageArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SqsSendMessageArgsTransfer $sqsSendMessageArgsTransfer
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function sendMessageAsync(SqsSendMessageArgsTransfer $sqsSendMessageArgsTransfer): PromiseInterface
    {
        return $this->awsSqsClient->sendMessageAsync($sqsSendMessageArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SqsSendMessageBatchArgsTransfer $sqsSendMessageBatchArgsTransfer
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function sendMessageBatchAsync(SqsSendMessageBatchArgsTransfer $sqsSendMessageBatchArgsTransfer): PromiseInterface
    {
        return $this->awsSqsClient->sendMessageBatchAsync($sqsSendMessageBatchArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SqsReceiveMessageArgsTransfer $sqsReceiveMessageArgsTransfer
     *
     * @return \Aws\Result
     */
    public function receiveMessage(SqsReceiveMessageArgsTransfer $sqsReceiveMessageArgsTransfer): Result
    {
        return $this->awsSqsClient->receiveMessage($sqsReceiveMessageArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SqsDeleteMessageArgsTransfer $sqsDeleteMessageArgsTransfer
     *
     * @return void
     */
    public function deleteMessage(SqsDeleteMessageArgsTransfer $sqsDeleteMessageArgsTransfer): void
    {
        $this->awsSqsClient->deleteMessage($sqsDeleteMessageArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SqsChangeMessageVisibilityArgsTransfer $sqsChangeMessageVisibilityArgsTransfer
     *
     * @return void
     */
    public function changeMessageVisibility(SqsChangeMessageVisibilityArgsTransfer $sqsChangeMessageVisibilityArgsTransfer): void
    {
        $this->awsSqsClient->changeMessageVisibility($sqsChangeMessageVisibilityArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SqsChangeMessageVisibilityArgsTransfer $sqsChangeMessageVisibilityArgsTransfer
     *
     * @return void
     */
    public function changeMessageVisibilityAsync(SqsChangeMessageVisibilityArgsTransfer $sqsChangeMessageVisibilityArgsTransfer): void
    {
        $this->awsSqsClient->changeMessageVisibilityAsync($sqsChangeMessageVisibilityArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SqsGetQueueAttributesArgsTransfer $sqsGetQueueAttributesArgsTransfer
     *
     * @return \Aws\Result
     */
    public function getQueueAttributes(SqsGetQueueAttributesArgsTransfer $sqsGetQueueAttributesArgsTransfer): Result
    {
        return $this->awsSqsClient->getQueueAttributes($sqsGetQueueAttributesArgsTransfer);
    }
}
