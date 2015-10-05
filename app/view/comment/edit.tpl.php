<output><?=$output?></output>
<div class='comment-form'>
<form method=post>
<input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>">
<fieldset>
<legend>Redigera kommentar (id #<?=$id?>)</legend>
<p class='comment'>Fält märkta med * är obligatoriska</p>
<p><label>Kommentar:*<br/><textarea name='content' required><?=$content?></textarea></label></p>
<p><label>Namn:*<br/><input type='text' name='name' value='<?=$name?>' required/></label></p>
<p><label>Hemsida:<br/><input type='text' name='web' value='<?=$web?>'/></label></p>
<p><label>Epost:*<br/><input type='text' name='mail' value='<?=$mail?>' required/></label></p>
<p class=buttons>
<input type='submit' name='doSave' value='Spara' onClick="this.form.action = '<?=$this->url->create('comment/save/'.$pagekey.'/'. $id)?>'"/>
<input type='reset' value='Återställ'/>
<input type='submit' name='doDelete' value='Radera kommentar' onClick="this.form.action = '<?=$this->url->create('comment/delete/'.$pagekey.'/' .$id)?>'"/>
</p>
</fieldset>
</form>
</div>
