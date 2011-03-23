<div class="menuContainerUpload">
	<div id="menuFileUpload">
		<button id="upload">Upload File</button><br /><span id="status" ></span>
		<div id="uploadContainer">  
			{*list of files*}  
			<ul id="files"></ul>
		</div>
		<p>Once an image is uploaded it becomes the property of www.stuffnearto.me and can be used however we see fit. However if a place owner has reason to want it removed they have the right to get it removed</p>
	</div>
	<div id="menuUrlLink" style="display:none;">
		<form id="menuLocationForm" action="/process/updateMenuLocation" method="post">
			<input type="text" name="menuLocation" /><br />
			<button>Submit</button>
		</form>
	</div>
	<div id="menuLinks">
		<a id="uploadMenuImage" class="awesome selected" href="#" class="active">Upload a menu</a>
		<a id="submitMenuLink" class="awesome" href="#">Submit a link to a menu</a>
	</div>	
</div>