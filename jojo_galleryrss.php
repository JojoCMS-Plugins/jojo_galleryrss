<?php
/**
 *
 * Copyright 2007 Michael Cochrane <code@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Michael Cochrane <code@gardyneholt.co.nz>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

class JOJO_Plugin_jojo_galleryrss extends JOJO_Plugin
{
    function galleryrss($content)
    {
        global $smarty;

        foreach (Jojo::listPlugins('external/SimplePie/simplepie.inc') as $pluginfile) {
            require_once($pluginfile);
            break;
        }

        /* Find all [[galleryrss:rssurl]] tags */
        preg_match_all('/\[\[galleryrss:([^\]]*)\]\]/', $content, $matches);
        foreach($matches[1] as $id => $url) {
            $url = str_replace('&amp;', '&', $url);

            /* Get the feed */
            $feed = new SimplePie($url, _CACHEDIR . '/feedcache');
            $items = $feed->get_items();

            /* Extract the img urls out of the feed */
            $images = array();
            foreach ($items as $itemid => $item) {
                $description = $item->get_description();
                preg_match_all('/<img([^>]*)\>/', $description, $imgmatches);
                if (isset($imgmatches[0][0])) {
                    preg_match_all('/src="([^"]*)"/', $imgmatches[0][0], $srcmatches);
                    if (isset($srcmatches[1][0])) {
                        $images[$itemid] = $srcmatches[1][0];
                    }
                }
            }

            /* Get the gallery html */
            $smarty->assign('feed', $feed);
            $smarty->assign('items', $items);
            $smarty->assign('images', $images);
            $html = $smarty->fetch('jojo_galleryrss.tpl');
            $content = str_replace($matches[0][$id], $html, $content);
        }
        return $content;
    }
}