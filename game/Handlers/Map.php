<?php

namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Helper;

class Map extends AbstractHandler
{
    /**
     * @var array
     */
    protected $map;

    public function showAll()
    {
        $db = $this->game->db;
        $cxallmap = \player\getqy_all($db);

        $locationIds = [];
        $cities = [];
        $wildAreas = [];
        foreach ($cxallmap as $v) {
            if (!$v['is_launched'] || !$v['is_portal']) {
                continue;
            }
            if ($v['type'] == 1) {
                $locationIds[] = $v['teleport'];
                $cities[] = $v;
                continue;
            }
            $wildAreas[] = $v;
        }

        if (!empty($locationIds)) {
            $locations = $this->db()->select('mid', ['mid(id)', 'mname(name)'], ['mid' => $locationIds]);
            $locationsMap = [];
            foreach ($locations as $v) {
                $locationsMap[$v['id']] = $v['name'];
            }
            foreach ($cities as $k => &$v) {
                $mid = $v['teleport'];
                if (!isset($locationsMap[$mid])) {
                    unset($cities[$k]);
                    continue;
                }
                $v['resurrection'] = $locationsMap[$mid];
            }
        }

        $data['cities'] = $cities;
        $data['wildAreas'] = $wildAreas;

        $this->display('allmap', $data);
    }

    public function showArea()
    {
        $player = \player\getPlayerById($this->game->db, $this->uid(), true);
        $mid = $this->db()->get('mid', '*', ['mid' => $player->nowmid]);
        $locations = $this->db()->select('mid', '*', ['mqy' => $mid['mqy']]);

        foreach ($locations as $v) {
            $this->map[$v['mid']] = $v;
        }

        $arr = array_fill(0, 60, array_fill(0, 60, 0));
        $x = 29;
        $y = 29;
        $this->recurLoc($mid, $x, $y, $arr);

        // 找到有效地图的四个定点
        $minX = 59;
        $maxX = 0;
        $minY = 59;
        $maxY = 0;
        foreach ($arr as $i => $v) {
            foreach ($v as $k => $n) {
                if (empty($n)) {
                    continue;
                }
                if ($i < $minY) {
                    $minY = $i;
                }
                if ($i > $maxY) {
                    $maxY = $i;
                }
                if ($k < $minX) {
                    $minX = $k;
                }
                if ($k > $maxX) {
                    $maxX = $k;
                }
            }
        }
        // 删除四个地点外的所有无效节点
        foreach ($arr as $i => $v) {
            if ($i < $minY || $i > $maxY) {
                unset($arr[$i]);
                continue;
            }
            foreach ($v as $k => $n) {
                if ($k < $minX || $k > $maxX) {
                    unset($arr[$i][$k]);
                }
            }
        }

        $data = [];
        $data['mid'] = $mid;
        $data['gonowmid'] = $this->encode("cmd=gomid&newmid={$player->nowmid}");

        $isAdmin = $this->session['is_admin'];
        foreach ($arr as $i => $v) {
            foreach ($v as $k => $n) {
                if (!empty($n)) {
                    if (is_numeric($n)) {
                        $arr[$i][$k] = '<div class="loc">';
                        if ($n == $mid['mid']) {
                            if ($isAdmin) {
                                $arr[$i][$k] .= sprintf('<span class="font-bold inline-block" style="color: green;" title="%s">', $n);
                            } else {
                                $arr[$i][$k] .= '<span class="font-bold inline-block" style="color: green;">';
                            }
                            $arr[$i][$k] .= $this->map[$n]['mname'];
                            $arr[$i][$k] .= '</span>';
                        } else {
                            if ($isAdmin) {
                                $arr[$i][$k] .= sprintf('<span title="%s">%s</span>', $n, $this->map[$n]['mname']);
                            } else {
                                $arr[$i][$k] .= $this->map[$n]['mname'];
                            }
                        }
                        $arr[$i][$k] .= '</div>';
                    }
                }
            }
        }
        $data['map'] = $arr;
        $this->display('map', $data);
    }

    protected function recurLoc(array $mid, int $x, int $y, array &$arr)
    {
        if (!empty($arr[$x][$y]) || !isset($arr[$x][$y])) {
            return;
        }
        $arr[$x][$y] = $mid['mid'];
        if (!empty($mid['mup']) && isset($this->map[$mid['mup']])) {
            $arr[$x - 1][$y] = '|';
            $this->recurLoc($this->map[$mid['mup']], $x - 2, $y, $arr);
        }
        if (!empty($mid['mdown']) && isset($this->map[$mid['mdown']])) {
            $arr[$x + 1][$y] = '|';
            $this->recurLoc($this->map[$mid['mdown']], $x + 2, $y, $arr);
        }
        if (!empty($mid['mleft']) && isset($this->map[$mid['mleft']])) {
            $arr[$x][$y - 1] = '—';
            $this->recurLoc($this->map[$mid['mleft']], $x, $y - 2, $arr);
        }
        if (!empty($mid['mright']) && isset($this->map[$mid['mright']])) {
            $arr[$x][$y + 1] = '—';
            $this->recurLoc($this->map[$mid['mright']], $x, $y + 2, $arr);
        }
    }
}