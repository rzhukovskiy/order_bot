<?php
/**
 * @var $result CommandParamEntity
 */

use Orderbot\Entities\CommandParamEntity;
?>
<p>
    <?=$result->description?>
</p>
<form action="" method="post">
    <input type="text" id="<?=$result->name?>" name="<?=$result->name?>" />
    <input type="submit" value="go" />
</form>
