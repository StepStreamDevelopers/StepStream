<?php
/**
 * StatusNet, the distributed open-source microblogging tool
 *
 * Form for silencing a user
 *
 * PHP version 5
 *
 * LICENCE: This program is free software: you can redistribute it and/or modify
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
 *
 * @category  Form
 * @package   StatusNet
 * @author    Evan Prodromou <evan@status.net>
 * @copyright 2009 StatusNet, Inc.
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link      http://status.net/
 */

if (!defined('STATUSNET')) {
    exit(1);
}

/**
 * Form for silencing a user
 *
 * @category Form
 * @package  StatusNet
 * @author   Evan Prodromou <evan@status.net>
 * @license  http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link     http://status.net/
 *
 * @see      UnSilenceForm
 */
class SilenceForm extends ProfileActionForm
{
    /**
     * Action this form provides
     *
     * @return string Name of the action, lowercased.
     */
    function target()
    {
        return 'silence';
    }

    /**
     * Title of the form
     *
     * @return string Title of the form, internationalized
     */
    function title()
    {
        // TRANS: Title of form to silence a user.
        return _m('TITLE','Silence');
    }

    /**
     * Description of the form
     *
     * @return string description of the form, internationalized
     */
    function description()
    {
        // TRANS: Description of form to silence a user.
        return _("This user can't post");
    }
}
