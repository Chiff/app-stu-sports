<?php


namespace App\Models\Netgrif;




use Faker\Provider\cs_CZ\DateTime;

class DateTimeNetgrif {

    private int $year;
    private int $month;
    private int $day;
    private int $hour;
    private int $minutes;
    private int $seconds;

    /** @var string[] $date */
    public function __construct(array $date = null)
    {
        if($date == null) return;
        $this->year = $date[0];
        $this->month = $date[1];
        $this->day = $date[2];
        $this->hour = $date[3];
        $this->minutes = $date[4];
        $this->seconds = $date[5];

    }

    /**
     * @return int|string
     */
    public function getYear(): int|string
    {
        return $this->year;
    }

    /**
     * @return int|string
     */
    public function getMonth(): int|string
    {
        return $this->month;
    }

    /**
     * @return int|string
     */
    public function getDay(): int|string
    {
        return $this->day;
    }

    /**
     * @return int|string
     */
    public function getHour(): int|string
    {
        return $this->hour;
    }

    /**
     * @return int|string
     */
    public function getMinutes(): int|string
    {
        return $this->minutes;
    }

    /**
     * @return int|string
     */
    public function getSeconds(): int|string
    {
        return $this->seconds;
    }



    /** @var string[] $date */
    public function setYear(array $date): void
    {
        $this->year = $date[0];
    }

    /** @var string[] $date */
    public function setMonth(array $date): void
    {
        $this->month = $date[1];
    }

    /** @var string[] $date */
    public function setDay(array $date): void
    {
        $this->day = $date[2];
    }

    /** @var string[] $date */
    public function setHour(array $date): void
    {
        $this->hour = $date[3];
    }

    /** @var string[] $date */
    public function setMinutes(array $date): void
    {
        $this->minutes = $date[4];
    }

    /** @var string[] $date */
    public function setSeconds(array $date): void
    {
        $this->seconds = $date[5];
    }

    /** @var string[] $date */
    public function toString()
    {
        return $this->year."-".$this->month."-".$this->day."T".$this->hour.":".$this->minutes.":".$this->seconds;
    }
    /** @var string[] $date */
    public function toDate():\DateTime
    {
        return new \DateTime($this->toString());
    }
}
