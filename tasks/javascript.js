var tasks = new Array();
var historic = new Array();
var sidebar_tab_selected = 0;
var selected_task = -1;
var sidebar_rate = 0.6;
var app = "tasks";
var tab_names = ['list', 'view'];

var topbar_height = 32 + 1;

var xmlhttp;

function init_tasks()
{
	
}

function resize_tasks()
{
	try {
		
	} catch (err) {
		alert_error("ERRO! RESIZE! " + err.description);
	}
}

function get_task(id, depth)
{
	var offid = globalid.indexOf(id);
	if (depth == null) depth = 0;
	if (offid != -1) {
		if (depth == 0)
			return usertasks[offid];
	} else {
		
	}
}

function build_task(offid, id, name, objStatus, complete, kids, parent) //, effort, stress, day, time, duration, complete, comm, kids)
{
	/* Obj_Status:
		1 - complete
		2 - offline
		3 - updating
	*/
	this.offid = offid;
	this.id = id;
	this.name = name;
	this.complete = complete;
	this.kids = kids;
	this.parent = parent;
	this.xmlhttp = null;
	if (objStatus == null) objStatus = 1;
	this.objStatus = objStatus;
	
	this.answerAJAX = function () {
		if (this.xmlhttp.readyState==4 && this.xmlhttp.status==200)
		{
			var taskname = document.getElementById("taskname");
			var fid = globalid.indexOf(folderid);
			//alert(usertasks[id].xmlhttp);
			//alert(usertasks[id].xmlhttp.responseText);
			/* XML PARSE */
			if (window.DOMParser)
			  {
			  parser=new DOMParser();
			  xml=parser.parseFromString(this.xmlhttp.responseText,"text/xml");
			  }
			else // Internet Explorer
			  {
			  xml=new ActiveXObject("Microsoft.XMLDOM");
			  xml.async=false;
			  xml.loadXML(this.xmlhttp.responseText); 
			  } 
			/* END XML PARSE */
			/* NOT WORKING FOR SOME REASON 
			alert(usertasks[id].xmlhttp.responseXML);
			var xml = usertasks[id].xmlhttp.responseXML;*/
			var action = xml.getElementsByTagName('action')[0].textContent;
			var res = Number(xml.getElementsByTagName('result')[0].textContent);
			if (res == 1) {
				document.getElementById('msg_container').innerHTML = "";
				/* sucesso */
				if (action == "create") {
					this.id = Number(xml.getElementsByTagName('fileid')[0].textContent);
					globalid[this.offid] = this.id;
					this.objStatus = 1;
					var tlist = document.getElementById("tasklist");
					tlist.innerHTML = draw_task(this, 0) + tlist.innerHTML;
					usertasks[fid].kids.push(this.id);
				} else if (action == "complete") {
					this.complete = Number(xml.getElementsByTagName('value')[0].textContent);
					document.getElementById('task'+this.offid).innerHTML = draw_task(this, 1);
				}
				//alert("parent [" + fid + "]" + usertasks[fid] + "\nglobal_id: " + this.id + '/' + globalid[this.offid] + "\noffid: " + this.offid);
			} else if (res == 2){
				var msg = xml.getElementsByTagName('msg').item(0);
				var txt = "<div";
				if (msg.attributes.getNamedItem("class") != null) {
					txt += " class='" + msg.attributes.getNamedItem("class").textContent + "'";
				}
				txt += ">" + msg.textContent + "</div>";
				document.getElementById("msg_container").innerHTML = txt;
				/* fracasso */
			}
			taskname.disabled = false;
			taskname.value = "";
		}
	};
	
	/*this.effort = effort;
	this.stress = stress;
	this.day = day;
	this.time = time;
	this.duration = duration;
	this.complete = complete;
	this.comm = comm;
	if (kids == null) {
		this.kids = new Array();
		//alert("ITEM " + offid + " HAS NO KIDS!");
	} else {
		this.kids = kids;
		//alert("ITEM " + offid + " HAS THESE KIDS: " + kids);
	}*/
}

function hide_sidebar()
{
	var i = sidebar_tab_selected;
	/*for (i = 0; i < tabs.lenght; i++)
	*/
		document.getElementById('sidebar_' + tab_names[i]).className = 'sidebar_contents';
		document.getElementById('sidebar_' + tab_names[i] + '_tab').className = "tab";

}

function draw_task(task, depth)
{
	var txt = "";
	var i;
	var kid;
	
	if (task == -1) {
		return "";
	}
	
	if (depth == null) {
		depth = 0;
	}

	if (depth <= 1) {
		if (depth == 0) {
			txt += "<div id='task" + task.offid + "' style='position: relative'>";
		}
		//ondblclick='window.location.assign(\"tasks/index.php?folder=" + task.id + "&complete=true\");'
		txt += "<div class='task-holder'><div class='task-h-top' ondblclick='open_folder(" + task.id + ");'><div class='task-checkbox'";
		c = (task.complete == 1 ? 0 : 1);
		txt += " onclick='complete_task(" + task.offid + ", " + c + ");'";
		txt += " ";
		txt += ">";
		if (task.complete) {
			txt += "<div style='width: 100%; height: 100%; padding: 2px;'><div style='width: 100%; height: 100%; background-color: #000000; border-radius: 2px;'></div></div>"
		}
		txt += "</div>";
		txt += task.name + "</div>";
		if (task.kids.length > 0) {
			txt += "<div class='task-h-rest'>";
		}
		
		//kid = globalid.indexOf(task.kids[i]);
	}
	if (depth <= 2 && task.kids.length > 0) {
		for (i = task.kids.length - 1; i >= 0; i--) {
			kid = globalid.indexOf(task.kids[i]);
			if (kid == -1) continue;
			txt += draw_task(usertasks[kid]);
		}
	}
	if (depth <= 1) {
		if (task.kids.length > 0) {
			txt += "</div>";
		}
		txt += "</div>";
	}
	
	if (depth == 0) {
		txt += "</div>";
	}
	
	return txt;
}

function update_task(task)
{
	var taskdiv = document.getElementById('task' + task.offid);
	var txt;
	
	txt = "<div class='task-h-top'>";
	txt += task.name + "</div>";
	
	taskdiv.innerHTML = txt;
}

function open_folder(id)
{
	var lid = globalid.indexOf(id); //local id / offid
	if (lid == -1)
		return; // ERROR: GLOBAL ID NOT REGISTERED
	document.getElementById("folder-name").innerHTML = usertasks[lid].name;
	document.getElementById("tasklist").innerHTML = draw_task(usertasks[lid], 2);
	stack = [];
	file = usertasks[lid]
	while (file.parent != null) {
		stack.push(file.parent);
		file = usertasks[file.parent];
	}
	path = "";
	while (stack.length) {
		fid = stack.pop();
		file = usertasks[fid];
		if (stack.length > 0) {
			path += "<a onclick='open_folder(" + file.id + ");' style='cursor: pointer;'>" + file.name + "</a> / ";
		} else {
			path += "<a onclick='open_folder(" + file.id + ");' style='cursor: pointer;'>" + file.name + "</a>";
		}
	}
	document.getElementById("folder-path").innerHTML = path;
	folderid = id;
}

function switch_sidetab(tab, info)
{
	hide_sidebar();
	sidebar_tab_selected = tab;
	document.getElementById('sidebar_' + tab_names[tab]).className = "sidebar_contents_active";
	document.getElementById('sidebar_' + tab_names[tab] + '_tab').className = "activetab";
	if (tab == 1)
	{
		if (info == null)
			info = selected_task;
		else
			selected_task = info;
		
		if (info == -1) {
			document.getElementById('sidebar_view_name').value = "New Event";
			//document.getElementById('taskview_effort').value = "";
		} else {
			document.getElementById('sidebar_view_name').value = tasks[info].name;
			//document.getElementById('taskview_effort').value = tasks[info].effort;
		}
	}
}

function complete_task(id, c)
{
	if (c == null)
		c = 1;
	try {
		if (id == null) {
			return;
		}
		var t = usertasks[id];
		if (usertasks[id].xmlhttp == null) {
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				usertasks[id].xmlhttp = new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				usertasks[id].xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else if (usertasks[id].xmlhttp.readyState!=4 && usertasks[id].xmlhttp.readyState!=0) {
			return;
		}
		usertasks[id].xmlhttp.fileid = id;
		usertasks[id].xmlhttp.onreadystatechange = function () {
			if (usertasks[this.fileid])
				usertasks[this.fileid].answerAJAX();
		}
		usertasks[id].xmlhttp.open("GET","tasks/create_task.php?action=complete&fileid=" + usertasks[id].id + "&value=" + c ,true);
		usertasks[id].xmlhttp.send();
	} catch (err) {
		alert("ERROR AT COMPLETE_TASK " + err.description);
	}
}

function create_task()
{
		var taskname = document.getElementById("taskname");
		var fid = globalid.indexOf(folderid); //local id / offid
		if (taskname.value == "" || fid == -1) {
			return;
		}
		var id = usertasks.length;
		globalid[id] = -1;
		usertasks[id] = new build_task(id, -1, taskname.value, 2, 0, [], fid);
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			usertasks[id].xmlhttp = new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			usertasks[id].xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		usertasks[id].xmlhttp.fileid = id;
		usertasks[id].xmlhttp.onreadystatechange = function () {
			if (usertasks[this.fileid])
				usertasks[this.fileid].answerAJAX();
		}
		taskname.disabled = true;
		usertasks[id].xmlhttp.open("GET","tasks/create_task.php?action=create&name=" + taskname.value + "&folder=" + folderid ,true);
		usertasks[id].xmlhttp.send();
}



