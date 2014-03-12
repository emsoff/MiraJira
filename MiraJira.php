<?php
/**
*	Description : A Jira PHP API wrapper for basic functions.
* 	Author: Justin Emsoff
* 	Created : 7/1/13
*	Revised : 1/4/14
* 	License: WTFPL <http://www.wtfpl.net>
**/

	class MiraJira
	{
	    /**
	    * Class variables. 
	    *
	    * @var string $username Jira username 
	    * @var string $password Jira password 
	    * @var string $baseURL Jira API base URL. Should not change.
	    */

		public $baseURL = "https://***YOUR_DOMAIN***.atlassian.net/rest/api/2/";
		private $username = '***YOUR_USERNAME***';
		private $password = '***YOUR_PASSWORD***';
		
		/**
		* Confirms cURL is installed and initializes
		*
		* @param int $curlopt index of option expressed as CURLOPT_ constant
		* @param mixed $value what to set this option to
		*/

		public function __construct() {
			if (!extension_loaded('curl'))
				throw new Exception('cURL not installed');
			if (!($this->ch = curl_init()))
				throw new Exception('cURL could not be instantiated');

			$options = array(
				CURLOPT_USERPWD => "$this->username:$this->password",
				CURLOPT_HTTPHEADER => array('Accept: application/json','Content-Type: application/json'),
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_AUTOREFERER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTPGET => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_VERBOSE => true
        	);

			curl_setopt_array($this->ch, $options);
		}

		/**
		* Closes cURL instance.
		*
		**/

		public function __destruct() {
			curl_close($this->ch);
		}

		/**
		* Execute a cURL statement.
		*
		* @param resource $curl: Our CURL instance, $this->ch.
		* @return object
		*
		**/

		public function executeCurl($curl)	
		{
			return curl_exec($curl);
		}


		/**
		* Get project details.
		*
		* @param string $projectKey: project key or id.
		*
		**/

		public function getDetails($projectKey)	
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "project/" . $projectKey);
			return $this->executeCurl($this->ch);
		}


		/**
		* Get status of issue
		*
		* @param string $issueKey: issue key or id;
		*
		**/

		public function getIssueStatus($issueKey) 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "issue/" . $issueKey);
			return $this->executeCurl($this->ch);
		}


		/**
		* Delete a comment
		*
		* @param string $issueKey: issue key or id;
		* @param string $commentID: comment id;
		*
		**/

		public function deleteComment($issueKey,$commentID) 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "issue/" . $issueKey . "/comment/" . $commentID);
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			return $this->executeCurl($this->ch);
		}


		/**
		* Get all projects.
		*
		**/

		public function getProjects()  
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "project");
			return $this->executeCurl($this->ch);
		}


		/**
		* Get meta fields for projects.
		*
		**/

		public function getMeta()	
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "issue/createmeta");
			return $this->executeCurl($this->ch);
		}


		/**
		* Gets the edittable meta fields for an issue.
		* @param string $issueKey: issue key or id;
		*
		**/

		public function editMeta($issueKey)
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "issue/" . $issueKey ."/editmeta");
			return $this->executeCurl($this->ch);
		}


		/**
		* Get details of an issue.
		* @param string $issueKey: issue key or id;
		*
		**/

		public function getIssueDetails($issueKey)
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "issue/" . $issueKey);
			return $this->executeCurl($this->ch);
		}


		/**
		* Edit the status of an issue.
		* @param array $data: See link for example representation.
		* @link https://docs.atlassian.com/jira/REST/latest/#d2e1984
		* 
		**/

		public function editIssueStatus($data)
		{
 			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "issue/");
			return $this->executeCurl($this->ch);
		}


		/**
		* Search for an issue
		* @param string $jqlQuery: a JQL query, see link for samples.
		* @link https://docs.atlassian.com/jira/REST/latest/#d2e2438
		*
		**/

		public function search($jqlQuery)	
		{
			global $search;
			$search = "search?jql&".$project;
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . $search);
			return $this->executeCurl($this->ch);
		}


		/**
		* Returns all available dashboards
		*
		**/

		public function getDashboards()	
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "dashboard");
			return $this->executeCurl($this->ch);
		}


		/**
		* Returns details of designated dashboard
		* 
		* @param string $dashboardID, can be obtained from getDashboards();
		*
		**/

		public function getDashboard($dashboardID)	
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "dashboard/" . $dashboardID);
			return $this->executeCurl($this->ch);
		}


		/**
		* Returns all available fields
		*
		**/

		public function getFields()	
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . "field/");
			return $this->executeCurl($this->ch);
		}


		/**
		* Assign new user to a Jira issue
		*
		* @param string $userName : Jira name of desired assignee
		* @param string $issueKey : issueKey of targeted issue
		*
		**/

	    public function changeAssignee($userName, $issueKey) 
	    {
	        $newComment = array(
	            "name"  => $userName,
	        );
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");
	        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($newComment));
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'issue/' . $issueKey . '/assignee');
			return $this->executeCurl($this->ch);
	    }


		/**
		* Returns all issue comments with Issue ID
		*
		* @param string $issueKey : issueKey of targeted issue
		*
		**/

		public function getIssueComments($issueKey) 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'issue/' . $issueKey . '/comment');
			return $this->executeCurl($this->ch);
		}


		/**
		* Adds a comment to an issue
		*
		* @param string $issueKey : issueKey of targeted issue
		* @param string $comment : Comment text
		*
		**/

	    public function addComment($comment, $issueKey) 
	    {
	        $newComment = array(
	            "body"  => $comment,
	        );
	        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($newComment));
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'issue/' . $issueKey . '/comment');
			return $this->executeCurl($this->ch);
	    }


		/**
		* Adds a comment to an issue
		*
		* @param string $issueKey : issueKey of targeted issue
		* @param string $comment : Comment text
		* @param string $commentID : Comment you'd like to update
		*
		**/

	    public function updateComment($comment, $issueKey, $commentID) 
	    {
	        $newComment = array(
	            "body"  => $comment,
	        );
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");
	        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($newComment));
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'issue/' . $issueKey . '/comment/' . $commentID);
			return $this->executeCurl($this->ch);
	    }


		/**
		* Create an issue
		*
		* @param array $data : data of new issue
		*
		**/

		public function createIssue($data)
		{
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'issue' );
			return $this->executeCurl($this->ch);
		}


		/**
		* Get properties of a project
		*
		* @param string $projectKey : data of the new issue.
		*
		**/

		public function getProperties($projectKey) 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'project/' . $projectKey . '/properties');
			return $this->executeCurl($this->ch);
		}


		/**
		* Get roles of a project
		*
		* @param string $projectKey : data of the new issue.
		* @param string $role : Role id, usually a 5-digit number
		*
		**/

		public function getRole($projectKey, $role) 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'project/' . $projectKey . '/role/' . $role);
			return $this->executeCurl($this->ch);
		}


		/**
		* Get available resolutions
		*
		**/

		public function getResolutions() 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'resolution');
			return $this->executeCurl($this->ch);
		}


		/**
		* Get available statuses
		*
		**/

		public function getStatuses() 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'status');
			return $this->executeCurl($this->ch);
		}


		/**
		* Get available workflows
		*
		**/

		public function getWorkflows() 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'workflow');
			return $this->executeCurl($this->ch);
		}


		/**
		* Create a new filter
		*
		* @param string $name : data of the new issue.
		* @param string $description : data of the new issue.
		* @param string $qry : JQL query, see link for reference.
		* @param boolean $favourite : Add to favourites.
		* @link https://confluence.atlassian.com/display/JIRA/Advanced+Searching
		*
		**/

		public function createFilter($name, $description, $qry, $favourite) 
		{
			$data = array(
					'name' => $name,
					'description' => $description,
					'jql' => $qry,
					'favourite' => $favourite);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'filter');
			return $this->executeCurl($this->ch);
		}


		/**
		* Get available issue priorities
		*
		**/

		public function getPriorities() 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'priority');
			return $this->executeCurl($this->ch);
		}


		/**
		* Get priority details
		* @param mixed $priorityID : String or integer of priority
		*
		**/

		public function getPriorityDetails($priorityID) 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'priority/' . $priorityID);
			return $this->executeCurl($this->ch);
		}


		/**
		* Get user's available projects
		*
		**/

		public function getMyProjects() 
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->baseURL . 'project');
			return $this->executeCurl($this->ch);
		}
	};
	
?>