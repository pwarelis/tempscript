<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class UglifyJs2 extends LanguageCompiler {
	protected $baseLanguage = "js";
	protected $defaults = array(
		"screw-ie8",
		"compress",
		"mangle" => "sort=true"
	);
	protected $app = "uglifyjs";

	public function compile(Resource $resource) {
		$this->runApp("{$resource->path} {$this->flags} --output {$resource->hash}");
	}

}
