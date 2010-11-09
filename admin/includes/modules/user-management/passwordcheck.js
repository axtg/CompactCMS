/* [i_a] now also returns the score as a result value, to be used by randomPassword() */
function passwordStrength(password)
{
	var score=0;
	if(password.length>5)
		score++;
	if((password.match(/[a-z]/))&&(password.match(/[A-Z]/)))
		score++;
	if(password.match(/\d+/))
		score++;
	if(password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))
		score++;
	if(password.length>12)
		score++;
	document.getElementById("passwordStrength").className="strength"+score; 
	return score;
}
	
/* [i_a] added loop to make sure password is ALWAYS strong enough (sometimes the random generated one isn't) */	
function randomPassword(length)
{
	var target=document.getElementById("userPass");
	
	chars="abcdefghijkmNPQRSTUVWXYZ123456789=*^?!@#$%";
	do
	{
		pass="";
		for(x=0;x<length;x++)
		{
			i=Math.floor(Math.random()*42);
			pass+=chars.charAt(i);
		}
		score=passwordStrength(pass);
	} while(score<4);
	return target.value=pass;
}

