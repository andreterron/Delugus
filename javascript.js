var typed = []
var home;

function blurBox(id, value)
{
	try {
		var search = document.getElementById(id);
		if (search.value == "")
		{
			search.style.color = "#aaaaaa";
			search.value = value;
			search.type = "text";
			typed[id] = false;
		}
		else
		{
			typed[id] = true;
		}
	} catch (err) {
		alert_error("ERRO! BLUR BOX! id = " + id);
	}
}

function focusBox(id, password)
{
	try {
		var search = document.getElementById(id);
		search.style.color = "#000000";
		if (!typed[id])
		{
			if (password)
				search.type = "password";
			search.value = "";
		}
		else
		{
			search.select();
		}
	} catch (err) {
		alert_error("ERRO! FOCUS BOX! id = " + id);
	}
}

function init()
{
	try {
		// descobre qual app ele esta no momento
		if (app == null) {
			switch (document.URL) {
				case "http://www.delugus.com/tasks":
					app = "tasks"; break;
				default:
					app = "home"; break;
			}
		}
		// chama a funcao de inicializacao do app atual
		switch (app) {
			case "tasks": init_tasks(); break;
			default: break;
		}
		// chama a funcao de resize
		resize();
	} catch (err) {
		alert_error("ERRO EM init()! " + err.description);
	}
}

function resize()
{
	try {
		switch (app) {
			case "tasks": init_tasks(); break;
			default: break;
		}
	} catch (err) {
		alert_error("ERRO EM resize()! " + err.description);
	}
}

function alert_error(msg)
{
	alert(msg);
}
