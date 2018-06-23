<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 22/06/2018
 * Time: 20:29
 */

namespace Tests\Serializer;


class SubObject extends MyObject
{
    /**
     * @var \DateTime
     */
    private $finish;
    /**
     * @var float
     */
    private $total;

    /**
     * @return \DateTime
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * @param \DateTime $finish
     * @return SubObject
     */
    public function setFinish($finish)
    {
        $this->finish = $finish;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     * @return SubObject
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }
}