<?php
/**
 * @var $step InstructionStepEntity
 */

use Orderbot\Entities\InstructionStepEntity;

$listButtons = json_decode($step->content, true);
?>
<p>
    <?= $step->description ?>
</p>
<?php
foreach ($listButtons as $value => $description) { ?>
    <form action="" method="post">
        <input type="hidden" id="<?= $step->name ?>" name="params[<?= $step->name ?>]" value="<?= $value ?>"/>
        <input type="submit" value="<?= htmlspecialchars($description) ?>"/>
    </form>
<?php }