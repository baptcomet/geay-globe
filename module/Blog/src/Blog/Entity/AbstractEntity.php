<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    /** @deprecated */
    const OFFLINE = 0;
    /** @deprecated */
    const ONLINE = 1;
    /** @deprecated */
    const ARCHIVED = 2;

    const STATUS_OFFLINE = 0;
    const STATUS_ONLINE = 1;
    const STATUS_ARCHIVED = 2;

    /** @ORM\Column(name="status", type="integer", nullable=false, options={"default" = 1, "comment" = "0 = Inactif - 1 = Publié - 2 = Archivé"}) */
    protected $status = self::STATUS_ONLINE;

    /** @deprecated */
    public function __construct()
    {
        $this->status = self::STATUS_ONLINE;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public static function getStaticStatusList()
    {
        return array(
            self::STATUS_OFFLINE => 'Inactif',
            self::STATUS_ONLINE => 'Publié',
            self::STATUS_ARCHIVED => 'Archivé',
        );
    }

    /**
     * @param $status
     * @return null|string
     */
    public static function getStaticFormattedStatus($status)
    {
        return self::getStaticFormattedValueFromArray($status, self::getStaticStatusList());
    }

    /**
     * @return array
     */
    public function getStatusList()
    {
        return self::getStaticStatusList();
    }

    /**
     * @return string
     */
    public function getStatusLib()
    {
        return self::getStaticFormattedValueFromArray($this->status, self::getStaticStatusList());
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->status = self::STATUS_OFFLINE;
        return $this;
    }

    /**
     * @param string|int $key
     * @param array $array
     * @return null|string
     */
    public static function getStaticFormattedValueFromArray($key, $array)
    {
        $return = null;

        if (isset($array[$key])) {
            $return = (string) $array[$key];
        }

        return $return;
    }
}
