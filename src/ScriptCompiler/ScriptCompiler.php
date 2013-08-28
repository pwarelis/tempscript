<?php
namespace ScriptCompiler;

use ScriptCompiler\Cache\CacheInterface;
use ScriptCompiler\Cache\DiscCache;

class ScriptCompiler {
	protected $_paths;
	protected $_urls;

	public function __construct($config = array()) {
		$config = $this->mergeConfigs($config);

		if ($config["cache"] instanceof DiscCache) {
			$cache = $config["cache"];
		} else {
			$cache = new DiscCache($config["cache"]);
		}

		$paths = new PathManager($config["aliases"], $config["doc_root"]);

		$this->_resources = new ResourceManager(array(
			"types" => $config["types"],
			"cache_remote" => $config["cache_remote"]
		));
		$this->_resources->setCache($cache);
		$this->_resources->setPathManager($paths);
	}


	private function mergeRecursive(&$array1, &$array2) {
		$merged = $array1;
		foreach ($array2 as $key => &$value) {
			if (is_array($value) && isset ($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = $this->mergeRecursive($merged[$key], $value);
			} else {
				$merged[$key] = $value;
			}
		}
		return $merged;
	}

	protected function mergeConfigs($config) {

		if (!is_array($config)) {
			throw new \Exception("Configuration must be an array");
		}

		$dc = $_SERVER['DOCUMENT_ROOT'];
		$defaults = array(
			"doc_root" => $dc,
			"cache" => "{$dc}/cache",
			"aliases" => array(),
			"cache_remote" => true,
			"types" => array(
				'soy' => 'ScriptCompiler\Compiler\Soy',
				'css' => 'ScriptCompiler\Compiler\Css',
				'scss' => 'ScriptCompiler\Compiler\Scss',
				'sass' => 'ScriptCompiler\Compiler\Sass',
				'less' => 'ScriptCompiler\Compiler\Less',
				'js' => 'ScriptCompiler\Compiler\UglifyJs2',
				'coffee' => 'ScriptCompiler\Compiler\CoffeeScript',
			)
		);
		return $this->mergeRecursive($defaults, $config);
	}

	public function add($resource) {
		$this->_resources->addResources($resource, ResourceManager::SCRIPT_APPEND);
	}

	public function prepend($resource) {
		$this->_resources->addResources($resource, ResourceManager::SCRIPT_PREPEND);
	}

	public function compileResources($urlPath = null) {
		$this->_urls = $this->_resources->compileResources();
	}

	public function __toString() {
		if (!$this->_urls) {
			try {
				$this->compileResources();
			} catch (\Exception $e) {
				die($e->getMessage());
			}
		}

		$tags = "";
		foreach ($this->_urls as $base => $url) {
			switch ($base) {
				case "css":
					$tags .= "<link href=\"{$url}\" rel=\"stylesheet\" type=\"text/css\" />";
					break;
				case "js":
					$tags .= "<script src=\"{$url}\"></script>";
					break;
			}
		}
		return $tags;
	}

}
