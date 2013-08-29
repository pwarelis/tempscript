<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class Css extends LanguageCompiler {
	protected $baseLanguage = "css";

	public function compile(Resource $resource) {
		$this->execute("minify {$resource->path} {$resource->hash}");
	}

}
