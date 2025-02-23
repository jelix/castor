<?php

/**
 * @author      Olivier Demah
 * @contributor Steven Jehannet, Laurent Jouanneau
 *
 * @copyright   2009 Olivier Demah, 2010 Steven Jehannet, 2025 L. Jouanneau
 *
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * gravatar plugin :  write the url corresponding to the image of the gravatar account identified by the given email address.
 *
 * @param \Jelix\Castor\CastorCore $tpl template engine
 * @param string $email account
 * @param array $params parameters for :
 *  1) the default avatar URL
 *  2) the default size of the image
 *  3) the username to be put in the "alt" attribute of the img
 */
function jtpl_function_html_gravatar(\Jelix\Castor\CastorCore $tpl, $email, $params = array())
{

    // if no default url is given for the default gravatar,
    // this will display the default Gravatar Image from gravatar.com
    if (!array_key_exists('default', $params)) {
        $params['default'] = null;
    }
    if (!array_key_exists('size', $params)) {
        $params['size'] = 60;
    }
    if (!array_key_exists('username', $params)) {
        $params['username'] = '';
    }

    $gravatarUrl = "https://www.gravatar.com/avatar/" . hash( "sha256", strtolower( trim( $email ) ));
    $gravatarUrl .= "?s=" . $params['size'];
    if ($params['default'] != null) {
        $gravatarUrl .= '&amp;default='.urlencode($params['default']);
    }

    echo '<img src="'.$gravatarUrl.'" class="gravatar" alt="'.htmlentities($params['username']).'"/>';
}
