<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

use DNW\Skills\Guard;
use DNW\Skills\HashMap;
use Exception;

abstract class Factor
{
    /**
     * @var Message[] $messages
     */
    private array $messages = [];

    private readonly HashMap $messageToVariableBinding;

    /**
     * @var Variable[] $variables
     */
    private array $variables = [];

    protected function __construct()
    {
        $this->messageToVariableBinding = new HashMap();
    }

    /**
     * @return float The log-normalization constant of that factor
     */
    public function getLogNormalization(): float
    {
        return 0;
    }

    /**
     * @return int The number of messages that the factor has
     */
    public function getNumberOfMessages(): int
    {
        return count($this->messages);
    }

    /**
     * @return Variable[]
     */
    protected function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @return Message[]
     */
    protected function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Update the message and marginal of the i-th variable that the factor is connected to
     *
     * @param $messageIndex
     *
     * @throws Exception
     */
    public function updateMessageIndex(int $messageIndex): float
    {
        Guard::argumentIsValidIndex($messageIndex, count($this->messages), 'messageIndex');
        $message = $this->messages[$messageIndex];
        $variable = $this->messageToVariableBinding->getValue($message);

        return $this->updateMessageVariable($message, $variable);
    }

    protected function updateMessageVariable(Message $message, Variable $variable): float
    {
        throw new Exception();
    }

    /**
     * Resets the marginal of the variables a factor is connected to
     */
    public function resetMarginals(): void
    {
        $allValues = $this->messageToVariableBinding->getAllValues();
        foreach ($allValues as $currentVariable) {
            $currentVariable->resetToPrior();
        }
    }

    /**
     * Sends the ith message to the marginal and returns the log-normalization constant
     *
     * @throws Exception
     */
    public function sendMessageIndex(int $messageIndex): float|int
    {
        Guard::argumentIsValidIndex($messageIndex, count($this->messages), 'messageIndex');

        $message = $this->messages[$messageIndex];
        $variable = $this->messageToVariableBinding->getValue($message);

        return $this->sendMessageVariable($message, $variable);
    }

    abstract protected function sendMessageVariable(Message $message, Variable $variable): float|int;

    abstract public function createVariableToMessageBinding(Variable $variable): Message;

    protected function createVariableToMessageBindingWithMessage(Variable $variable, Message $message): Message
    {
        $this->messageToVariableBinding->setValue($message, $variable);
        $this->messages[] = $message;
        $this->variables[] = $variable;

        return $message;
    }
}
