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

/**
 * Class PHPPreFileSaver
 *
 * @author     Maciej Trynkowski <maciej.trynkowski@miltar.pl>
 * @author     Wojciech Trynkowski <wojciech.trynkowski@miltar.pl>
 * @license    GNU Lesser General Public License, version 3
 * @version    $Id$
 * @package    phing.tasks.ext
 * @subpackage phppre
 * @link       https://github.com/PHPPre/Phing-PHPPre
 */
class PHPPreFileSaver
{
    /**
     * Windows line separator CRLF
     */
    const LINE_SEPARATOR_WINDOWS = "\r\n";

    /**
     * Linux line separator LF
     */
    const LINE_SEPARATOR_UNIX   = "\n";

    /**
     * MAC OS line separator CR
     */
    const LINE_SEPARATOR_MAC   = "\r";

    /**
     * File saved will use it as line separator.
     *
     * @var string
     */
    private $lineSeparator;

    /**
     * Creates file saver, which uses specified string as line separator.
     *
     * @param string $lineSeparator line separator
     */
    public function __construct($lineSeparator = PHPPreFileSaver::LINE_SEPARATOR_UNIX)
    {
        $this->lineSeparator = $lineSeparator;
    }


    /**
     * Saves file
     *
     * @param string $filePath file path
     * @param array $fileLines lines that will be saved
     */
    public function saveFile($filePath, $fileLines)
    {
        file_put_contents($filePath, implode($this->lineSeparator, $fileLines));
    }
}
