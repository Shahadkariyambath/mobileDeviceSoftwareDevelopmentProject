<?php
//
$host = 'localhost';
$user_name = 'root';
$pass = ''; // password
$db = 'travelcompany'; // Database name

global $pdo;

try {

    $pdo = new PDO("mysql:host=$host; dbname=$db;", $user_name, $pass);
    $created = date("Y:m:d h:i:s");

} catch (PDOException $e) {

    echo "Error!: " . $e->getMessage() . "<br/>";

}


$current_file = $_SERVER['SCRIPT_NAME'];
$http_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";

///QUERY MADE EASY
function Query($query)
{
    $stmt = $GLOBALS['pdo']->prepare($query);
    $stmt->execute();
    return $stmt;
}

// insert new record into database and handle ajax request
function insert($table, $record)
{
    $keys = array_keys($record);
    $values = implode(', ', $keys);
    $valuesWithColon = implode(', :', $keys);
    $query = 'INSERT INTO ' . $table . ' (' . $values . ') VALUES (:' . $valuesWithColon . ')';
    $insert_stmt = $GLOBALS['pdo']->prepare($query);
    $insert_stmt->execute($record);
    return $insert_stmt;

}



function insertWhere($table, $field, $value, $record)
{

    $keys = array_keys($record);
    $values = implode(', ', $keys);
    $valuesWithColon = implode(', :', $keys);
    $query = 'INSERT INTO ' . $table . ' (' . $values . ') VALUES (:' . $valuesWithColon . ') WHERE ' . $field . ' = ' . $value . ' ';
    $insert_stmt = $GLOBALS['pdo']->prepare($query);
    $insert_stmt->execute($record);
    return $insert_stmt;

}

//fetch all student record from database and handle ajax request
function fetchAllRecordsInnerJoin($table, $InnerJoinColumn, $ExternalJoinTable, $ExternalJoinColumn)
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM ' . $table . ' INNER JOIN ' . $ExternalJoinTable . ' On ' . $table . '.' . $InnerJoinColumn . ' = ' . $ExternalJoinTable . '.' . $ExternalJoinColumn . ' ');
    $stmt->execute();
    return $stmt;
}

function fetchAllRecordsInnerJoinWhere($table, $InnerJoinColumn, $ExternalJoinTable, $ExternalJoinColumn, $WhereColumn, $WhereValue)
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM ' . $table . ' INNER JOIN ' . $ExternalJoinTable . ' On ' . $table . '.' . $InnerJoinColumn . ' = ' . $ExternalJoinTable . '.' . $ExternalJoinColumn . ' WHERE ' . $WhereColumn . ' = ' . $WhereValue . ' ');
    $stmt->execute();
    return $stmt;
}

function fetchAllRecordsInnerJoinThreeTablesWhere($table, $InnerJoinColumn, $InnerJoinColumn2, $InnerJoinColumn3, $ExternalJoinTable, $ExternalJoinColumn, $ExternalJoinTable2, $ExternalJoinColumn2, $ExternalJoinTable3, $ExternalJoinColumn3, $WhereColumn, $WhereValue)
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM ' . $table . '
     INNER JOIN ' . $ExternalJoinTable . ' On ' . $table . '.' . $InnerJoinColumn . ' = ' . $ExternalJoinTable . '.' . $ExternalJoinColumn . '
     INNER JOIN ' . $ExternalJoinTable2 . ' On ' . $table . '.' . $InnerJoinColumn2 . ' = ' . $ExternalJoinTable2 . '.' . $ExternalJoinColumn2 . '
     INNER JOIN ' . $ExternalJoinTable3 . ' On ' . $table . '.' . $InnerJoinColumn3 . ' = ' . $ExternalJoinTable3 . '.' . $ExternalJoinColumn3 . '
     WHERE ' . $WhereColumn . ' = ' . $WhereValue . ' ');
    $stmt->execute();
    return $stmt;
}



function fetchAllRecords($table)
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM ' . $table);
    $stmt->execute();
    return $stmt;
}

function fetchAllRecordsWithFetchAll($table)
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM ' . $table);
    $stmt->execute();
    $resultAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultAll;
}


//fetch all student record from database and handle ajax request
function fetchARecordWithOneWhereClause($table, $field, $value)
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT *  FROM  ' . $table . ' WHERE ' . $field . ' = :value ');
    $criteria = [
        'value' => $value
    ];
    $stmt->execute($criteria);
    $resultAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultAll;
}

//fetch all student record from database and handle ajax request
function fetchARecordWithTwoWhereClause($table, $field, $value, $fieldtwo, $valuetwo)
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM ' . $table . ' WHERE ' . $field . ' = :value AND ' . $fieldtwo . ' = :valuetwo');
    $criteria = [
        'value' => $value,
        'valuetwo' => $valuetwo
    ];
    $stmt->execute($criteria);
    $resultAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultAll;
}

//fetch all student record from database and handle ajax request
function fetchARecordWithThreeWhereClause($table, $field, $value, $fieldtwo, $valuetwo, $fieldthree, $valuethree)
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM ' . $table . ' WHERE ' . $field . ' = :value AND ' . $fieldtwo . ' = :valuetwo AND ' . $fieldthree . ' = :valuethree');
    $criteria = [
        'value' => $value,
        'valuetwo' => $valuetwo,
        'valuethree' => $valuethree
    ];
    $stmt->execute($criteria);
    return $stmt;
}

function fetchARecordWithFourWhereClause($table, $field, $value, $fieldtwo, $valuetwo, $fieldthree, $valuethree, $fieldfour, $valuefour)
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM ' . $table . ' WHERE ' . $field . ' = :value AND ' . $fieldtwo . ' = :valuetwo AND ' . $fieldthree . ' = :valuethree ' . $fieldfour . ' = :valuefour');
    $criteria = [
        'value' => $value,
        'valuetwo' => $valuetwo,
        'valuethree' => $valuethree,
        'valuefour' => $valuefour
    ];
    $stmt->execute($criteria);
    return $stmt;
}


function fecthARecordWithTwoWhereClauseInnerJoin($table, $field, $value, $field2, $value2, $InnerJoinColumn, $ExternalJoinTable, $ExternalJoinColumn)
{

    $stmt = $GLOBALS['pdo']->prepare('SELECT *  FROM ' . $table . ' INNER JOIN ' . $ExternalJoinTable . ' On ' . $table . '.' . $InnerJoinColumn . ' = ' . $ExternalJoinTable . '.' . $ExternalJoinColumn . '
      WHERE ' . $field . ' = :value AND ' . $field2 . ' =:value2
      ');
    $criteria = [
        'value' => $value,
        'value2' => $value2
    ];
    $stmt->execute($criteria);
    return $stmt;

}

//delete student record from database and handle ajax request
function deleteRecord($table, $field, $value)
{

    $stmt = $GLOBALS['pdo']->prepare('DELETE FROM ' . $table . ' WHERE ' . $field . ' = :value');
    $criteria = [
        'value' => $value
    ];
    $stmt->execute($criteria);
    return $stmt;

}

function deleteRecordWithTwoWhereClause($table, $field, $value, $field2, $value2)
{

    $stmt = $GLOBALS['pdo']->prepare('DELETE FROM ' . $table . ' WHERE ' . $field . ' = :value AND ' . $field2 . ' = :value2');
    $criteria = [
        'value' => $value,
        'value2' => $value2
    ];
    $stmt->execute($criteria);
    return $stmt;

}

function findMoreThanThree($table, $field, $value, $additionalField, $value2, $extraadditionalField, $value3, $extra4, $value4)
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM ' . $table . ' WHERE ' . $field . ' = :value AND ' . $additionalField . ' =:value2 AND ' . $extraadditionalField . ' = :value3 AND ' . $extra4 . ' =:value4 ');
    $criteria = [
        'value' => $value,
        'value2' => $value2,
        'value3' => $value3,
        'value4' => $value4
    ];
    $stmt->execute($criteria);
    return $stmt;
}

function lastInsertId()
{
    return $GLOBALS['pdo']->lastInsertId();
}

function UpdateByQuery($query)
{

    $stmt = $GLOBALS['pdo']->prepare($query);
    $stmt->execute();
    return $stmt;
}

function update($table, $data, $id, $whereClause)
{
    $setPart = array();
    $bindings = array();

    foreach ($data as $key => $value) {
        $setPart[] = "{$key} = :{$key}";
        $bindings[":{$key}"] = $value;
    }

    $bindings[":id"] = $id;

    $sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE $whereClause = :id";
    $stmt = $GLOBALS['pdo']->prepare($sql);

    $stmt->execute($bindings);
    return $stmt;
}


function updateQueryVersion2($table, $data, $where)
{
    //merge data and where together
    $collection = array_merge($data, $where);

    //collect the values from collection
    $values = array_values($collection);

    //setup fields
    $fieldDetails = null;
    foreach ($data as $key => $value) {
        $fieldDetails .= "$key = ?,";
    }
    $fieldDetails = rtrim($fieldDetails, ',');

    //setup where
    $whereDetails = null;
    $i = 0;
    foreach ($where as $key => $value) {
        $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
        $i++;
    }

    $stmt = $GLOBALS['pdo']->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails", $values);

    return $stmt->rowCount();
}



function updateWithTwoWhereClause($table, $data, $ClauseOne, $value, $ClauseTwo, $value2)
{
    $setPart = array();
    $bindings = array();

    foreach ($data as $key => $value) {
        $setPart[] = "{$key} = :{$key}";
        $bindings[":{$key}"] = $value;
    }

    $sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE $ClauseOne = $value AND $ClauseTwo = $value2";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->execute();
    return $stmt;
}

function getLastDays($Day, $NumberOfTheWeeks)
{
    trim($Day, "+");
    $from = new DateTime();
    $from->modify('-' . $NumberOfTheWeeks . ' weeks');
    return new DatePeriod(
        $from,
        DateInterval::createFromDateString($Day),
        new DateTime()
        , DatePeriod::EXCLUDE_START_DATE
    );
}



function hashPass($pass)
{
    return password_hash($pass, PASSWORD_DEFAULT);
}

function passwordChange($id, $pass)
{
    //$connection = $GLOBALS['pdo']->connection;
    if (isset($id) && isset($pass)) {
        $stmt = $GLOBALS['pdo']->prepare('UPDATE tblusers SET UserPassword = ? WHERE UserID = ?');
        if ($stmt->execute([$id, hashPass($pass)])) {
            return true;
        } else {
            $GLOBALS['pdo']->msg = 'Password change failed.';
            return false;
        }
    } else {
        $GLOBALS['pdo']->msg = 'Provide an ID and a password.';
        return false;
    }
}


function updateUserPassword($criteria, $updateField, $updateValue)
{
    $stmt = $GLOBALS['pdo']->prepare('UPDATE tblusers
          SET UserPassword = :UserPassword
          WHERE ' . $updateField . ' = :updateValue');
    $criteria['updateValue'] = $updateValue;
    $stmt->execute($criteria);
    echo '<script language="javascript">';
    echo 'alert("The requested information has been succesfully saved.")';
    echo '</script>';
    //  echo "<meta http-equiv='refresh' content='0'>";
    echo "The requested information has been succesfully saved.";
}