<h1><?=$header?></h1>
<form method='post' action='../post-form'>
<fieldset>
<input type='hidden' name='id' value='<?=$id?>'/>
<input type='hidden' name='publisheddate' value='<?=$published?>'/>
<p>
<label>Title</label>
<br/>
<input type='text' name='title' required='required' value='<?=$title?>'/>

</p>
<p>
<label>URL</label>
<br/>
<input type='text' name='url' value='<?=$url?>'/>

</p>
<p>
<label>Slug</label>
<br/>
<input type='text' name='slug' required='required' value='<?=$slug?>'/>
</p>
<p>
<label>Content</label><br/>
<textarea name='data' required='required'><?=$data?></textarea>
</p>
<p>
<label>Name</label>
<br/>
<input type='text' name='acronym' required='required' value='<?=$acronym?>'/>
</p>
<label>Filter</label>
<br/>
<input type='text' name='filter' value='<?=$filter?>'/>
<p>Available filters: link, markdown, bbcode, nl2br. Enter filters as string, separated by commas.</p>
</p>
<p>
<label>Type</label>
<br/>
<select name='type' required='required'>
<option value='blog' <?php $selected = ($type == 'blog') ? 'selected' : null; echo $selected;?>>blog</option>
<option value='page' <?php $selected = ($type == 'page') ? 'selected' : null; echo $selected;?>>page</option>

</select>

</p>
<p>
<?php $publcheck = ($published == null) ? null : 'checked';?>
<input type='checkbox' name='published' value='published' <?=$publcheck?>/>
<label>Publish</label>
</p>
<p>

<input type='submit' name='submit-edit' value='Save' />

</p>

</fieldset>
</form>


