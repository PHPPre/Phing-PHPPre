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

require_once 'phing/tasks/ext/phppre/PHPPreDirectiveFactory.php';
require_once 'phing/tasks/ext/phppre/lineactions/PHPPreDeleteLinesAction.php';
require_once 'phing/tasks/ext/phppre/exceptions/PHPPreParserException.php';

/**
 * Class ElseDirective
 *
 * @author     Maciej Trynkowski <maciej.trynkowski@miltar.pl>
 * @author     Wojciech Trynkowski <wojciech.trynkowski@miltar.pl>
 * @license    GNU Lesser General Public License, version 3
 * @version    $Id$
 * @package    phing.tasks.ext
 * @subpackage phppre
 * @link       https://github.com/PHPPre/Phing-PHPPre
 */
class ElseDirective extends AbstractPHPPreConditionalDirective
{

    /**
     * @param PHPPreStack $stack
     * @param PHPPreActionSet $actionSet
     * @throws PHPPreParserException
     */
    public function handleInternal(PHPPreStack &$stack, PHPPreActionSet &$actionSet)
    {
        if ($stack->top() instanceof ElseDirective) {
            throw new PHPPreParserException('This is second else tag in a row', $this->getFileLine());
        }

        if ($stack->top() instanceof AbstractPHPPreConditionalDirective) {
            $conditionalTag = $stack->pop();
            $stack->push($this);
            $this->condition = false;

            if (!$conditionalTag->getCondition()) {
                $this->condition = true;
                $action = new PHPPreDeleteLinesAction();
                $action->setStartLine($conditionalTag->getFileLine());
                $action->setEndLine($this->getFileLine());
                $actionSet->addAction($action);
            }
        } else {
            throw new PHPPreParserException('No opening tag found for else', $this->getFileLine());
        }
    }
}
