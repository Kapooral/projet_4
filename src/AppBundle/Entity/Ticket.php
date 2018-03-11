<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(min=2, max=50, minMessage="Le prénom doit être de {{ limit }} caractères minimum.", maxMessage="Le prénom ne peut excéder {{ limit }} caractères.")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Assert\Length(min=2, max=50, minMessage="Le nom doit être de {{ limit }} caractères minimum.", maxMessage="Le nom ne peut excéder {{ limit }} caractères.")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\Type(type="string", message="Erreur sur le pays.")
     * @Assert\Length(min=2, max=5, minMessage="Erreur sur le pays.", maxMessage="Erreur sur le pays.")
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="date")
     * @Assert\DateTime(message="Le format de la date est incorrect.")
     */
    private $birthDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="reducePrice", type="boolean")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Type(type="bool", message="Erreur sur la case prix réduit.")
     */
    private $reducePrice;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order", inversedBy="tickets")
     * @ORM\JoinColumn(name="order_code", referencedColumnName="orderCode", nullable=false)
     */
    private $order;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Ticket
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return Ticket
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set birthDate.
     *
     * @param \DateTime $birthDate
     *
     * @return Ticket
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate.
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set reducePrice.
     *
     * @param bool $reducePrice
     *
     * @return Ticket
     */
    public function setReducePrice($reducePrice)
    {
        $this->reducePrice = $reducePrice;

        return $this;
    }

    /**
     * Get reducePrice.
     *
     * @return bool
     */
    public function getReducePrice()
    {
        return $this->reducePrice;
    }

    /**
     * Set order.
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return Ticket
     */
    public function setOrder(\AppBundle\Entity\Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return \AppBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        $this->getOrder()->increaseTicket();
    }
}
