<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Helper;
use function player\getPlayerById;

class Im extends AbstractHandler
{
    public function showMessages()
    {
        $player = getPlayerById($this->db(), $this->uid(), true);
        $type = $this->params['type'] ?? 1;
        $types = [
            'AND #system' => ['tid' => 0, 'type' => 1],
            'AND #system-player' => ['tid' => $player->id, 'type' => 1],
            'AND #player' => ['tid' => $this->uid(),'type' => 2],
        ];
        if ($player->partyId) {
            $types['AND #party'] = ['tid' => $player->partyId, 'type' => 3];
        }
        $messages = $this->db()->select('im', '*', [
            'OR' => $types,
            'ORDER' => ['id' => 'DESC'],
            'LIMIT' => 10
        ]);
        $data = [
            'type'=> $type,
            'messages' => $messages,
        ];
        $this->display('liaotian', $data);
    }
}