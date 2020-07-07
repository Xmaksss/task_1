<?php

namespace App\Distributors;

/**
 * Class DistributorProcessor
 * @package App\Distributors
 */
class DistributorProcessor
{
    /** @var int  */
    protected $_socketPort;
    /** @var int  */
    protected $_maxConnections;
    /** @var resource */
    protected $_socket;
    /** @var DistributorInterface[]  */
    protected $_distributors = [];

    /**
     * DistributorProcessor constructor.
     * @param int $port
     * @param int $maxConnections
     */
    public function __construct(int $port, int $maxConnections = 5)
    {
        $this->_socketPort = $port;
        $this->_maxConnections = $maxConnections;
    }

    /**
     * @param array $distributors
     */
    public function loadDistributors(array $distributors)
    {
        foreach ($distributors as $distributor) {
            if (in_array(DistributorInterface::class, class_implements($distributor))) {
                $this->_distributors[] = new $distributor();
            }
        }
    }

    public function listen()
    {
        $socket = $this->getSocket();

        while (true) {
            $jobs = $this->getJobs();

            while (count($jobs)) {
                $ret = socket_accept($socket);
                $job = array_shift($jobs);
                $data = $this->serializeJob($job);
                socket_write($ret, $data, strlen($data));
                socket_close($ret);
            }

            sleep(1);
        }
    }

    /**
     * @param $job
     * @return false|string
     */
    protected function serializeJob($job)
    {
        return json_encode($job);
    }

    /**
     * @return array
     */
    protected function getJobs()
    {
        $data = [];

        foreach ($this->_distributors as $distributor) {
            $jobs = $distributor->getJobs();
            foreach ($jobs as $job)
                $data[] = [$distributor->getJobClass(), $job];
        }

        return $data;
    }

    /**
     * @return resource
     */
    protected function getSocket()
    {
        if ($this->_socket === null) {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_bind($socket, '127.0.0.1', $this->_socketPort);
            socket_listen($socket, $this->_maxConnections);
            $this->_socket = $socket;
        }

        return $this->_socket;
    }
}