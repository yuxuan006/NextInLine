<?php

require('header.inc.php'); 

$result = mysql_query("SELECT * FROM nxline_shop WHERE shop_id = " . mysql_real_escape_string($_GET['shop_id']));

if(!$result)
{ 
    echo 'The shop could not be displayed, please try again later.' . mysql_error();
}
else
{
    if(mysql_num_rows($result) == 0)
    {
        echo 'This shop does not exist.';
    }
    else
    {
        //display category data
        while($row = mysql_fetch_assoc($result))
        {
            if($user['user_role_name'] == "Owner" && $user['user_id'] == $row['user_id'])
            {
                echo'<form id="addAddrForm" method="post" action="addAddress.php?shop_id='. $row['shop_id'].'">
                        <div id="addAdd">
                                <table id="addAddTable">
                                        <tr>
                                                <th colspan="4" align="center">Add Shop Address</th>
                                        </tr>
                                        <tr>
                                                <td>Address: </td>
                                                <td colspan="3"><input type="text" name="address" id="address"/></td>
                                        </tr>
                                        <tr>
                                                <td>City: </td>
                                                <td colspan="3"><input type="text" name="city" id="city"/></td>
                                        </tr>
                                        <tr>
                                                <td>State: </td>
                                                <td colspan="3"><input type="text" name="state" id="state" maxlength="2" size="2"/></td>
                                        </tr>
                                        <tr>
                                                <td>Zip Code: </td>
                                                <td colspan="3"><input type="text" name="zipCode" id="zipCode" maxlength="5" size="5"/></td>
                                        </tr>
                                        <tr>
                                                <td>Country: </td>
                                                <td colspan="3"><input type="text" name="country" id="country"/></td>
                                        </tr>
                                        <tr>
                                                <td>Phone Number: </td>
                                                <td><input type="text" name="areaCode" id="areaCode" maxlength="3" size="3"/></td>
                                                <td><input type="text" name="mid" id="mid" maxlength="3" size="3"/></td>
                                                <td><input type="text" name="number" id="number" maxlength="4" size="4"/></td>
                                        </tr>
                                        <tr>
                                            <td>Number of Reservation: </td>
                                            <td><input type="text" name="numReserve" id="country" size="3"/></td>
                                        </tr>
                                        <tr>
                                        <td colspan ="4" align="center"><button type="submit" name="addAddButton" id="addAddButton">Add Address</button></td>
                                        </tr>                                        
                                </table>
                        </div>';
                
                if(isset($_POST['addAddButton']))
                {
                    $address = $_POST['address'];
                    $city = $_POST['city'];
                    $state = $_POST['state'];
                    $zipCode = $_POST['zipCode'];
                    $country = $_POST['country'];
                    $areaCode = $_POST['areaCode'];
                    $mid = $_POST['mid'];
                    $number = $_POST['number'];
                    $numReserve = $_POST['numReserve'];
                    $intNumReserve = intval($numReserve);
                    
                    print($numReserve);
                    
                    $checkAddress = mysql_query("SELECT * FROM nxline_shop_addr WHERE address = '".$address."' AND city ='".$city."' AND state ='".$state."' AND country ='".$country."' AND zip_code='".$zipCode."' AND shop_id = ".(int) $row['shop_id']." ");
                    
                    if($address == "")
                    {
                        echo'Please provide your shop address!<br/>';
                    }
                    else if($city == "")
                    {
                        echo'Please enter the city!<br/>';
                    }
                    else if($state == "")
                    {
                        echo'Please enter the state!<br/>';
                    }
                    else if($zipCode == "")
                    {
                        echo'Please enter zip code number!<br/>';
                    }
                    else if($country == "")
                    {
                        echo'Please enter country!<br/>';
                    }
                    else if($areaCode == "" || $mid == "" || $number == "")
                    {
                        echo'Please enter correct phone number!<br/>';
                    }
                    else if(mysql_num_rows($checkAddress) == 1)
                    {
                        echo'This address is already in the system! <br/>';
                    }
                    else if ($numReserve ="")
                    {
                        echo'Please enter a number of reservations that is allowed per time slot!<br/>';
                    }
                    else
                    {
                        mysql_query("INSERT INTO nxline_shop_addr(address, city, state, country, zip_code, shop_id, phone, numReserve ) "
                                . "VALUES('". $address."', '". $city."', '". $state."', '". $country ."', ".$zipCode.", ".(int) $row['shop_id'].", '". $areaCode."-".$mid."-".$number."', ".$intNumReserve.")");
                        
                        $query = mysql_query("SELECT * FROM nxline_shop_addr "
                                . "WHERE address = '".$address."' AND city ='".$city."' AND state ='".$state."' "
                                . "AND country ='".$country."' AND zip_code='".$zipCode."' AND shop_id = ".(int) $row['shop_id']." ");

                        
                        if(mysql_num_rows($query))
                        {
                            echo'A new address has been added! <br/>';
                        }
                        else
                        {
                            echo'Error! Please try again! <br/>';
                        }
                    }
                }
            }
            else
            {
                header("Location: ./index.php");
            }
        }
    }
}

require('footer.inc.php');