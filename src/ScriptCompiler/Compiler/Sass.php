<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class Sass extends LanguageCompiler {
	protected $baseLanguage = "css";
	protected $defaults = array(
		"style" => "compressed",
		"stop-on-error",
		"unix-newlines",
		"precision" => 10
	);
	protected $app = "sass";

	public function compile(Resource $resource) {
		$this->runApp("{$this->flags} {$resource->path} {$resource->hash}");
	}

}
