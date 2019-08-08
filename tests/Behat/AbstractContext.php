<?php

namespace ModernGame\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\DBAL\Connection;
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
    }

    /**
     * @AfterScenario
     */
    public function clearData(AfterScenarioScope $scope)
    {
        /** @var Connection $connection */
        $connection = $this->kernel->getContainer()->get('doctrine')->getConnection();

        foreach ($connection->fetchAll('SHOW TABLES') as $table) {
            $connection->createQueryBuilder()
                ->delete($table['Tables_in_test'])
                ->where('true')
                ->execute();
        }
    }

    /**
     * @Then debug
     */
    public function debug()
    {
        $this->requireResponse();

        print_r((string)$this->response->getBody() . ' Code: ' . $this->response->getStatusCode());
    }
}
