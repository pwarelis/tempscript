<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class Soy extends UglifyJs2 {

	public function compile(Resource $resource) {

		$jar = ROOT_PATH . "/public/javascript/libraries/closure/SoyToJsSrcCompiler.jar";
		$command = "/usr/bin/java -jar {$jar} --outputPathFormat {$resource->hash} {$resource->path}";
		$this->execute($command);
		$resource->path = $resource->hash;

		parent::compile($resource);
	}

}
