<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class Css extends LanguageCompiler {
	protected $baseLanguage = "css";
	protected $app = "minify";

	public function compile(Resource $resource) {
		$this->runApp("{$resource->path} {$resource->hash}");
	}

}
