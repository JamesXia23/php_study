function changePage(obj){
	if (obj.id == 'navMenu') {
		document.getElementById('mainHome').style.display = 'none';
		document.getElementById('mainMenu').style.display = 'inline';
	} else if (obj.id == 'navHome') {
		document.getElementById('mainHome').style.display = 'inline';
		document.getElementById('mainMenu').style.display = 'none';
	}
	
}