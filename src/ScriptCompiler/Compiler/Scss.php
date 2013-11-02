<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class Scss extends Sass {
	protected $defaults = array(
		"scss",
		"cache-location" => "/tmp/sass-cache",
		"stop-on-error",
		"unix-newlines",
		"precision" => 10
	);

}
