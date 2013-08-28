<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class CoffeeScript extends LanguageCompiler {
	protected $baseLanguage = "js";

	public function compile(Resource $resource) {
		$command = "coffee --compile {$resource->path} {$this->flags} --output {$resource->hash}";
		$this->execute($command);
	}

}
