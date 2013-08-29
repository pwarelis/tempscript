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

	public function compile(Resource $resource) {
		$this->execute("uglifyjs {$resource->path} {$this->flags} --output {$resource->hash}");
	}

}
