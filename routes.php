 <?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header("Content-type:application/json",true);
header("content-type: text/html; charset=utf-8");
// header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

$app->group('/api', function () use ($app) {
    

    $app->post('/user', function ($request, $response) {
        $input = $request->getParsedBody();
        $sql = "SELECT * FROM login WHERE username=:username AND password=:password";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("username", $input['username']);
        $sth->bindParam("password", $input['password']);
        $sth->execute();
        $count = $sth->rowCount();
        if($count==0){
            $message = (object)array('username' => 'failed', 'password' => 'failed'); 
            return $this->response->withJson($message);
        }else{
            $user = $sth->fetchObject();
            return $this->response->withJson($user);
        }
    });

    //cakeapp

//cakerecommen
$app->get('/cakerecommen', function ($request, $response, $args) {   
    $sth = $this->db->prepare("SELECT * FROM cakerecommen ORDER BY rm_id");
    $sth->execute();
    $data = $sth->fetchAll();
    $cakerecommens = array("cakerecommens"=>$data);
    return $this->response->withJson($cakerecommens);
});  

$app->get('/caken', function ($request, $response, $args) {   
    $sth = $this->db->prepare("SELECT * FROM caken ORDER BY cn_id");
    $sth->execute();
    $data = $sth->fetchAll();
    $cakens = array("cakens"=>$data);
    return $this->response->withJson($cakens);
}); 

$app->get('/c_usertable', function ($request, $response, $args) {   
    $sth = $this->db->prepare("SELECT * FROM c_usertable ORDER BY id");
    $sth->execute();
    $data = $sth->fetchAll();
    $c_usertable = array("c_usertable"=>$data);
    return $this->response->withJson($c_usertable);
});  

$app->get('/cake_size', function ($request, $response, $args) {   
    $sth = $this->db->prepare("SELECT * FROM cake_size ORDER BY size_id");
    $sth->execute();
    $data = $sth->fetchAll();
    $cake_sizes = array("cake_size"=>$data);
    return $this->response->withJson($cake_sizes);
}); 

$app->get('/order_table', function ($request, $response, $args) {   
    $sth = $this->db->prepare("SELECT * FROM order_table ORDER BY order_id");
    $sth->execute();
    $data = $sth->fetchAll();
    $order_tables = array("order_table"=>$data);
    return $this->response->withJson($order_tables);
}); 

$app->get('/review', function ($request, $response, $args) {   
    $sth = $this->db->prepare("SELECT * FROM review ORDER BY rv_id");
    $sth->execute();
    $data = $sth->fetchAll();
    $reviews = array("review"=>$data);
    return $this->response->withJson($reviews);
}); 

});