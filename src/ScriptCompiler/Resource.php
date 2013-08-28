<?php

namespace ScriptCompiler;

class Resource {
	public $url;
	public $path = null;
	public $hash;

	public $isRemote;
	public $minified = null;
	public $modified = null;
	public $hasChanged = null;

	public $base;
	public $language;

	public function __construct($data) {
		$this->url = $data["url"];
		$this->isRemote = (boolean) parse_url($this->url, PHP_URL_HOST);

		if (isset($data["minified"])) {
			$this->minified = $data["minified"];
		}

		if (isset($data["language"])) {
			$this->language = $data["minified"];
		} else {
			$this->language = pathinfo($this->url, PATHINFO_EXTENSION);
		}
	}

	public function isMinified() {
		if (is_null($this->minified)) return (boolean) preg_match('/\.min\./', $this->url);
		return $this->minified;
	}

	public function setPath($path) {
		$this->path = $path;
	}

	public function setHash($hash) {
		$this->hash = $hash;
		if (is_null($this->hasChanged)) {
			$this->hasChanged = !file_exists($hash);
		}
		return $this->hasChanged;
	}

	public function getModifiedTime() {

		if ($this->isRemote) {
			$this->hasChanged = !file_exists($this->path);

			if ($this->hasChanged && !copy($this->url, $this->path)) {
				throw new \Exception("Cannot copy the resource: {$this->url}");
			}
		}

		if (!isset($this->modified)) {
			$this->modified = filemtime($this->path);
		}

		return $this->modified;
	}

}
