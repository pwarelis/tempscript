<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class Sass extends LanguageCompiler {
	protected $baseLanguage = "css";
	protected $defaults = array(
		"style" => "compressed",
		"cache-location" => "/tmp/sass-cache",
		"stop-on-error",
		"unix-newlines",
		"precision" => 10
	);

	public function compile(Resource $resource) {
		$this->execute("sass {$this->flags} {$resource->path} {$resource->hash}");
	}

}
