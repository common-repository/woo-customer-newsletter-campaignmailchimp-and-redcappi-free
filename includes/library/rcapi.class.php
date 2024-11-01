<?php
/*
	RedCappi API
	version: v1
	Sample code for PHP
		
	Authored by tech@redcappi.com
	---------------------------------------------------------------------------
	This sample code uses the CURL library for php to establish a connection,
	submit the post, and get the response.
	If you receive an error, you may want to ensure that you have the curl
	library enabled in your php configuration.
	
	additional options may be required depending upon your server configuration
	you can find documentation on curl options at http://www.php.net/curl_setopt
	---------------------------------------------------------------------------

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

class RedCappiClientApi {
	private $public_key;
	private $private_key;
	private $api_url;
	private $development;
	function __construct($public_key, $private_key, $api_domain, $api_folder) {
		$this->public_key = $public_key;
		$this->private_key = $private_key;		
		$this->api_domain = $api_domain;		
		$this->api_folder = $api_folder;		
		$this->development = 0; // to debug, update with 1		
	}

	
// --------------------------------------------------------------------------------------------
// List Management
// --------------------------------------------------------------------------------------------
		
	function showList($id=0){		
		if($id > 0)
		return $this->CallApi('/lists.json?id='.$id , 'GET');	
		else
		return $this->CallApi('/lists.json/' , 'GET');	
	}
	function addList( $name){		
		return $this->CallApi('/lists/'.urlencode($name), 'POST');	
	}
	
	function renameList($intId, $new_name){		
		return $this->CallApi('/lists.json?id='.$intId.'&name='.urlencode($new_name), 'POST', null);	
	}
	
	function removeList($intId, $content=null){		
		return $this->CallApi('/lists.json/'.$intId, 'DELETE', $content);	
	}
		
	
// --------------------------------------------------------------------------------------------
// Contact Management
// --------------------------------------------------------------------------------------------
	
	function showContact($lid, $cid=null){			
		return $this->CallApi('/contacts.json/'.$lid.'/contact/'.$cid, 'GET');	
	}
	function showContacts($lid, $pagesize=5, $offset=2){			
		return $this->CallApi('/contacts.json/'.$lid.'/contacts/?pagesize='.$pagesize.'&offset='.$offset, 'GET');			
	}
	
	
	function addContacts($lid, $content){		
		return $this->CallApi('/contacts.json/'.$lid.'/contacts/', 'POST', $content);
	}
	function updateContact($lid, $cid, $content){		
		return $this->CallApi('/contacts.json/'.$lid.'/contacts/'.$cid, 'POST', $content);
	}
	function removeContacts($lid, $cid){				 
		return $this->CallApi('/contacts.json/'.$lid.'/contacts/'.$cid, 'DELETE', null); 
	}
		
	 
	
	function CallApi( $api_path, $method = 'POST', $content=array()) {
		
		if(empty($content)){
			$request_data = json_encode(array('posted_data'=>json_encode($content)));
		}else{
			$request_data =json_encode($content);
		}
	
		$signature = $this->createSignature($method, $api_path,$request_data);
		
		if(!mb_check_encoding($signature, 'UTF-8')) $signature = utf8_encode($signature); 
		$hash = (hash_hmac('sha256', $signature, $this->private_key));
			 
			 
	
		$headers = array(
			"Content-Length: ".strlen($request_data),			
			"Content-Type: application/json; charset=UTF-8", 
			"Accept: */*",
			"Authorization: RCWS ".$this->public_key.':'.$hash			
		);
		$ch = curl_init($this->api_domain.$this->api_folder.$api_path);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);	
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$request_data);	 
		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$requestHeader =curl_getinfo($ch, CURLINFO_HEADER_OUT);
		curl_close($ch);
		if($this->development)
		return $result."<br/><br/>".'<pre>'. htmlspecialchars($requestHeader). '</pre>' ;
		else
		return $result ;
	}
	private function createSignature($method, $api_url,$posted_data=''){
		$arrAPIUrl = @explode('?',$api_url);
		$sortedQueryString = '';
		$strQueryString = (count($arrAPIUrl) > 1)?$arrAPIUrl[1]:'';
		if(trim($strQueryString) != ''){
			parse_str($strQueryString,$arrTemp);
			if(!empty($arrTemp)){
				@uksort($arrTemp, 'strcasecmp');
				$sortedQueryString =  http_build_query($arrTemp, '', '&');
			}
		}
		
		$arrPostedData = json_decode($posted_data,true);
		
		if(!empty($arrPostedData)){
			@uksort($arrPostedData, 'strcasecmp');
		}
		
		if(!empty($arrPostedData)){ 
		$signature = strtolower($method.'::'.str_replace($strQueryString, $sortedQueryString,$this->api_folder.$api_url).'::'.json_encode($arrPostedData));
		}else{
		 $signature = strtolower($method.'::'.str_replace($strQueryString, $sortedQueryString,$this->api_folder.$api_url).'::null');
		}
		return $signature;
	
	}
}
/**
* End of class file
*/