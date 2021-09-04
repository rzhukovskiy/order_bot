<?php
/**
 * @var $step InstructionStepEntity
 */

use Orderbot\Entities\InstructionStepEntity;
?>
<p>
    <?=$step->description?>
</p>

<form action="" method="post">
    <input type="text" id="<?=$step->name?>" name="params[<?=$step->name?>]" />
    <input type="submit" value="go" />
</form>