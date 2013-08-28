<?php

namespace ScriptCompiler\Exception;

class MissingCompilerException extends \Exception {
	protected $command;

	public function __construct($msg, $command) {
		parent::__construct($msg);
		$this->setCommand($command);
	}

	final public function setCommand($command) {
		$this->command = $command;
	}

	final public function getCommand() {
		return $this->command;
	}

}
