<div class='comment-form'>
<form method=post id='hide-form'>
<fieldset>
<legend><a href='#' onclick="document.getElementById('hide-form').submit();">Lämna en kommentar</a></legend>
<input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>">
<input type="hidden" name="pagekey" value="<?=$pagekey?>">
<input type="hidden" name="form" value="show-form">
</fieldset>
</form>
</div>