<?php
/**
 * Created by PhpStorm.
 * User: jbravo
 * Date: 25/05/15
 * Time: 11:39
 */

namespace ConsoleMessage;

use Simpleue\Job\Job;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleMessageJob implements  Job {

    const STOP_INSTRUCTION = "STOP";

    private $consoleOutput;
    private $decodedMessage;

    public function __construct(OutputInterface $consoleOutput) {
        $this->consoleOutput = $consoleOutput;
    }

    /**
     * @param $message : JSON
     *  {
     *      "text" : "message text",
     *      "color" : ["green" | "red" | "blue" | ...]
     *  }
     */
    public function manage($message) {
        if (!$this->validateMessage($message))
            return FALSE;
        $this->printMessage();
        return TRUE;
    }

    private function validateMessage($message) {
        $this->decodedMessage = json_decode($message, true);
        if (!array_key_exists('text', $this->decodedMessage))
            return FALSE;
        return TRUE;
    }

    private function printMessage() {
        $message = $this->decodedMessage['text'];
        $color = $this->getColor();

        $this->consoleOutput->writeln("<fg=$color>$message</fg=$color>");
    }

    private function getColor() {
        if (array_key_exists('color', $this->decodedMessage))
            return $this->decodedMessage['color'];
        return "white";
    }

    public function mustStop($job) {
        return (strtoupper($job) === self::STOP_INSTRUCTION);
    }
}