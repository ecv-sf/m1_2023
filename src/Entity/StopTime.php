<?php

namespace App\Entity;

use App\Repository\StopTimeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StopTimeRepository::class)]
class StopTime implements ImportDatasInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stopTimes')]
    private Trip $trip;

    #[ORM\ManyToOne(inversedBy: 'stopTimes')]
    private Stop $stop;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: false)]
    private \DateTimeInterface $arrival;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: false)]
    private \DateTimeInterface $departure;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $stopSequence;

    public function __construct(Trip $trip, Stop $stop, \DateTime $arrival, \DateTime $departure, int $stopSequence)
    {
        $this->trip = $trip;
        $this->stop = $stop;
        $this->arrival = $arrival;
        $this->departure = $departure;
        $this->stopSequence = $stopSequence;
    }

    public static function createFromCsv(array $datas): self
    {
        return new self(
            $datas['trip'],
            $datas['stop'],
            \DateTime::createFromFormat('H:i:s', $datas['arrival_time']),
            \DateTime::createFromFormat('H:i:s', $datas['departure_time']),
            $datas['stop_sequence']
        );
    }
}
