<?php

namespace ScriptCompiler;

use ScriptCompiler\Cache\DiscCache;
use ScriptCompiler\Compiler\LanguageCompiler;

class ResourceManager {
	/** @var  DiscCache */
	protected $cache;
	/** @var  PathManager */
	protected $paths;
	protected $types;
	protected $cacheRemoteScripts;

	protected $resources = array();
	protected $compilers = array();

	const SCRIPT_APPEND = 0;
	const SCRIPT_PREPEND = 1;

	public function __construct($config) {
		$this->now = time();
		$this->types = $config["types"];
		$this->cacheRemoteScripts = $config["cache_remote"];
	}

	public function setCache(DiscCache $cache) {
		$this->cache = $cache;
	}

	public function setPathManager(PathManager $pm) {
		$this->paths = $pm;
	}

	public function addResources($resources, $direction) {
		if (is_string($resources) || isset($resources["url"])) $resources = array($resources);

		foreach ($resources as $item) {
			$this->addResource($item, $direction);
		}
	}

	protected function getFilePath(Resource $resource) {
		if ($resource->language == 'soy') {
			return $resource->url;
		}
		if ($resource->isRemote) {
			return $this->cache->buildFilename($resource->url);
		}
		return $this->paths->getPath($resource->url);
	}

	protected function addResource($data, $direction) {
		if (is_string($data)) {
			$data = array("url" => $data);
		}

		foreach ($this->resources as $resource) {
			if ($resource->url == $data["url"]) return;
		}

		$resource = new Resource($data);
		$resource->path = $this->getFilePath($resource);

		if ($direction == self::SCRIPT_APPEND) {
			$this->resources[] = $resource;
		} else {
			array_unshift($this->resources, $resource);
		}
	}

	/**
	 * @param string $language
	 * @return LanguageCompiler
	 * @throws \Exception
	 */
	protected function getCompiler($language) {
		if (!isset($this->compilers[$language])) {

			if (!isset($this->types[$language])) {
				throw new \Exception("No compiler found for this language: {$language}");
			}

			$this->compilers[$language] = new $this->types[$language];
		}
		return $this->compilers[$language];
	}

	public function compileResources() {

		foreach ($this->resources as $resource) {
			$compiler = $this->getCompiler($resource->language);
			$compiler->compileResource($resource, $this->cache);
		}

		$sets = array();
		$lastModifyTime = array();

		foreach ($this->resources as $resource) {

			$base = $resource->base;

			if (!isset($sets[$base])) {
				$sets[$base] = array();
				$lastModifyTime[$base] = 0;
			}

			$sets[$base][] = $resource->hash;

			if ($resource->modified > $lastModifyTime[$base]) {
				$lastModifyTime[$base] = $resource->modified;
			}
		}

		$urls = array();

		foreach ($sets as $base => $fileList) {
			$masterCacheFile = $this->cache->buildFilename(implode($fileList).$lastModifyTime[$base]);
			$script = "{$masterCacheFile}.{$base}";

			if (!file_exists($script)) {
				// Copy all the files over to the master script file
				$fd = fopen($script, "w");
				foreach ($fileList as $file) {
					$terminator = ($base == "css") ? "\n" : ";";
					fwrite($fd, file_get_contents($file).$terminator);
				}
				fclose($fd);
			}

			$urls[$base] = "/cache/" . basename($script);
		}

		return $urls;
	}

}
