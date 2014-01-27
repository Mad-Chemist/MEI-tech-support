<?php
defined('_JEXEC') or die();
?>

<h4 class="typeTitle"><?php echo JText::_('COM_MEI_TYPE_TITLE_' . strtoupper($this->type)) ?></h4>

<div class="file_table span11">

    <table class="table table-bordered table-striped table-hover">

        <tr class="file-table-head">

            <th><?php echo JText::_('COM_MEI_TABLE_HEADING_NAME'); ?></th>

            <th><?php echo JText::_('COM_MEI_TABLE_HEADING_SUBSCRIBE'); ?></th>

            <th><?php echo JText::_('COM_MEI_TABLE_HEADING_LAST_UPDATED'); ?></th>

        </tr>

        <?php foreach ($this->products as $product) : ?>

        <?php
            $modified = new JDate($product->modified_on);
            $identifier = strtolower(str_replace(" ", "-", $product->title) . '-' . $this->type);
        ?>

        <tr class="<?php echo $identifier?>">

            <td><?php echo $product->title ?></td>

            <td>
                <div class="control-group">
                    <div class="controls">
                        <fieldset id="<?php echo $identifier?>-sub" class="radio radio btn-group" >
                            <input type="radio"
                                   id="<?php echo $identifier?>0"
                                   name="subscriptions[<?php echo $product->subid?>][<?php echo $this->type?>]"
                                   value="1" <?php echo (!$product->excluded) ? 'checked="checked"' : '' ?> />
                            <label for="<?php echo $identifier?>0" ><?php echo JText::_('COM_MEI_YES') ?></label>
                            <input type="radio"
                                   id="<?php echo $identifier?>1"
                                   name="subscriptions[<?php echo $product->subid?>][<?php echo $this->type?>]"
                                   value="0" <?php echo ($product->excluded) ? 'checked="checked"' : '' ?> />
                            <label for="<?php echo $identifier?>1" ><?php echo JText::_('COM_MEI_NO') ?></label>
                        </fieldset>
                    </div>
                </div>
            </td>

            <td><?php echo $modified->format('M d, Y h:i A'); ?></td>

        </tr>

        <?php endforeach; ?>

    </table>

</div>