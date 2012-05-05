<?php
/*
Copyright 2009-2012 Guillaume Boudreau, Andrew Hopkinson

This file is part of Greyhole.

Greyhole is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Greyhole is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Greyhole.  If not, see <http://www.gnu.org/licenses/>.
*/

class MD5WorkerCliRunner extends AbstractCliRunner {
	private $drives;
	
	function __construct($options) {
		$pid = pcntl_fork();
		if ($pid == -1) {
			$this->log("Error spawning child md5-worker!");
			$this->finish(1);
		}
		if ($pid == 0) {
			// Child
			parent::__construct($options);
			if (is_array($this->options['drive'])) {
				$this->drives = $this->options['drive'];
			} else {
				$this->drives = array($this->options['drive']);
			}
		} else {
			// Parent
			echo $pid;
			$this->finish(0);
		}
	}
	
	public function run() {
		md5_worker_thread($this->drives);
	}
}

?>