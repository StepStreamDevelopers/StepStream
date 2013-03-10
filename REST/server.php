<?php




class Server {
    /* The array key works as id and is used in the URL
       to identify the resource.
    */
    private $dbuser = 'user';
    private $dbpass = 'pass';
    private $dbname = 'db';
    private $dbhost = 'dbhost';
    private $con;
  	$paramsFile = fopen('../stepstream/config.php','r');

  	while(!feof($paramsFile))
  	{
		$buffer = fgets($paramsFile);
		list($name,$value) = split('=',trim($buffer));

        if (strlen(strstr($name,"server"))>0) {
	 		$value = str_replace("'" ,"", $value);
	 		$value = str_replace(" " ,"", $value);
         	$dbhost = str_replace(";" ,"", $value) ;
       }
		if (strlen(strstr($name,"database"))>0) {
			$newval = str_replace("'" ,"", $value);
			$temp1 = explode("//" , $newval);
			$temp2 = explode(":" , $temp1[1]);
			$dbuser = str_replace(";" ,"",$temp2[0]);
            $int_pos = strrpos($temp2[1], '@');
            $dbpass = substr($temp2[1],0,$int_pos);
			$temp4 = explode("/" , substr($temp2[1],$int_pos + 1));
            $servername = str_replace(";" ,"",$temp4[0]);  
			$dbname = str_replace(";" ,"",$temp4[1]); 
   		}	

  	}
	fclose ($paramsFile);


    public function serve() {
        $this->con = mysql_connect($this->dbhost,$this->dbuser,$this->dbpass);
        if (!$this->con)
          {
          die('Could not connect: ' . mysql_error());
          }
      
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $paths = explode('/', $this->paths($uri));
        array_shift($paths); // Hack; get rid of initials empty string
        $resource = array_shift($paths);
      
        
        $valType = array_shift($paths);
        
        $profileId = array_shift($paths);
        
        if (empty($profileId)) {
            $this->handle_base($method , $valType);
        } else {
            $this->handle_name($method, $valType, $profileId);
        }
            
        mysql_close($this->con);
          
         
    }
        
    private function handle_base($method , $valType) {
        switch($method) {
        case 'GET':
            $this->result();
            break;
        default:
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET');
            break;
        }
    }

    private function handle_name($method, $valType, $profileId) {
        
        switch($method) {
        case 'POST':
            if($valType == "points")
              $this->submit_scores($profileId);
            else if($valType == "tokens")
              $this->submit_tokens($profileId);
            break;

       
      
        case 'GET':
            if($valType == "points")
              $this->display_scores($profileId);
            else if($valType == "tokens")
              $this->display_tokens($profileId);
            else if($valType == "subscriptions")
              $this->display_subscriptions($profileId);
            break;

        default:
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, PUT, DELETE');
            break;
        }
    
   }
    private function submit_scores($profileId){
       
        $score = $_POST['score'];
       
        $dbquery = "UPDATE user_points SET available_points =  '" . $score . "' WHERE  profile_id = " . $profileId;
        
        mysql_select_db($this->dbname, $this->con);

        mysql_query($dbquery);
    }
    
    
     private function submit_tokens($profileId){
       
        $tokens = $_POST['tokens'];
       
        $dbquery = "INSERT INTO game_token (profile_id, token) VALUES ($profileId,$tokens)";
        
        mysql_select_db($this->dbname, $this->con);

        mysql_query($dbquery);
    }
    
  
    
    private function display_scores($profileId) {
        $dbquery = "SELECT available_points FROM user_points WHERE profile_id=" . $profileId;
        mysql_select_db($this->dbname, $this->con);

        $result = mysql_query($dbquery);

        while($row = mysql_fetch_array($result))
          {
                 echo $row['available_points'];
                } 
      }
      
      private function display_tokens($profileId) {
        $dbquery = "SELECT SUM( token ) as tokenSum FROM game_token WHERE profile_id =$profileId";
        mysql_select_db($this->dbname, $this->con);

        $result = mysql_query($dbquery);

        while($row = mysql_fetch_array($result))
          {
                 echo $row['tokenSum'];
                } 
      }
      
      
      
       private function display_subscriptions($profileId) {
        $dbquery = "SELECT * FROM  subscription WHERE subscriber =$profileId";
        mysql_select_db($this->dbname, $this->con);

        $result = mysql_query($dbquery);
        $i = 0;
        while($row = mysql_fetch_array($result))
          {
                 $profileIDList[$i++] = $row['subscribed'] ;
                }
                
                print_r($profileIDList); 
      }
    
    private function paths($url) {
        $uri = parse_url($url);
        return $uri['path'];
    }
    
    /**
     * Displays a list of all contacts.
     */
    private function result() {
        header('Content-type: application/json');
        echo 0;
    }
  }

$server = new Server;
$server->serve();

?>
