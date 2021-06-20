<?php
namespace Xian\Handlers;

use Xian\AbstractHandler;

class VIP extends AbstractHandler
{
    public function showAdvantage()
    {
        $this->display('vip_advantage');
    }
}