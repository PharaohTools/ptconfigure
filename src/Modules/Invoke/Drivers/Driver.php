<?php namespace Invoke\Drivers;

use Invoke\Server;

interface Driver {

	public function __construct(Server $server);
	public function connect();
	public function exec($command);

}