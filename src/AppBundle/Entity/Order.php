<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Order
 *
 * @ORM\Table(name="`order`")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 */
class Order
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
     * @ORM\Column(name="orderCode", type="string", length=255, unique=true)
     */
    private $orderCode;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(checkMX=true, message="Adresse e-mail invalide")
     * @Assert\Length(max=70, maxMessage="Votre adresse e-mail ne peut excéder {{ limit }} caractères.")
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bookingDate", type="date")
     * @Assert\DateTime(message="Le format de la date est incorrect.")
     */
    private $bookingDate;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $nbTicket = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="fullDay", type="boolean")
     * @Assert\Type(type="bool", message="Votre choix est incorrect.")
     */
    private $fullDay;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="order")
     */
    private $tickets;


    public function __construct()
    {
        $this->bookingDate = new \DateTime();
        $this->fullDay = true;
        $this->tickets = new ArrayCollection();
    }

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
     * Set orderCode.
     *
     * @param string $orderCode
     *
     * @return Order
     */
    public function setOrderCode($orderCode)
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    /**
     * Get orderCode.
     *
     * @return string
     */
    public function getOrderCode()
    {
        return $this->orderCode;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set bookingDate.
     *
     * @param \DateTime $bookingDate
     *
     * @return Order
     */
    public function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    /**
     * Get bookingDate.
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * Set nbTicket.
     *
     * @param int $nbTicket
     *
     * @return Order
     */
    public function setNbTicket($nbTicket)
    {
        $this->nbTicket = $nbTicket;

        return $this;
    }

    /**
     * Get nbTicket.
     *
     * @return int
     */
    public function getNbTicket()
    {
        return $this->nbTicket;
    }

    public function increaseTicket()
    {
        $this->nbTicket++;
    }

    /**
     * Set fullDay.
     *
     * @param bool $fullDay
     *
     * @return Order
     */
    public function setFullDay($fullDay)
    {
        $this->fullDay = $fullDay;

        return $this;
    }

    /**
     * Get fullDay.
     *
     * @return bool
     */
    public function getFullDay()
    {
        return $this->fullDay;
    }

    /**
     * Add ticket.
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Order
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $ticket->setOrder($this);
        
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket.
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        return $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
