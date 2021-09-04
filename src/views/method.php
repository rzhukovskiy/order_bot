<?php
/**
 * @var $this Result
 */

use Orderbot\Result;

$result = $this->getResult();
$step = $this->getStep();

?>
<?php foreach ($result as $item) { ?>
    <form method="post">
        <input type="hidden" id="id" name="params[<?=$step->name?>]" value="<?=$item->getAction()?>"/>
        <input type="submit" value="<?=htmlspecialchars($item->getDescription())?>" />
    </form>
<?php } ?>