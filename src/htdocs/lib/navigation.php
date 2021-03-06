<?php
class Navigation {

    private $config;

    function __construct($config = CONFIG) {
        $this->config = $config;
    }

    function createDeviceTree() {
        $tree = array('children' => array());
        $i = 0;
        $nodeById = array();
        foreach ($this->config['devices'] as $d) {
            if (isset($d['hidden']) && $d['hidden']) {
                continue;
            }
            $name = array_map('trim', explode('/', $d['name']));
            $desc = array_map('trim', explode('/', $d['description']));
            $node = &$tree;
            foreach ($desc as $s) {
                if (!isset($node['children'][$s])) {
                    $node['children'][$s] = array('children' => array());
                    $node['id'] = $i;
                    $nodeById[$i] = &$node;
                    $i++;
                }
                $node = &$node['children'][$s];
            }
            $node['name'] = $d['name'];
        }
        return array('tree' => $tree, 'nodeById' => $nodeById);
    }

    static function parseTree($node) {
        $list = array();
        if (isset($node['name'])) {
            $list[] = $node['name'];
        }
        if (isset($node['children'])) {
            foreach ($node['children'] as $n) {
                array_push($list, ...Navigation::parseTree($n));
            }
        }
        return $list;
    }
}
?>