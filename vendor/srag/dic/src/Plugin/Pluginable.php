<?php

namespace srag\DIC\SrFileObjectTypeIcons\Plugin;

/**
 * Interface Pluginable
 *
 * @package srag\DIC\SrFileObjectTypeIcons\Plugin
 */
interface Pluginable
{

    /**
     * @return PluginInterface
     */
    public function getPlugin() : PluginInterface;


    /**
     * @param PluginInterface $plugin
     *
     * @return static
     */
    public function withPlugin(PluginInterface $plugin)/*: static*/ ;
}
