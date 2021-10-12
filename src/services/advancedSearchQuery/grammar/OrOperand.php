<?php declare(strict_types=1);
/**
 * @author Nicolas CARPi <nico-git@deltablot.email>
 * @author Marcel Bolten <github@marcelbolten.de>
 * @copyright 2012 Nicolas CARPi
 * @see https://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */

namespace Elabftw\Services\AdvancedSearchQuery\Grammar;

use Elabftw\Services\AdvancedSearchQuery\Interfaces\Visitable;
use Elabftw\Services\AdvancedSearchQuery\Interfaces\Visitor;
use Elabftw\Services\AdvancedSearchQuery\Visitors\VisitorParameters;

class OrOperand implements Visitable
{
    public function __construct(private AndExpression $operand, private ?self $tail = null)
    {
    }

    public function accept(Visitor $visitor, VisitorParameters $parameters): mixed
    {
        return $visitor->visitOrOperand($this, $parameters);
    }

    public function getOperand(): AndExpression
    {
        return $this->operand;
    }

    public function getTail(): ?self
    {
        return $this->tail;
    }
}
