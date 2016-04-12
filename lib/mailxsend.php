<?PHP
    require_once('mail.php');
    class MAILXsend{

        protected $appid='';

        protected $appkey='';

        protected $sign_type='';

        protected $To=array();

        protected $Addressbook=array();

        protected $From='';

        protected $From_name='';

        protected $Reply='';

        protected $Cc=array();

        protected $Bcc=array();

        protected $Subject='';

        protected $Project='';

        protected $Vars=array();

        protected $Links=array();

        protected $Headers=array();
        
        protected $asynchronous="false";

        function __construct($configs){
            $this->appid=$configs['appid'];
            $this->appkey=$configs['appkey'];
            if(!empty($configs['sign_type'])){
                $this->sign_type=$configs['sign_type'];
            }
        }

        public function AddTo($address,$name=''){
            array_push($this->To,array('address'=>$address,'name'=>$name));
        }

        public function AddAddressbook($addressbook){
            array_push($this->Addressbook,$addressbook);
        }

        public function SetSender($sender,$name=''){
            $this->From=$sender;
            $this->From_name=$name;
        }

        public function SetReply($reply){
            $this->Reply=$reply;
        }

        public function AddCc($address,$name=''){
            array_push($this->Cc,array('address'=>$address,'name'=>$name));
        }

        public function AddBcc($address,$name=''){
            array_push($this->Bcc,array('address'=>$address,'name'=>$name));
        }

        public function SetSubject($subject){
            $this->Subject=$subject;
        }

        public function SetProject($project){
            $this->Project=$project;
        }

        public function AddVar($key,$val){
            $this->Vars[$key]=$val;
        }

        public function AddLink($key,$val){
            $this->Links[$key]=$val;
        }

        public function AddHeaders($key,$val){
            $this->Headers[$key]=$val;
        }
        
        public function setAsynchronous($asynchronous){
            if($asynchronous==true){
                $this->asynchronous="true";
            }else{
                $this->asynchronous="false";
            }
        }

        protected function buildRequest(){
            $request=array();

            if(!empty($this->To)){
                $request['to']='';
                foreach($this->To as $tmp){
                    $request['to'].=$tmp['name'].'<'.$tmp['address'].'>,';
                }
                $request['to'] = substr($request['to'],0,count($request['to'])-2);
            }

            if(!empty($this->Addressbook)){
                $request['addressbook']='';
                foreach($this->Addressbook as $tmp){
                    $request['addressbook'].=$tmp.',';
                }
                $request['addressbook'] = substr($request['addressbook'],0,count($request['addressbook'])-2);
            }

            if($this->From!=''){
                $request['from']=$this->From;
            }

            if($this->From_name!=''){
                $request['from_name']=$this->From_name;
            }

            
            if($this->Reply!=''){
                $request['reply']=$this->Reply;
            }
            
            if(!empty($this->Cc)){
                $request['cc']='';
                foreach($this->Cc as $tmp){
                    $request['cc'].=$tmp['name'].'<'.$tmp['address'].'>,';
                }
                $request['cc'] = substr($request['cc'],0,count($request['cc'])-2);
            }
            
            if(!empty($this->Bcc)){
                $request['bcc']='';
                foreach($this->Bcc as $tmp){
                    $request['bcc'].=$tmp['name'].'<'.$tmp['address'].'>,';
                }
                $request['bcc'] = substr($request['bcc'],0,count($request['bcc'])-2);
            }

            if($this->Subject!=''){
                $request['subject']=$this->Subject;
            }
            
            $request['project']=$this->Project;
            
            if(!empty($this->Vars)){
                $request['vars']=json_encode($this->Vars);
            }
            
            if(!empty($this->Links)){
                $request['links']=json_encode($this->Links);
            }

            if(!empty($this->asynchronous)){
                $request['asynchronous']= $this->asynchronous;
            }
            
            if(!empty($this->Headers)){
                $request['headers']=json_encode($this->Headers);
            }
            return $request;
            
        }
        
        public function xsend(){

            $mail_configs['appid']=$this->appid;
            $mail_configs['appkey']=$this->appkey;
            if($this->sign_type!=''){
                $mail_configs['sign_type']=$this->sign_type;
            }
            
            $mail=new mail($mail_configs);
            return $mail->xsend($this->buildRequest());
        }
    }