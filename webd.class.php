<?php 

	/* 	
	 *	Title	 --	WeDB
	 *	Author	 -- Roman Novohackii
	 *	Twitter	 -- @Gruve94
	 * 	WebSite	 -- http://wetwi.com
	 *	Language -- PHP
	 */


 //	Connection parameters
 define ( "BD_HOST", "localhost" );
 define ( "BD_USER", "YOUR_NAME" );
 define ( "BD_PASS", "YOUR_PASS" );
 define ( "BD_NAME", "YOUR_BASE" );
  
 class weBD {
	
	protected $connect, $error, $table_name;
	
	function __construct ( $tb_name = FALSE ) {
		
		$this->error 	= FALSE;
		$this->connect  = new mysqli ( BD_HOST , BD_USER , BD_PASS , BD_NAME );
		
		if ( mysqli_connect_errno () ) exit;
		
		if ( $tb_name !== FALSE ) $this->table_name = $this->safe ( $tb_name );
		
	}
	
	function query ( $query , $type = FALSE ) {
		
		$query	= $this->safe ( $query );
		$type	= $this->safe ( $type, "text" );		
			
		if ( $result = $this->connect->query ( $query ) ) {
			
			if ( $type === FALSE ) return $result;
			
			$adjacent = Array();
			
			switch ( $type ) {
			
				case 'fetch': 
				
					return $result->fetch_array ( MYSQLI_ASSOC ); 
					break;
				
				case 'num': 
					
					return $result->num_rows; 
					break;
				
				case 'num-fetch': 
					
					$adjacent['num'] 	= $result->num_rows;
					$adjacent['fetch']  = $result->fetch_array ( MYSQLI_ASSOC );
					return $adjacent;
					break;
				
				case 'num-for': 
				
					$adjacent['num'] = $result->num_rows;
					
					while ( $row = $result->fetch_array ( MYSQLI_ASSOC ) ) {
					
						$adjacent['fetch'][] = $row;
					
					}
					
					return $adjacent;
					break;
					
				case 'each':
				
					while ( $row = $result->fetch_array ( MYSQLI_ASSOC ) ) {
					
						$adjacent[] = $row;
					
					}
					
					return $adjacent;
					break;
			}
			
			$result->close();
			
		} else return FALSE;
	}
	
	function safe ( $str, $regular = FALSE, $long = FALSE ) { 
		
		if ( $str ) {
		
			if ( is_array ( $str ) ) {
				
				foreach ( $str as $key => $value ) {
				
					$str [ $key ] = trim ( htmlspecialchars ( $value , ENT_QUOTES ) ); 
			
				}
			
				return $str;
		
			}
		
			if ( $regular ) {
				
				$arr = Array (
							  "num" 	=> "[^0-9]",
							  "num+" 	=> "[^0-9.,]", 									  
							  "text"	=> "[^a-zA-Zр- ╕└-▀и0-9_!?(),.\- ]",
							  "eng"		=> "[^a-zA-Z]",
							  "rus"		=> "[^р- ╕└-▀и]",
							  "WM"		=> "[^UZER0-9]",
							  "url"		=> "[^a-zA-Z└-▀ир- ╕0-9.\-_\/:#%&?=+]",
							  "letters"	=> "[^a-zA-Zр- ╕└-▀и ]",
							  "letters+"=> "[^a-zA-Zр- ╕└-▀и0-9 ]",
							 );
				
				$regular = $arr [ $regular ];
				
				$str = preg_replace("/$regular/" , "" , $str);
				
				if ( $long ) $str = substr ( $str , 0 , $this->safe ( $long ) );
				
			}
			
			$str = trim ( htmlspecialchars ( $str , ENT_QUOTES ) ); 
			
			return $str;
		}
	}
 }
?>