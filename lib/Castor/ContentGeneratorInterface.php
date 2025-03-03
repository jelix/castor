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

interface ContentGeneratorInterface
{
    public function meta(CastorCore $castor, RuntimeContainer $t);

    public function content(CastorCore $castor, RuntimeContainer $t);
}