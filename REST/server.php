<?php



class Server {
    /* The array key works as id and is used in the URL
       to identify the resource.
    */
              
    private $dbhost = 'mysql.stephealth.us';

    private $dbuser = 'stephealth';
    private $dbpass = 'stepstream8903';
    private $dbname = 'stephealth1';
    private $con;
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
            break;

        default:
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, PUT, DELETE');
            break;
        }
    
   }
    private function submit_scores($profileId){
       
        $score = $_POST['score'];
       
        $dbquery = "UPDATE stepcount SET points_earned =  '" . $score . "' WHERE  profile_id = " . $profileId;
        
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
