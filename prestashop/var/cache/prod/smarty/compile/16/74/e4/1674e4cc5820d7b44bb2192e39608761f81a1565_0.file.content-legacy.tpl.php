<?php
/* Smarty version 3.1.34-dev-7, created on 2021-03-18 14:04:16
  from 'C:\xampp\htdocs\prestashop\admin\themes\default\template\content-legacy.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_60534fd0ebac88_04376518',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1674e4cc5820d7b44bb2192e39608761f81a1565' => 
    array (
      0 => 'C:\\xampp\\htdocs\\prestashop\\admin\\themes\\default\\template\\content-legacy.tpl',
      1 => 1613672194,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_60534fd0ebac88_04376518 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}
