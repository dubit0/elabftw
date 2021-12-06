<?php declare(strict_types=1);
/**
 * @author Nicolas CARPi <nico-git@deltablot.email>
 * @author Marcel Bolten <github@marcelbolten.de>
 * @copyright 2021 Nicolas CARPi
 * @see https://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */

namespace Elabftw\Services;

use Elabftw\Models\TeamGroups;
use Elabftw\Models\Users;

class AdvancedSearchQueryTest extends \PHPUnit\Framework\TestCase
{
    public function testGetWhereClause(): void
    {
        $query = ' TEST TEST1 AND TEST2 OR TEST3 NOT TEST4 & TEST5';
        $query .= ' | TEST6 AND ! TEST7 (TEST8 or TEST9) "T E S T 1 0"';
        $query .= ' \'T E S T 1 1\' "chinese 汉语 漢語 中文" "japanese 日本語 ひらがな 平仮名 カタカナ 片仮名"';
        $query .= ' attachment:0 author:"Phpunit TestUser" body:"some text goes here"';
        $query .= ' category:"only meaningful with items but no error"';
        $query .= ' elabid:7bebdd3512dc6cbee0b1 locked:yes rating:0 rating:5 rating:unrated';
        $query .= ' status:"only meaningful with experiments but no error"';
        $query .= ' timestamped:true title:"very cool experiment" visibility:me';

        $advancedSearchQuery = new AdvancedSearchQuery($query, array(
            'column' => 'body',
            'visArr' => (new TeamGroups(new Users(1, 1)))->getVisibilityList(),
            'entityType' => 'experiments',
        ));
        $whereClause = $advancedSearchQuery->getWhereClause();
        $this->assertIsArray($whereClause);
        $this->assertStringStartsWith(' AND (((entity.body LIKE :', $whereClause['where']);
        $this->assertStringEndsWith(')))', $whereClause['where']);
    }

    public function testSyntaxError(): void
    {
        $query = 'AND AND AND';

        $advancedSearchQuery = new AdvancedSearchQuery($query, array(
            'column' => 'body',
            'visArr' => (new TeamGroups(new Users(1, 1)))->getVisibilityList(),
            'entityType' => 'experiments',
        ));
        $advancedSearchQuery->getWhereClause();
        $this->assertStringStartsWith('Column ', $advancedSearchQuery->getException());
    }

    public function testComplexityLimit(): void
    {
        $query = 'TEST TEST1';

        // Depth of abstract syntax tree is set to 1 with the last parameter
        $advancedSearchQuery = new AdvancedSearchQuery($query, array(
            'column' => 'body',
            'visArr' => (new TeamGroups(new Users(1, 1)))->getVisibilityList(),
            'entityType' => 'experiments',
        ), 1);
        $advancedSearchQuery->getWhereClause();
        $this->assertEquals('Query is too complex.', $advancedSearchQuery->getException());
    }
}
