<?php
/* Smarty version 3.1.34-dev-7, created on 2021-03-18 14:04:17
  from 'C:\xampp\htdocs\prestashop\admin\themes\default\template\controllers\cart_rules\product_rule_itemlist.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_60534fd1687be5_36106677',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd9d00c6791c225dbe34874de92b59a9dd883362e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\prestashop\\admin\\themes\\default\\template\\controllers\\cart_rules\\product_rule_itemlist.tpl',
      1 => 1613672194,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_60534fd1687be5_36106677 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="col-lg-12 bootstrap">
	<div class="col-lg-6">
		<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Unselected','d'=>'Admin.Global'),$_smarty_tpl ) );?>

		<select multiple size="10" id="product_rule_select_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_group_id']->value);?>
_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_id']->value);?>
_1">
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product_rule_itemlist']->value['unselected'], 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
				<option value="<?php echo intval($_smarty_tpl->tpl_vars['item']->value['id']);?>
" title="<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
">&nbsp;<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</option>
			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</select>
		<div class="clearfix">&nbsp;</div>
		<a id="product_rule_select_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_group_id']->value);?>
_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_id']->value);?>
_add" class="btn btn-default btn-block" >
			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add','d'=>'Admin.Actions'),$_smarty_tpl ) );?>

			<i class="icon-arrow-right"></i>
		</a>
	</div>
	<div class="col-lg-6">
		<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Selected','d'=>'Admin.Global'),$_smarty_tpl ) );?>

		<select multiple size="10" name="product_rule_select_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_group_id']->value);?>
_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_id']->value);?>
[]" id="product_rule_select_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_group_id']->value);?>
_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_id']->value);?>
_2" class="product_rule_toselect" >
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product_rule_itemlist']->value['selected'], 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
				<option value="<?php echo intval($_smarty_tpl->tpl_vars['item']->value['id']);?>
" title="<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
">&nbsp;<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</option>
			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</select>
		<div class="clearfix">&nbsp;</div>
		<a id="product_rule_select_<?php echo $_smarty_tpl->tpl_vars['product_rule_group_id']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['product_rule_id']->value;?>
_remove" class="btn btn-default btn-block" >
			<i class="icon-arrow-left"></i>
			<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Remove','d'=>'Admin.Actions'),$_smarty_tpl ) );?>

		</a>
	</div>
</div>

<?php echo '<script'; ?>
 type="text/javascript">
	$('#product_rule_select_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_group_id']->value);?>
_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_id']->value);?>
_remove').click(function() { removeCartRuleOption(this); updateProductRuleShortDescription(this); });
	$('#product_rule_select_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_group_id']->value);?>
_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_id']->value);?>
_add').click(function() { addCartRuleOption(this); updateProductRuleShortDescription(this); });
	$(document).ready(function() { updateProductRuleShortDescription($('#product_rule_select_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_group_id']->value);?>
_<?php echo intval($_smarty_tpl->tpl_vars['product_rule_id']->value);?>
_add')); });
<?php echo '</script'; ?>
>
<?php }
}
