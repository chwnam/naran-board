<?php

namespace Naran\Board\Models\Objects;

class Style {
	public $handle;

	public $src;

	public $deps;

	public $ver;

	public $media;

	/**
	 * Style constructor.
	 *
	 * @param string $handle
	 * @param string $src
	 * @param string|array $deps
	 * @param false|null $ver
	 * @param string $media
	 */
	public function __construct( $handle, $src, $deps = [], $ver = false, $media = 'all' ) {
		$this->handle = $handle;
		$this->src    = $src;
		$this->deps   = (array) $deps;
		$this->ver    = $ver;
		$this->media  = $media;
	}
}
