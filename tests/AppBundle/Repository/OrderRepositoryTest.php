<?php 

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testisOverbooking()
    {
        $result = $this->entityManager
            ->getRepository(Order::class)
            ->isOverbooking('2018-04-30')
        ;

        $this->assertNotFalse(true);
    }

    public function testisNotOverbooking()
    {
        $result = $this->entityManager
            ->getRepository(Order::class)
            ->isOverbooking('2018-04-04')
        ;

        $this->assertFalse(false);
    }


    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}