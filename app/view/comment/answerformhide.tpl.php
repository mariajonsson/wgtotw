<div class='answer-form'>
<form method=post name='hide-form' id="hide-form-<?=$formid?>">
<fieldset>
<legend><a href='#' onclick="document.getElementById('hide-form-<?=$formid?>').submit();"><i class="fa fa-comments fa-lg"></i>
 lämna ett svar</a></legend>
<input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>">
<input type="hidden" name="pagekey" value="<?=$pagekey?>">
<input type="hidden" name="formid" value="<?=$formid?>">
<input type="hidden" name="form" value="show-form">

</fieldset>
</form>
</div>