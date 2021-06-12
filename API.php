<?php

// actions

// connect with DB🥰
$con = new PDO("mysql:host=localhost;dbname=testing", "root", "");


// GET CONTENTS & DECODE ITS
$received_data = json_decode(file_get_contents("php://input"));

//  STE DATA VAR
$data = [];


//  CHECK THE ACTION === FETCHALL
if ($received_data->action == 'fetchall') :
    // create query
    $query = "SELECT * FROM users ORDER BY id DESC";
    // PREPRAE STATEMNET
    $stmt = $con->prepare($query);
    // execute query
    $stmt->execute();

    // loop through data
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
        $data[] = $row;
    endwhile;
    // encode data
    echo json_encode($data);
endif;

//  CHECK THE ACTION === FETCH SINGLE
if ($received_data->action == 'fetchSingle') :
    // create query
    $query = "
      SELECT * FROM users
      WHERE id='.$received_data->id.'
    ";
    // prepare statment
    $stmt = $con->prepare($query);
    // execute query
    $stmt->execute();
    // fetach results
    $result = $stmt->fetchAll();
    foreach ($result as $row) :
        $data['id'] = $row['id'];
        $data['first_name'] = $row['first_name'];
        $data['last_name'] = $row['last_name'];
    endforeach;
    echo json_encode($data);
endif;

//  CHECK THE ACTION === INSERT
if ($received_data->action == 'insert') :
    $data = [
        ':first_name' => $received_data->firstName,
        ':last_name' => $received_data->lastName
    ];

    // create query
    $stmt = $con->prepare($query);
    $stmt->execute($data);

    // output message
    $output = [
        'message' => 'Dtat Inserted😍🥰'
    ];

    echo json_encode($output);

endif;

//  CHECK THE ACTION === UPDATE
if ($received_data->action == 'update') :
    $data = [':first_name' => $received_data->firstName, ':last_name' => $received_data->lastName, ':id' => $received_data->hiddenId];
    // create query
    $query = "
       UPDATE users
       SET first_name=:first_name,
       last_name=:last_name,
       WHERE id=:id
    ";
    $stmt = $con->prepare($query);
    $stmt->execute($data);
    $output = ['message' => 'Data Updated🥰'];

    echo json_encode($output);

endif;

//  CHECK THE ACTION === DELETE
if ($received_data->action == 'delete') :

    $query = "
       DELETE FROM users
       WHERE id='.$received_data->id.'
    ";

    $stmt = $con->prepare($query);
    $stmt->execute();
    $output = [
        'message' => 'Data Deleted'
    ];

    echo json_encode($output);

endif;
