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

require_once 'phing/tasks/ext/phppre/directives/PHPPreIfDirective.php';
require_once 'phing/tasks/ext/phppre/directives/PHPPreIfDefDirective.php';
require_once 'phing/tasks/ext/phppre/directives/PHPPreIfNDefDirective.php';
require_once 'phing/tasks/ext/phppre/directives/PHPPreEndIfDirective.php';
require_once 'phing/tasks/ext/phppre/directives/PHPPreElseDirective.php';
require_once 'phing/tasks/ext/phppre/directives/PHPPreElseIfDirective.php';
require_once 'phing/tasks/ext/phppre/directives/PHPPreElseIfDefDirective.php';
require_once 'phing/tasks/ext/phppre/directives/PHPPreElseIfNDefDirective.php';

require_once 'phing/tasks/ext/phppre/directives/PHPPreMessageErrorDirective.php';
require_once 'phing/tasks/ext/phppre/directives/PHPPreMessageInfoDirective.php';
require_once 'phing/tasks/ext/phppre/directives/PHPPreMessageWarningDirective.php';

/**
 * Class PHPPreDirectiveFactory
 *
 * @author     Maciej Trynkowski <maciej.trynkowski@miltar.pl>
 * @author     Wojciech Trynkowski <wojciech.trynkowski@miltar.pl>
 * @license    GNU Lesser General Public License, version 3
 * @version    $Id$
 * @package    phing.tasks.ext
 * @subpackage phppre
 * @link       https://github.com/PHPPre/Phing-PHPPre
 */
class PHPPreDirectiveFactory
{

    private static $instance;

    /**
     * @return PHPPreDirectiveFactory
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new PHPPreDirectiveFactory();
        }
        return self::$instance;
    }

    /**
     * @param $tag
     * @param $argument
     * @param $line
     * @param $definitions
     * @return ElseDirective|EndIfDirective|IfDefDirective|IfNDefDirective|MessageErrorDirective
     * @throws Exception
     */
    public function createTag($tag, $argument, $line, &$definitions)
    {
        switch ($tag) {
            case 'if':
                $tag = new IfDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            case 'ifdef':
                $tag = new IfDefDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            case 'ifndef':
                $tag = new IfNDefDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            case 'endif':
                $tag = new EndIfDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            case 'else':
                $tag = new ElseDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            case 'elif':
                $tag = new ElseIfDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            case 'elifdef':
                $tag = new ElseIfDefDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            case 'elifndef':
                $tag = new ElseIfNDefDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            case 'error':
                $tag = new MessageErrorDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            case 'message':
                $tag = new MessageInfoDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            case 'warning':
                $tag = new MessageWarningDirective($argument, $line, $definitions);
                $tag->validate();
                return $tag;
            default:
                throw new PHPPreParserException('Unknown tag: ' . $tag, $line);
        }
    }
}
