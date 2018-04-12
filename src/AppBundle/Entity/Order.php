<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Validator\Overbooking;

/**
 * Order
 *
 * @ORM\Table(name="`order`")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 * @UniqueEntity(fields="orderCode", message = "Une autre commande comporte déjà ce code de réservation. Veuillez recommencer.")
 */
class Order
{
    /**
     * @var string
     *
     * @ORM\Id
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
     * @Overbooking
     */
    private $bookingDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="whole_day", type="boolean")
     * @Assert\Type(type="bool", message="Votre choix est incorrect.")
     */
    private $wholeDay;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="order", cascade={"persist"})
     * @Assert\Valid()
     */
    private $tickets;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\Type("integer")
     */
    private $quantity;


    public function __construct()
    {
        $this->bookingDate = new \DateTime();
        $this->wholeDay = true;
        $this->tickets = new ArrayCollection();
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
     * Set wholeDay.
     *
     * @param bool $wholeDay
     *
     * @return Order
     */
    public function setWholeDay($wholeDay)
    {
        $this->wholeDay = $wholeDay;

        return $this;
    }

    /**
     * Get wholeDay.
     *
     * @return bool
     */
    public function getWholeDay()
    {
        return $this->wholeDay;
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

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return Order
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
