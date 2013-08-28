<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class Scss extends Sass {
	protected $defaults = array(
		"scss",
		"style" => "compressed",
		"stop-on-error",
		"unix-newlines",
		"precision" => 10
	);

}
