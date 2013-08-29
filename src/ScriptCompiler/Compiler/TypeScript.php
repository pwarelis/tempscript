<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class TypeScript extends LanguageCompiler {
	protected $baseLanguage = "js";
	protected $defaults = array(
		"removeComments"
	);
	protected $app = "tsc";

	public function compile(Resource $resource) {
		$this->runApp("{$this->flags} --out {$resource->hash} {$resource->path}");
	}

}
