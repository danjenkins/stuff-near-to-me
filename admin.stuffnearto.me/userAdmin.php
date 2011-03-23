<?
	/**
	* Admin.php
	*
	* This is the Admin Center page. Only administrators
	* are allowed to view this page. This page displays the
	* database table of users and banned users. Admins can
	* choose to delete specific users, delete inactive users,
	* ban users, update user levels, etc.
	*
	*/
	
	function displayUsers(){
		/**
	 	 * displayUsers - Displays the users database table in a nicely formatted html table.
	 	 */
	 	 
		global $database;
		global $stuff;
		
		$q = 'SELECT username,userlevel,email,timestamp FROM '.TBL_USERS.' ORDER BY userlevel DESC,username';
		
		$result = $database->query($q);
		
		/* Error occurred, return given name by default */
		$num_rows = mysql_numrows($result);
		if(!$result || ($num_rows < 0)){
			$table .= 'Error displaying info in displayUsers()';
			return $table;
		}
		if($num_rows == 0){
			$table .= 'Database table empty, table was '.TBL_USERS;
			return $table;
		}
   
		/* Display table contents */
		$table .= '<table class="viewTables">';
		$table .= '<thead><tr><th>Username</th><th>Level</th><th>Email</th><th>Last Active</th><th>Edit</th><th>Ban User</th><th>Delete User</th></tr></thead><tbody>';
		$i = 0;
		while($row = mysql_fetch_assoc($result)){
		$i++;
			$table .= '
				<tr class="'.($i%2 ? 'even' : 'odd').'">
					<td>'.$row['username'].'</td>
					<td>'.$row['userlevel'].'</td>
					<td>'.$row['email'].'</td>
					<td>'.($stuff->distanceOfTimeInWords($row['timestamp'])).'</td>
					<td><a href="/edit/user/'.$row['username'].'">Edit <img src="/images/icons/user_edit.png" /></a></td>
					<td><a href="/adminprocess.php?banuser='.$row['username'].'&subbanuser=1">Ban <img src="/images/icons/cross.png" /></a></td>
					<td><a href="/adminprocess.php?deluser='.$row['username'].'&subdeluser=1">Delete <img src="/images/icons/delete.png" /></a></td>
				</tr>';
		}
		$table .= '</tbody></table><br />';
		return $table;
	}

	function displayBannedUsers(){
		/**
	 	 * displayBannedUsers - Displays the banned users database table in a nicely formatted html table.
	 	 */
		global $database;
		$q = "SELECT username,timestamp FROM ".TBL_BANNED_USERS." ORDER BY username";
   		$result = $database->query($q);
   		
   		/* Error occurred, return given name by default */
		$num_rows = mysql_numrows($result);
		if(!$result || ($num_rows < 0)){
			$bannedTable .= "Error displaying info in displayBannedUsers()";
			return $bannedTable;
		}
		
		if($num_rows == 0){
			$bannedTable .= "Database table empty, table was ".TBL_BANNED_USERS;
			return $bannedTable;
		}
		/* Display table contents */
		$bannedTable .= '<table class="viewTables">';
		$bannedTable .= '<thead><tr><th>Username</th><th>Time Banned</th><th>Un-ban</th><th>Delete Banned User</th></tr></thead><tbody>';
		while($row = mysql_fetch_assoc($result)){
			$bannedTable .= '<tr><td>'.$row['username'].'</td><td>'.$row['timestamp'].'</td><td><a>Un-ban</a></td><td><a href="/adminprocess.php?delbanuser='.$row['username'].'&subdelbanned=1">Delete</a></td></tr>';
		}
		$bannedTable .= '</tbody></table><br />';
		return $bannedTable;
	}
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
		if($loginEditDelete){
			global $stuff;
			if($_GET['action']){
				if($GET['user']){
					$html .= '<div class="userActioned">User '.$GET['username'].' has been '.$stuff->splitCamelCase($_GET['action']).'</div>';
				}else{
					$html .= '<div class="userActioned">Action was '.$stuff->splitCamelCase($_GET['action']).'</div>';
				}
				
			}
			
			
			if($form->num_errors > 0){
				$html .= 'Error with request, please fix';
				foreach($form->errors as $k=>$v){
					$html .= $v;
				}
			}
					
			$html .= '<h3>Users Table Contents:</h3>';
			/*
			 * Show Users
			 */
			$html .= displayUsers();
			
			/**
			 * Update User Level
			 */
			
			$html .= '<h3>Update User Level</h3>';
			$html .= $form->error("upduser");
			$html .= '<form action="adminprocess.php" method="POST">';
			$html .= '<label>Username:</label><input type="text" name="upduser" maxlength="30" value="'.($form->value("upduser")).'" /><br />';
			$html .= '<label>Level:</label><select name="updlevel">';
			$html .= '<option value="1">Basic User</option>';
			$html .= '<option value="5">Place Admin</option>';
			$html .= '<option value="9">Super Admin</option>';
			$html .= '</select><br />';
			$html .= '<input type="hidden" name="subupdlevel" value="1" />';
			$html .= '<label>&nbsp;</label><button>Update Level</button>';
			$html .= '</form>';
	
			/**
			 * Delete Inactive Users
			 */
	
			$html .= '<h3>Delete Inactive Users</h3>';
			$html .= '<p>This will delete all users (not administrators), who have not logged in to the site<br>
	within a certain time period. You specify the days spent inactive.</p>';
			$html .= '<form action="adminprocess.php" method="POST">';
			$html .= '<label>Days:</label><select name="inactdays">';
			$html .= '<option value="3">3</option><option value="7">7</option><option value="14">14</option><option value="30">30</option><option value="100">100</option><option value="365">365</option>';
			$html .= '</select><br />';
			$html .= '<input type="hidden" name="subdelinact" value="1" />';
			$html .= '<label>&nbsp;</label><button>Delete All Inactive</button>';
			$html .= '</form>';
			
			/**
			 * Display Banned Users Table
			 */
	
			$html .= '<h3>Banned Users Table Contents</h3>';
			$html .= displayBannedUsers();
			
			$html .= '<h3>Active Users</h3>';
			$q = "SELECT username FROM ".TBL_ACTIVE_USERS." ORDER BY timestamp DESC,username";
			$result = $database->query($q);
			/* Error occurred, return given name by default */
			$num_rows = mysql_numrows($result);
			if(!$result || ($num_rows < 0)){
				$html .= "Error displaying info";
			}
			else if($num_rows > 0){
				/* Display active users, with link to their info */
				$html .= '<table class="viewTables">';
				while($row = mysql_fetch_assoc($result)){
					$html .= '<tr><td>';
						$html .= '<a href="userinfo.php?user='.$row['username'].'">'.$row['username'].'</a>';
					$html .= '</td></tr>';
				}
				$html .= '</table>';
			}
		}else{
			$html .= 'You\'re not allowed here!';
		}
	}


