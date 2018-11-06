<?php

/**
 * Created by Angelo Biaggi
 * Date: 04/18/18
 * This file will send the request to register anew users
 */
 
//importing required script
require_once '../Includes/DbOperation.php';
 
$response = array();
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    
    if (!verifyRequiredParams(array('username', 'password', 'email', 'name'))) 
    {
        //getting values
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $name = $_POST['name'];
 
        //creating db operation object
        $db = new DbOperation();
 
        //adding user to database
        $result = $db->createUser($username, $password, $email, $name);
 
        //making the response accordingly
        if ($result == USER_CREATED) 
        {
            $response['Error'] = false;
            $response['Message'] = 'User created successfully';
        } 
        elseif ($result == USER_ALREADY_EXISTS) 
        {
            $response['Error'] = true;
            $response['Message'] = 'User already exist';
        } 
        elseif ($result == USER_NOT_CREATED) 
        {
            $response['Error'] = true;
            $response['Message'] = 'Some error occurred';
        }
    } 
    else 
    {
        $response['Error'] = true;
        $response['Message'] = 'Required parameters are missing';
    }
} 
else 
{
    $response['Error'] = true;
    $response['Message'] = 'Invalid request';
}
 
//function to validate the required parameter in request
function verifyRequiredParams($required_fields)
{
 
    //Getting the request parameters
    $request_params = $_REQUEST;
 
    //Looping through all the parameters
    foreach ($required_fields as $field) {
        //if any requred parameter is missing
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) 
        {
 
            //returning true;
            return true;
        }
    }
    return false;
}
 
echo json_encode($response);