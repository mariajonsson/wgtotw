<div class='comment-form'>
<form method=get name='hide-form' id="<?=$formid?>">
<fieldset>
<legend><a href='<?=$this->url->create($redirect)?>?form=show-form&formid=<?=$formid?>#<?=$formid?>'>Lämna en kommentar</a></legend>
<input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>">
<input type="hidden" name="pagekey" value="<?=$pagekey?>">

</fieldset>
</form>
</div>