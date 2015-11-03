<div class='answer-form'>
<form method=get name='hide-form' id="hide-form-<?=$formid?>">
<fieldset>
<legend><a href='<?=$this->url->create($redirect)?>?form=show-form&formid=<?=$formid?>'>Lämna ett svar</a></legend>
<input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>">
<input type="hidden" name="pagekey" value="<?=$pagekey?>">
</fieldset>
</form>
</div>