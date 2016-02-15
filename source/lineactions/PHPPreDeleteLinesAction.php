<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://phing.info>.
 *
 * @author     Maciej Trynkowski <maciej.trynkowski@miltar.pl>
 * @author     Wojciech Trynkowski <wojciech.trynkowski@miltar.pl>
 * @license    GNU Lesser General Public License, version 3
 * @version    $Id$
 * @package    phing.tasks.ext
 * @subpackage phppre
 * @link       https://github.com/PHPPre/Phing-PHPPre
 */

require_once 'phing/tasks/ext/phppre/lineactions/AbstractPHPPreLinesAction.php';

/**
 * Class PHPPreDeleteLinesAction
 *
 * @author     Maciej Trynkowski <maciej.trynkowski@miltar.pl>
 * @author     Wojciech Trynkowski <wojciech.trynkowski@miltar.pl>
 * @license    GNU Lesser General Public License, version 3
 * @version    $Id$
 * @package    phing.tasks.ext
 * @subpackage phppre
 * @link       https://github.com/PHPPre/Phing-PHPPre
 */
class PHPPreDeleteLinesAction extends AbstractPHPPreLinesAction
{

    /**
     * @param array $fileLines
     * @param $outputMode
     * @throws Exception
     */
    public function execute(&$fileLines, &$outputMode)
    {
        for ($i = $this->startLine; $i <= $this->endLine; $i++) {
            // function array_key_exist is very slow in php version 5.x, therefore we use combination of isset and is_null
            if (isset($fileLines[$i]) || is_null($fileLines[$i]) || array_key_exists($i, $fileLines)) {
                switch ($outputMode) {
                    case PHPPreTask::OUTPUT_MODE_REMOVE:
                        unset($fileLines[$i]);
                        break;
                    case PHPPreTask::OUTPUT_MODE_CLEAR:
                        $fileLines[$i] = '';
                        break;
                    case PHPPreTask::OUTPUT_MODE_COMMENT:
                        if (substr($fileLines[$i], 0, 3) !== "// ") {
                            $fileLines[$i] = '// '.$fileLines[$i];
                        }
                        break;
                    default:
                        throw new Exception("Internal error, unsupported output mode");
                }
            }
        }
    }
}
