<?php

namespace App\traits;

trait Mafia
{
    protected $roles = [
        'Mafia' => 'Mafia',
        'Doctor' => 'Doctor',
        'Detective' => 'Detective',
        'Villager1' => 'Villager1',
        'Villager2' => 'Villager2',
        'Villager3' => 'Villager3',
        'Villager4' => 'Villager4',
        'Villager5' => 'Villager5',
        'Villager6' => 'Villager6',
        'Villager7' => 'Villager7'
    ];

    /**
     * @return array
     */
    public function getRoleKeys(): array
    {
        return array_keys($this->roles);
    }

    /**
     * @return int|string
     * @throws \Exception
     */
    public function getRandomRole(): int|string
    {
        return $this->getRoleKeys()[random_int(0, count($this->roles) - 1)];
    }
}