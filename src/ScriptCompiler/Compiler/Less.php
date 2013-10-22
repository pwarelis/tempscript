<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class Less extends LanguageCompiler {
	protected $baseLanguage = "css";
	protected $defaults = array(
	);

	public function compile(Resource $resource) {
		$this->execute("lessc {$resource->path} {$resource->hash}");
	}
}
