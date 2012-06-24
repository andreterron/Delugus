<?php ?>
<!DOCTYPE>

<form action="filter.php" method="GET">
You are looking for:<br />
<input type="checkbox" name="male" value="true" />Men<br />
<input type="checkbox" name="female" value="true" />Women<br /><br />

Search type:<br />

<select name="type">
	<option value="simple" selected>Simple</option>
	<option value="complex">Complex</option>
</select>
<br /><br />

How much these features should matter in your match?<br />


Sports:<br />
<select name="sports">
	<option value=0>It doesn't</option>
	<option value=1>A little</option>
	<option value=2 selected>It matters</option>
	<option value=3>It's necessary</option>
</select>
<br /><br />

Sports teams:<br />
<select name="sportsteams">
	<option value=0>It doesn't</option>
	<option value=1>A little</option>
	<option value=2 selected>It matters</option>
	<option value=3>It's necessary</option>
</select>
<br /><br />

Music:<br />
<select name="music">
	<option value=0>It doesn't</option>
	<option value=1>A little</option>
	<option value=2 selected>It matters</option>
	<option value=3>It's necessary</option>
</select>
<br /><br />

Movies:<br />
<select name="movies">
	<option value=0>It doesn't</option>
	<option value=1>A little</option>
	<option value=2 selected>It matters</option>
	<option value=3>It's necessary</option>
</select>
<br /><br />

TV Shows:<br />
<select name="television">
	<option value=0>It doesn't</option>
	<option value=1>A little</option>
	<option value=2 selected>It matters</option>
	<option value=3>It's necessary</option>
</select>
<br /><br />

Games and toys:<br />
<select name="games">
	<option value=0>It doesn't</option>
	<option value=1>A little</option>
	<option value=2 selected>It matters</option>
	<option value=3>It's necessary</option>
</select>
<br /><br />

Books:<br />
<select name="books">
	<option value=0>It doesn't</option>
	<option value=1>A little</option>
	<option value=2 selected>It matters</option>
	<option value=3>It's necessary</option>
</select>
<br /><br />

Religion:<br />
<select name="religion">
	<option value=0>It doesn't</option>
	<option value=1>A little</option>
	<option value=2 selected>It matters</option>
	<option value=3>It's necessary</option>
</select>
<br /><br />

<input type="submit" value="Submit" />
</form>
