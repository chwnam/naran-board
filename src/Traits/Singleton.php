<?php


namespace Naran\Board\Traits;


trait Singleton {
	private static $instance = null;

	private function __sleep() {
	}

	private function __wakeup() {
	}

	private function __clone() {
	}

	public static function getInstance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new static();
		}

		return self::$instance;
	}
}
