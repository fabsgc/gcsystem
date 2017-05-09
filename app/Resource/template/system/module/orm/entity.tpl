{$php}

namespace Orm\Entity;

{gc:if condition="$collection"}use System\Collection\Collection;{/gc:if}

use System\Orm\Entity\Entity;

/**
 * Class {$class}
 * @Table(name="{$table}")
 * @Form(name="{$form}")
{gc:foreach var="$fields" as="$field"} * @property {$field['type-php']} {$field['name']}

{/gc:foreach}* @package Orm\Entity
 */

class {$class} extends Entity {
{gc:foreach var="$fields" as="$field"}

    /**
{gc:if condition="$field['foreign']['enabled'] == false"} * @var {$field['type-php']}

     * @Column(type="{$field['type-orm']}"{gc:if condition="$field['unique'] && !$field['primary']"}, unique="true"{/gc:if}{gc:if condition="$field['primary']"}, primary="true"{/gc:if}{gc:if condition="$field['size'] != 0"}, size="{$field['size']}"{/gc:if}{gc:if condition="!$field['beNull'] && !$field['primary']"}, null="false"{/gc:if}{gc:if condition="$field['defaultValue'] != ''"}, default="{$field['defaultValue']}"{/gc:if}{gc:if condition="$field['precision'] != ''"}, precision="{$field['precision']}"{/gc:if}{gc:if condition="$field['enum'] != ''"}, enum="{$field['enum']}"{/gc:if})
     */
{gc:else/} * @var {$field['type-php']}

     * @{$field['foreign']['type']}(to="{$field['foreign']['to']}")
     */
{/gc:if}protected ${$field['name']};
{/gc:foreach}

}