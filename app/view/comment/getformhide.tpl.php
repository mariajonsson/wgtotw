<div class='comment-form'>
<form method=get name='hide-form' id="<?=$formid?>">
<fieldset>
<legend><a href='<?=$this->url->create($redirect)?>?form=show-form&formid=<?=$formid?>#<?=$formid?>' class='commenting'><i class="fa fa-comment-o fa-flip-horizontal"></i>
 kommentera...</a></legend>
<input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>">
<input type="hidden" name="pagekey" value="<?=$pagekey?>">

</fieldset>
</form>
</div>