<?php

namespace ModernGame\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Exception;
use Imbo\BehatApiExtension\Context\ApiContext;
use ModernGame\Service\EnvironmentService;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractContext extends ApiContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function before(BeforeScenarioScope $scope)
    {
        $env = new EnvironmentService($this->kernel->getEnvironment());

        if ($env->isProd() || $env->isDev()) {
            throw new Exception('Can\'t run tests on this env');
        }

        /** @var Connection $connection */
        $connection = $this->kernel->getContainer()->get('doctrine')->getConnection();

        foreach ($connection->fetchAll('SHOW TABLES') as $table) {
            $connection->createQueryBuilder()
                ->delete($table['Tables_in_test'])
                ->where('1')
                ->execute();
        }
    }

    protected function getmanager(): ObjectManager
    {
        return $this->kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @Then debug
     */
    public function debug()
    {
        $this->requireResponse();

        echo (string)$this->response->getBody() . ' Code: ' . $this->response->getStatusCode();
        die;
    }
}
