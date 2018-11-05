<?php

class Action {
	private $id = '';
	private $route = array();
	private $method = 'index';
	
	public function __construct($route) {
		$this->id = $route;
		
		$parts = explode('/', preg_replace('#[^a-zA-Z0-9_/]#', '', (string) $route));

		while ($parts) {
			$file = sprintf('%scontroller/%s.php', DIR_APPLICATION, implode('/', $parts));

			if (is_file($file)) {
				$this->route = implode('/', $parts);
				
				break;
			} else {
				$this->method = array_pop($parts);
			}
		}
	}

	public function getId() {
		return $this->id;
	}

	public function execute($registry, array $args = array()) {
		if (substr($this->method, 0, 2) == '__') {
			return new \Exception('Error: Magic methods disabled!');
		}

		$file  = sprintf('%scontroller/%s.php', DIR_APPLICATION, $this->route);
		
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $this->route);
		
		if (is_file($file)) {
			include_once modification($file);
		
			$controller = new $class($registry);
			
			$reflection = new ReflectionClass($class);
		
			if ($reflection->hasMethod($this->method) && $reflection->getMethod($this->method)->getNumberOfRequiredParameters() <= count($args)) {
				return call_user_func_array(array($controller, $this->method), $args);
			}
		}
		
		return new \Exception(sprintf('Error: Could not call %s/%s!', $this->route, $this->method));
	}
}
