function confirm_delete()
	{
		val = confirm('Are you sure you want to delete this report?');
		if(val)
		{
			return true;
		}
		else
		{
			return false;
		}
	}