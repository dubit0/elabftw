<?php declare(strict_types=1);
/**
 * @author Nicolas CARPi <nico-git@deltablot.email>
 * @author Marcel Bolten <github@marcelbolten.de>
 * @copyright 2012 Nicolas CARPi
 * @see https://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */

namespace Elabftw\Services\AdvancedSearchQuery\Collectors;

class WhereCollector
{
    public function __construct(private string $where, private array $bindValues)
    {
    }

    public function getWhere(): string
    {
        return $this->where;
    }

    public function getBindValues(): array
    {
        return $this->bindValues;
    }
}
