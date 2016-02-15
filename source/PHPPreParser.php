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

require_once 'phing/tasks/ext/phppre/PHPPreStack.php';
require_once 'phing/tasks/ext/phppre/PHPPreDirectiveFactory.php';
require_once 'phing/tasks/ext/phppre/PHPPreActionSet.php';

/**
 * Class PHPPreParser
 *
 * @author     Maciej Trynkowski <maciej.trynkowski@miltar.pl>
 * @author     Wojciech Trynkowski <wojciech.trynkowski@miltar.pl>
 * @license    GNU Lesser General Public License, version 3
 * @version    $Id$
 * @package    phing.tasks.ext
 * @subpackage phppre
 * @link       https://github.com/PHPPre/Phing-PHPPre
 */
class PHPPreParser
{
    /**
     * @var array
     */
    private $fileLines;

    /**
     * @var PHPPreStack
     */
    private $stack;

    /**
     * @var string
     */
    private $outputMode = PHPPreTask::OUTPUT_MODE_REMOVE;

    /**
     * @param $outputMode
     * @throws Exception
     */
    public function setOutputMode($outputMode)
    {
        if (!in_array($outputMode, [PHPPreTask::OUTPUT_MODE_REMOVE,
                                    PHPPreTask::OUTPUT_MODE_COMMENT,
                                    PHPPreTask::OUTPUT_MODE_CLEAR])) {
            throw new Exception("Invalid outputMode value");
        }
        $this->outputMode = $outputMode;
    }

    /**
     * Parser constructor.
     * @param $fileLines
     */
    public function __construct($fileLines)
    {
        $this->fileLines = $fileLines;
        $this->stack = new PHPPreStack();
    }

    /**
     * Parser destructor.
     */
    public function __destruct()
    {
        unset($this->fileLines);
        unset($this->stack);
    }

    /**
     * @param string $line
     * @return mixed
     */
    private function splitPreprocessorLine($line)
    {

        preg_match('/^#([a-zA-Z0-9_]*)[\s]*(?:$|([^#]*))/', $line, $result);
        array_shift($result);
        $result[0] = strtolower($result[0]);
        if (!array_key_exists(1, $result)) {
            $result[1] = null;
        }
        return $result;
    }

    /**
     * @param $definitions
     * @return mixed
     */
    public function parse(&$definitions)
    {
        $lineNumber = 0;
        $actionSet = new PHPPreActionSet($this->outputMode);
        foreach ($this->fileLines as $line) {
            if (preg_match('/^[\s]*#.*/', $line)) {
                $line = trim($line);
                $parsedLine = $this->splitPreprocessorLine($line);
                $tag = PHPPreDirectiveFactory::getInstance()->createTag($parsedLine[0], $parsedLine[1], $lineNumber, $definitions);
                $tag->handle($this->stack, $actionSet);
            }
            $lineNumber++;
        }
        $actionSet->executeActions($this->fileLines);
        return $this->fileLines;
    }
}
