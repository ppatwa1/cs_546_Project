  <?php
		function runMatching($contacts) {
			// Read data from jva_contacts table and store it in a object
			$jva_data = array ();
			$jvaQuery = "SELECT * FROM jva_contacts";
			$DBResource = getDBConnection ();
			$jva_resultSet = $resultSet = execSQL ( $DBResource, $jvaQuery );
			if (mysqli_num_rows ( $jva_resultSet ) > 0) {
				while ( $jva_r = mysqli_fetch_row ( $jva_resultSet ) ) {
					$jva_row = new TempContact ();
					$jva_row->jva_id = $jva_r [0];
					$jva_row->jva_first_name = $jva_r [1];
					$jva_row->jva_middle_name = $jva_r [2];
					$jva_row->jva_last_name = $jva_r [3];
					$jva_row->jva_salutation = $jva_r [4];
					$jva_row->jva_phone_no = $jva_r [5];
					$jva_row->jva_fax_no = $jva_r [6];
					$jva_row->jva_country = $jva_r [7];
					$jva_row->jva_zipcode = $jva_r [8];
					$jva_row->jva_email = $jva_r [9];
					array_push ( $jva_data, $jva_row );
				}
				closeDBConnection ( $DBResource );
				
				// To compare each record in jva_contacts with all records in the file
				foreach ( $jva_data as $jva_row ) {
					foreach ( $contacts as $contacts_row ) {
						$c_last = $contacts_row->con_last_name;
						$j_last = $jva_row->jva_last_name;
						$DBResource = getDBConnection ();
						if (($jva_row->jva_last_name == $contacts_row->con_last_name) && ($jva_row->jva_country == $contacts_row->con_country) && ($jva_row->jva_email == $contacts_row->con_email)) {
							$nQuery = "INSERT INTO notifications(src_id,src_con_id,jva_id,match_case,pending_notification) values('" . $contacts_row->src_id . "','" . $contacts_row->src_con_id . "'," . $jva_row->jva_id . ",'Perfect',1)";
							$resultSet = execSQL ( $DBResource, $nQuery );
						} else if (($jva_row->jva_country == $contacts_row->con_country) && ($jva_row->jva_email == $contacts_row->con_email)) {
							if (strpos ( $j_last, $c_last ) !== FALSE || strpos ( $c_last, $j_last ) !== FALSE) {
								$nQuery = "INSERT INTO notifications(src_id,src_con_id,jva_id,match_case,pending_notification) values('" . $contacts_row->src_id . "','" . $contacts_row->src_con_id . "'," . $jva_row->jva_id . ",'Partial',1)";
								$resultSet = execSQL ( $DBResource, $nQuery );
							}
						} else {
							$nQuery = "INSERT INTO notifications(src_id,src_con_id,jva_id,match_case,pending_notification) values('" . $contacts_row->src_id . "','" . $contacts_row->src_con_id . "',0,'New',1)";
							$resultSet = execSQL ( $DBResource, $nQuery );
						}
						closeDBConnection ( $DBResource );
					} // end of inner for each loop
				} // end of outer for each loop
			} else {
				foreach ( $contacts as $contacts_row ) {
					$nQuery = "INSERT INTO notifications(src_id,src_con_id,jva_id,match_case,pending_notification) values('" . $contacts_row->src_id . "','" . $contacts_row->src_con_id . "',0,'New',1)";
					execSQL ( $DBResource, $nQuery );
				}
			}
		} // end of runMatching
		?>