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

require_once 'phing/tasks/ext/phppre/AbstractPHPPreBinaryOperator.php';
require_once 'phing/tasks/ext/phppre/PHPPreValueOperator.php';

require_once 'phing/tasks/ext/phppre/operators/EqualOperator.php';
require_once 'phing/tasks/ext/phppre/operators/GreaterThanEqualOperator.php';
require_once 'phing/tasks/ext/phppre/operators/GreaterThanOperator.php';
require_once 'phing/tasks/ext/phppre/operators/LessThanEqualOperator.php';
require_once 'phing/tasks/ext/phppre/operators/LessThanOperator.php';
require_once 'phing/tasks/ext/phppre/operators/NotEqualOperator.php';

/**
 * Class PHPPreOperatorFactory
 *
 * @author     Maciej Trynkowski <maciej.trynkowski@miltar.pl>
 * @author     Wojciech Trynkowski <wojciech.trynkowski@miltar.pl>
 * @license    GNU Lesser General Public License, version 3
 * @version    $Id$
 * @package    phing.tasks.ext
 * @subpackage phppre
 * @link       https://github.com/PHPPre/Phing-PHPPre
 */
class PHPPreOperatorFactory
{

    private static $instance;

    /**
     * PHPPreOperatorFactory constructor.
     */
    private function __construct()
    {
        // EMPTY
    }

    /**
     * @return PHPPreOperatorFactory
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new PHPPreOperatorFactory();
        }
        return self::$instance;
    }

    /**
     * @param $value
     * @return PHPPreValueOperator
     */
    public static function createValueOperator(&$value)
    {
        return new PHPPreValueOperator($value);
    }

    /**
     * @param $operator
     * @param $left
     * @param $right
     * @return EqualOperator|GreaterThanEqualOperator|GreaterThanOperator|LessThanEqualOperator|LessThanOperator|NotEqualOperator
     * @throws Exception
     */
    public static function createBinaryOperator($operator, &$left, &$right)
    {
        switch ($operator) {
            case '<':
                return new LessThanOperator($left, $right);
            case '<=':
                return new LessThanEqualOperator($left, $right);
            case '>':
                return new GreaterThanOperator($left, $right);
            case '>=':
                return new GreaterThanEqualOperator($left, $right);
            case '!=':
            case '<>':
                return new NotEqualOperator($left, $right);
            case '==':
                return new EqualOperator($left, $right);
            default:
                throw new Exception("Unknown operator expression: " . $operator);
        }
    }
}
