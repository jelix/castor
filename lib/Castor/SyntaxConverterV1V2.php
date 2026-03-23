<?php


/**
 * @author      Laurent Jouanneau
 *
 * @copyright   2026 Laurent Jouanneau
 *
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

namespace Jelix\Castor;

class SyntaxConverterV1V2
{
    public function convert($template)
    {
        $template = preg_replace_callback('/\\{(\\/?)([a-zA-Z]+.*?)\\}/sm', [$this, 'replaceFctCallback'], $template);
        $template = preg_replace_callback('/\\{([$@=])(.+?)\\}/sm', [$this, 'replaceOutputCallback'], $template);
        return $template;
    }

    public function replaceFctCallback($matches)
    {
        if ($matches[1] == '/') {
            return '{% end'.$matches[2].' %}';
        }
        return '{% '.$matches[2].' %}';
    }

    public function replaceOutputCallback($matches)
    {
        if ($matches[1] == '=') {
            return '{{'.$matches[2].'}}';
        }
        return '{{'.$matches[1].$matches[2].'}}';
    }
}