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

require_once 'phing/Task.php';
require_once 'phing/tasks/ext/phppre/PHPPreFileLoader.php';
require_once 'phing/tasks/ext/phppre/PHPPreFileSaver.php';
require_once 'phing/tasks/ext/phppre/PHPPreParser.php';
require_once 'phing/tasks/ext/phppre/PHPPreLineSeparatorAttributeTranslator.php';

/**
 * PHPPre PHPPreTask
 *
 * @author     Maciej Trynkowski <maciej.trynkowski@miltar.pl>
 * @author     Wojciech Trynkowski <wojciech.trynkowski@miltar.pl>
 * @license    GNU Lesser General Public License, version 3
 * @version    $Id$
 * @package    phing.tasks.ext
 * @subpackage phppre
 * @link       https://github.com/PHPPre/Phing-PHPPre
 */
class PHPPreTask extends Task
{

    public static $reference;

    /**
     * from xml attribute
     *
     * @var string
     */
    protected $outputMode;

    /**
     * from xml attribute
     *
     * @var string|null
     */
    protected $lineSeparator;

    /**
     * @var array
     */
    protected $filesets = [];


    const OUTPUT_MODE_REMOVE = "REMOVE";
    const OUTPUT_MODE_COMMENT = "COMMENT";
    const OUTPUT_MODE_CLEAR = "CLEAR";

    /**
     * XML attribute output Mode setter
     *
     * @param string|null $outputMode
     */
    public function setOutputMode($outputMode)
    {
        $this->outputMode = $outputMode;
    }

    /**
     * XML attribute Line Separator setter
     *
     * @param string|null $lineSeparator
     */
    public function setLineSeparator($lineSeparator)
    {
        $lineSeparatorAttributeTranslator = new PHPPreLineSeparatorAttributeTranslator();
        $this->lineSeparator = $lineSeparatorAttributeTranslator->translate($lineSeparator);
    }

    /**
     * @param $message
     * @param integer $messageType
     */
    public static function logger($message, $messageType = Project::MSG_INFO)
    {
        if (!empty(self::$reference)) {
            self::$reference->log($message, $messageType);
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function defineGet($name)
    {
        return self::$reference->getProject()->getProperty($name);
    }

    /**
     * @param $name
     * @param $value
     */
    public static function defineSet($name, $value)
    {
        self::$reference->getProject()->setProperty($name, $value);
    }

    /**
     * @param FileSet $fs
     */
    public function addFileSet(FileSet $fs)
    {
        $this->filesets[] = $fs;
    }


    /**
     * @throws Exception
     */
    public function main()
    {

        self::$reference = &$this;

        foreach ($this->filesets as $fs) {
            $ds = $fs->getDirectoryScanner($this->getProject());
            $fromDir = $fs->getDir($this->getProject());
            $srcFiles = $ds->getIncludedFiles();
            $srcDirs = $ds->getIncludedDirectories();

            $this->logger('Preprocessing ' . count($srcFiles) . ((count($srcFiles) == 1) ? ' file' : ' files'));
            foreach ($srcFiles as $srcFile) {
                $fileLoader = new PHPPreFileLoader($fromDir . DIRECTORY_SEPARATOR . $srcFile);
                $parser = new PHPPreParser($fileLoader->getFileLines());
                if ($this->outputMode !== null) {
                    $parser->setOutputMode($this->outputMode);
                }
                try {
                    $this->logger(" * Preprocessing file: " . $srcDirs.DIRECTORY_SEPARATOR.$srcFile, Project::MSG_VERBOSE);
                    $definitions = [];
                    $parsedFileLines = $parser->parse($definitions);

                    if ($this->lineSeparator === null) {
                        $fileSaver = new PHPPreFileSaver();
                    } else {
                        $fileSaver = new PHPPreFileSaver($this->lineSeparator);
                    }

                    $fileSaver->saveFile($fromDir . DIRECTORY_SEPARATOR . $srcFile, $parsedFileLines);
                } catch (PHPPreParserException $ex) {
                    $this->logger('Error : file '.$srcFile.'('.$ex->getSourceLine().')'.$ex->getMessage(), Project::MSG_ERR);
                    throw $ex;
                } catch (Exception $ex) {
                    $this->logger('Error : file '.$srcFile.' '.$ex->getMessage(), Project::MSG_ERR);
                    throw $ex;
                }
            }
        }
    }
}
