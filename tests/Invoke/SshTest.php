<?php

use Invoke\Server;

class SshTest extends PHPUnit_Framework_TestCase {

	/**
	 * Stress test the defautl driver
	 */
	public function testStress()
	{
		$server = new Server('128.199.46.113', 'root', 'cleodavid', 22);
		$server->connect();
		$this->assertEquals('working', trim($server->exec('echo working')));
	}
} 