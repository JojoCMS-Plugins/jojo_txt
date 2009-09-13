<div id="error" class="error"{if !$error} style="display: none;"{/if}>{$error}</div>
<div id="message" class="message"{if !$message} style="display: none;"{/if}>{$message}</div>

<div>{$content}</div>

<h3>Send SMS</h3>
<form method="post" action="txt/">
<div class="txt-form">
<label for="from">From:</label>
<input type="text" name="from" id="from" value="{$from}" /><br />

<label for="phone">Phone:</label>
<input type="text" name="phone" id="phone" value="{$mobilenumber}" readonly="readonly" /> This can't be changed<br />

<label for="message">Message:</label>
<div class="form-field"><textarea name="msg" id="msg" rows="5" cols="40" onkeydown="countDown('msg', 'counter', 160);" onkeyup="countDown('msg','counter', 160); hideregion('message'); hideregion('error');">{$msg}</textarea><br />
Remaining characters: <input readonly="readonly" style="width: 25px;" name="counter" id="counter" size="3" maxlength="3" value="" type="text" /></div><br />

<label for="CAPTCHA">Spam prevention:</label>
<div class="form-field">
<input type="text" size="8" name="CAPTCHA" id="CAPTCHA" value="" /><br />
Please enter the 3 letter code below. This helps us prevent spam.<br />
<img src="external/php-captcha/visual-captcha.php" width="200" height="60" alt="Visual CAPTCHA" /><br />
<em>Code is not case-sensitive</em>
</div><br />

<label for="submit">Send Message:</label>
<input type="submit" name="submit" id="submit" value="send" /><br />

</div>
</form>