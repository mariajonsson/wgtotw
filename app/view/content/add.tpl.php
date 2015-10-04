<h1><?=$title?></h1>
<form method='post' action='post-form'>
<fieldset>

<p>
<label>Title</label>
<br/>
<input type='text' name='title' required='required'/>

</p>
<p>
<label>URL</label>
<br/>
<input type='text' name='url' required='required'/>

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
<input type='text' name='acronym' required='required'/>
</p>
<label>Filter</label>
<br/>
<input type='text' name='filter'/>
<p>Available filters: link, markdown, bbcode, nl2br. Enter filters as string, separated by commas.</p>
</p>
<p>
<label>Type</label>
<br/>
<select name='type' required='required'>
<option value='blog' selected>blog</option>
<option value='page'>page</option>

</select>

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


