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
 * Class PHPPreStack
 *
 * @author     Maciej Trynkowski <maciej.trynkowski@miltar.pl>
 * @author     Wojciech Trynkowski <wojciech.trynkowski@miltar.pl>
 * @license    GNU Lesser General Public License, version 3
 * @version    $Id$
 * @package    phing.tasks.ext
 * @subpackage phppre
 * @link       https://github.com/PHPPre/Phing-PHPPre
 */
class PHPPreStack
{
    private $stack;

    /**
     * Stack constructor.
     */
    public function __construct()
    {
        $this->stack = [];
    }

    /**
     * Stack destructor
     */
    public function __destruct()
    {
        if (isset($this->stack)) {
            unset($this->stack);
        }
    }

    /**
     * Returns top element's index in stack.
     *
     * @return integer top element index
     */
    protected function getTopIndex()
    {
        return count($this->stack) - 1;
    }

    /**
     * Gets element from top of the stack.
     *
     * @return mixed top element
     */
    public function top()
    {
        if ($this->getTopIndex() == -1) {
            return null;
        }
        return $this->stack[$this->getTopIndex()];
    }

    /**
     * Gets element and removes it from top of the stack.
     *
     * @return mixed top element
     * @throws Exception when stack is empty
     */
    public function pop()
    {
        if ($this->getTopIndex() == -1) {
            throw new Exception('Stack is empty!');
        }
        return array_pop($this->stack);
    }

    /**
     * Adds element to top of the stack.
     *
     * @param $element element to add
     */
    public function push(&$element)
    {
        array_push($this->stack, $element);
    }
}
