<?php
/**
 * User by ID action class.
 *
 * PHP version 5
 *
 * @category Action
 * @package  StatusNet
 * @author   Evan Prodromou <evan@status.net>
 * @author   Robin Millette <millette@status.net>
 * @license  http://www.fsf.org/licensing/licenses/agpl.html AGPLv3
 * @link     http://status.net/

 * StatusNet - the distributed open-source microblogging tool
 * Copyright (C) 2008, 2009, StatusNet, Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('GNUSOCIAL')) { exit(1); }

/**
 * User by ID action class.
 *
 * @category Action
 * @package  StatusNet
 * @author   Evan Prodromou <evan@status.net>
 * @author   Robin Millette <millette@status.net>
 * @license  http://www.fsf.org/licensing/licenses/agpl.html AGPLv3
 * @link     http://status.net/
 */
class UserbyidAction extends ShowstreamAction
{
    protected function doPreparation()
    {
        // accessing by ID just requires an ID, not a nickname
        $this->target = Profile::getByID($this->trimmed('id'));

        // For local users when accessed by id number, redirect with
        // the nickname as argument instead of id.
        if ($this->target->isLocal()) {
            // Support redirecting to FOAF rdf/xml if the agent prefers it...
            // Internet Explorer doesn't specify "text/html" and does list "*/*"
            // at least through version 8. We need to list text/html up front to
            // ensure that only user-agents who specifically ask for RDF get it.
            $page_prefs = 'text/html,application/xhtml+xml,application/rdf+xml,application/xml;q=0.3,text/xml;q=0.2';
            $httpaccept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null;
            $type       = common_negotiate_type(common_accept_to_prefs($httpaccept),
                                                common_accept_to_prefs($page_prefs));
            $page       = $type === 'application/rdf+xml' ? 'foaf' : 'showstream';
            $url        = common_local_url($page, array('nickname' => $this->target->getNickname()));
            common_redirect($url, 303);
        }
    }
}
