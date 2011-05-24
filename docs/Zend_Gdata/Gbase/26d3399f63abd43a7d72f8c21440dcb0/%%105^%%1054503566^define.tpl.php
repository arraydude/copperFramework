<?php /* Smarty version 2.6.0, created on 2011-05-24 16:13:48
         compiled from define.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'define.tpl', 3, false),array('modifier', 'replace', 'define.tpl', 7, false),)), $this); ?>
<?php if (isset($this->_sections['def'])) unset($this->_sections['def']);
$this->_sections['def']['name'] = 'def';
$this->_sections['def']['loop'] = is_array($_loop=$this->_tpl_vars['defines']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['def']['show'] = true;
$this->_sections['def']['max'] = $this->_sections['def']['loop'];
$this->_sections['def']['step'] = 1;
$this->_sections['def']['start'] = $this->_sections['def']['step'] > 0 ? 0 : $this->_sections['def']['loop']-1;
if ($this->_sections['def']['show']) {
    $this->_sections['def']['total'] = $this->_sections['def']['loop'];
    if ($this->_sections['def']['total'] == 0)
        $this->_sections['def']['show'] = false;
} else
    $this->_sections['def']['total'] = 0;
if ($this->_sections['def']['show']):

            for ($this->_sections['def']['index'] = $this->_sections['def']['start'], $this->_sections['def']['iteration'] = 1;
                 $this->_sections['def']['iteration'] <= $this->_sections['def']['total'];
                 $this->_sections['def']['index'] += $this->_sections['def']['step'], $this->_sections['def']['iteration']++):
$this->_sections['def']['rownum'] = $this->_sections['def']['iteration'];
$this->_sections['def']['index_prev'] = $this->_sections['def']['index'] - $this->_sections['def']['step'];
$this->_sections['def']['index_next'] = $this->_sections['def']['index'] + $this->_sections['def']['step'];
$this->_sections['def']['first']      = ($this->_sections['def']['iteration'] == 1);
$this->_sections['def']['last']       = ($this->_sections['def']['iteration'] == $this->_sections['def']['total']);
?>
<a name="<?php echo $this->_tpl_vars['defines'][$this->_sections['def']['index']]['define_link']; ?>
"><!-- --></a>
<div class="<?php echo smarty_function_cycle(array('values' => "evenrow,oddrow"), $this);?>
">
	
	<div>
		<span class="const-title">
			<span class="const-name"><?php echo $this->_tpl_vars['defines'][$this->_sections['def']['index']]['define_name']; ?>
</span> = <?php echo ((is_array($_tmp=$this->_tpl_vars['defines'][$this->_sections['def']['index']]['define_value'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br />") : smarty_modifier_replace($_tmp, "\n", "<br />")); ?>

			(line <span class="line-number"><?php if ($this->_tpl_vars['defines'][$this->_sections['def']['index']]['slink']):  echo $this->_tpl_vars['defines'][$this->_sections['def']['index']]['slink'];  else:  echo $this->_tpl_vars['defines'][$this->_sections['def']['index']]['line_number'];  endif; ?></span>)
		</span>
	</div>
	
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "docblock.tpl", 'smarty_include_vars' => array('sdesc' => $this->_tpl_vars['defines'][$this->_sections['def']['index']]['sdesc'],'desc' => $this->_tpl_vars['defines'][$this->_sections['def']['index']]['desc'],'tags' => $this->_tpl_vars['defines'][$this->_sections['def']['index']]['tags'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	
	<?php if ($this->_tpl_vars['globals'][$this->_sections['glob']['index']]['global_conflicts']['conflict_type']): ?>
		<hr class="separator" />
		<div><span class="warning">Conflicts with constants:</span><br /> 
			<?php if (isset($this->_sections['me'])) unset($this->_sections['me']);
$this->_sections['me']['name'] = 'me';
$this->_sections['me']['loop'] = is_array($_loop=$this->_tpl_vars['defines'][$this->_sections['def']['index']]['define_conflicts']['conflicts']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['me']['show'] = true;
$this->_sections['me']['max'] = $this->_sections['me']['loop'];
$this->_sections['me']['step'] = 1;
$this->_sections['me']['start'] = $this->_sections['me']['step'] > 0 ? 0 : $this->_sections['me']['loop']-1;
if ($this->_sections['me']['show']) {
    $this->_sections['me']['total'] = $this->_sections['me']['loop'];
    if ($this->_sections['me']['total'] == 0)
        $this->_sections['me']['show'] = false;
} else
    $this->_sections['me']['total'] = 0;
if ($this->_sections['me']['show']):

            for ($this->_sections['me']['index'] = $this->_sections['me']['start'], $this->_sections['me']['iteration'] = 1;
                 $this->_sections['me']['iteration'] <= $this->_sections['me']['total'];
                 $this->_sections['me']['index'] += $this->_sections['me']['step'], $this->_sections['me']['iteration']++):
$this->_sections['me']['rownum'] = $this->_sections['me']['iteration'];
$this->_sections['me']['index_prev'] = $this->_sections['me']['index'] - $this->_sections['me']['step'];
$this->_sections['me']['index_next'] = $this->_sections['me']['index'] + $this->_sections['me']['step'];
$this->_sections['me']['first']      = ($this->_sections['me']['iteration'] == 1);
$this->_sections['me']['last']       = ($this->_sections['me']['iteration'] == $this->_sections['me']['total']);
?>
				<?php echo $this->_tpl_vars['defines'][$this->_sections['def']['index']]['define_conflicts']['conflicts'][$this->_sections['me']['index']]; ?>
<br />
			<?php endfor; endif; ?>
		</div>
	<?php endif; ?>
	
</div>
<?php endfor; endif; ?>