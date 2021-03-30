<?php

namespace MNGame\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Exception;
use Imbo\BehatApiExtension\Context\ApiContext;
use MNGame\Service\EnvironmentService;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractContext extends ApiContext implements Context
{
    /** @var KernelInterface */
    private KernelInterface $kernel;
    private string $value;

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

            $connection->exec('ALTER TABLE '. $table['Tables_in_test'] .' AUTO_INCREMENT = 1');
        }
    }

    /**
     * @When I request :path using HTTP :method using stored param as :value
     */
    public function setToRequestQuery(string $path, string $method, string $value)
    {
        $this->setRequestPath($path . '?' . $value . '=' . $this->value);

        if (null === $method) {
            $this->setRequestMethod('GET', false);
        } else {
            $this->setRequestMethod($method);
        }

        return $this->sendRequest();
    }

    /**
     * @Then I store :value
     */
    public function storeValue($value)
    {
        $this->value = ((array)$this->getResponseBody())[$value];
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

    protected function getManager(): ObjectManager
    {
        return $this->kernel->getContainer()->get('doctrine')->getManager();
    }
}
