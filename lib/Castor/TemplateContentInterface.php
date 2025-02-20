<?php
declare(strict_types=1);
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

namespace Jelix\Castor;


interface TemplateContentInterface
{
    /**
     * A name that allow to identify easily the template
     *
     * @return string a filename, a URI or another identifier
     */
    public function getName() : string;

    /**
     * @return bool says if the content of the template can be trusted
     */
    public function isTrusted() : bool;

    /**
     * @return string content of the template
     */
    public function getContent() : string;

    public function cacheTag() : string;

}