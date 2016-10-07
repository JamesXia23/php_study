function changePage(obj){
	if (obj.id == 'navMenu') {
		document.getElementById('mainHome').style.display = 'none';
		document.getElementById('mainMenu').style.display = 'inline';
		document.getElementById('mainMusic').style.display = 'none';
	} else if (obj.id == 'navHome') {
		document.getElementById('mainHome').style.display = 'inline';
		document.getElementById('mainMenu').style.display = 'none';
		document.getElementById('mainMusic').style.display = 'none';
	} else if (obj.id == 'navMusic') {
		document.getElementById('mainHome').style.display = 'none';
		document.getElementById('mainMenu').style.display = 'none';
		document.getElementById('mainMusic').style.display = 'inline';
	}
	
}