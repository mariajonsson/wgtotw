<h1><?=$title?></h1>
<form method='post' action='post-form'>
<fieldset>
<?=var_dump($user)?>
<p>
<label>Title</label>
<br/>
<input type='text' name='title' required='required'/>

</p>

<p>
<label>Slug</label>
<br/>
<input type='text' name='slug' required='required'/>
</p>
<p>
<label>Content</label><br/>
<textarea name='data' required='required'></textarea>
</p>
<p>
<label>Name</label>
<br/>
<input type='text' name='acronym' required='required' value='<?=$user?>'/>
</p>

<p>
<input type='checkbox' name='published' value='published' />
<label>Publish</label>
</p>
<p>

<input type='submit' name='submit-add' value='Save' />

</p>

</fieldset>
</form>


