<?php

namespace DNW\Skills\FactorGraphs;

use DNW\Skills\Guard;
use DNW\Skills\HashMap;
use Exception;

abstract class Factor implements \Stringable
{
    private array $_messages = [];

    private $_messageToVariableBinding;

    private string $_name;

    private array $_variables = [];

    protected function __construct(string $name)
    {
        $this->_name = 'Factor['.$name.']';
        $this->_messageToVariableBinding = new HashMap();
    }

    /**
     * @return mixed The log-normalization constant of that factor
     */
    public function getLogNormalization()
    {
        return 0;
    }

    /**
     * @return int The number of messages that the factor has
     */
    public function getNumberOfMessages(): int
    {
        return count($this->_messages);
    }

    protected function getVariables(): array
    {
        return $this->_variables;
    }

    protected function getMessages(): array
    {
        return $this->_messages;
    }

    /**
     * Update the message and marginal of the i-th variable that the factor is connected to
     *
     * @param $messageIndex
     *
     * @throws Exception
     */
    public function updateMessageIndex(int $messageIndex)
    {
        Guard::argumentIsValidIndex($messageIndex, count($this->_messages), 'messageIndex');
        $message = $this->_messages[$messageIndex];
        $variable = $this->_messageToVariableBinding->getValue($message);

        return $this->updateMessageVariable($message, $variable);
    }

    protected function updateMessageVariable(Message $message, Variable $variable)
    {
        throw new Exception();
    }

    /**
     * Resets the marginal of the variables a factor is connected to
     */
    public function resetMarginals()
    {
        $allValues = $this->_messageToVariableBinding->getAllValues();
        foreach ($allValues as $currentVariable) {
            $currentVariable->resetToPrior();
        }
    }

    /**
     * Sends the ith message to the marginal and returns the log-normalization constant
     *
     * @param $messageIndex
     * @return
     *
     * @throws Exception
     */
    public function sendMessageIndex($messageIndex)
    {
        Guard::argumentIsValidIndex($messageIndex, count($this->_messages), 'messageIndex');

        $message = $this->_messages[$messageIndex];
        $variable = $this->_messageToVariableBinding->getValue($message);

        return $this->sendMessageVariable($message, $variable);
    }

    abstract protected function sendMessageVariable(Message $message, Variable $variable);

    abstract public function createVariableToMessageBinding(Variable $variable);

    protected function createVariableToMessageBindingWithMessage(Variable $variable, Message $message): Message
    {
        $this->_messageToVariableBinding->setValue($message, $variable);
        $this->_messages[] = $message;
        $this->_variables[] = $variable;

        return $message;
    }

    public function __toString(): string
    {
        return $this->_name;
    }
}
