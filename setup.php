<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007 Harvey Kane <code@ragepank.com>
 * Copyright 2007 Michael Holt <code@gardyneholt.co.nz>
 * Copyright 2007 Melanie Schulz <mel@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @author  Michael Cochrane <code@gardyneholt.co.nz>
 * @author  Melanie Schulz <mel@gardyneholt.co.nz>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */



//SMS
if (!Jojo::tableexists('smslog')) {
    echo "Table <b>smslog</b> Does not exist - creating empty table<br />";
    $query = "
        CREATE TABLE `smslog` (
        `smslogid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `sent` INT NOT NULL ,
        `from` VARCHAR( 30 ) NOT NULL ,
        `phone` VARCHAR( 30 ) NOT NULL ,
        `message` TEXT NOT NULL
        ) ENGINE = MYISAM ;
        ";
    Jojo::updateQuery($query);
}


/* add page to menu - under admin section where it requires admin access */
$data = Jojo::selectQuery("SELECT * FROM page WHERE pg_link='jojo_txt.php'");
if (count($data) == 0) {
    echo "Adding <b>TXT</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO page SET pg_title='TXT', pg_link='jojo_txt.php', pg_url='txt', pg_parent=0, pg_order=100");
}

if (Jojo::tableexists('option')) {
    //Insert new option for txt mobile number
    $data = Jojo::selectQuery("SELECT * FROM `option` WHERE op_name = 'mobilenumber'");
    if (count($data) == 0) {
        echo "Adding <b>mobilenumber</b> option<br />";
        Jojo::insertQuery("INSERT INTO `option` SET op_name = 'mobilenumber', op_value = '', op_category='jojo_txt'");
    }
}